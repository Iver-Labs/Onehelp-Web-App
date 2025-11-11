<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Attendance;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Generate volunteer activity report (PDF)
     */
    public function volunteerActivityReport($volunteerId)
    {
        $volunteer = Volunteer::with(['user', 'registrations.event', 'registrations.event.organization'])
            ->findOrFail($volunteerId);

        $registrations = $volunteer->registrations()
            ->with('event', 'event.organization')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'totalEvents' => $registrations->where('status', 'approved')->count(),
            'completedEvents' => $registrations->where('status', 'approved')
                ->filter(function($reg) {
                    return Carbon::parse($reg->event->event_date)->isPast();
                })->count(),
            'upcomingEvents' => $registrations->where('status', 'approved')
                ->filter(function($reg) {
                    return Carbon::parse($reg->event->event_date)->isFuture();
                })->count(),
            'totalHours' => $registrations->where('status', 'approved')
                ->sum('hours_contributed') ?? 0,
        ];

        $pdf = Pdf::loadView('reports.volunteer-activity', compact('volunteer', 'registrations', 'stats'));
        
        return $pdf->download('volunteer-activity-report-' . $volunteer->volunteer_id . '.pdf');
    }

    /**
     * Generate event participation report (PDF)
     */
    public function eventParticipationReport($eventId)
    {
        $event = Event::with(['organization', 'registrations.volunteer'])
            ->findOrFail($eventId);

        $registrations = $event->registrations()
            ->with('volunteer')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'totalApplicants' => $registrations->count(),
            'approved' => $registrations->where('status', 'approved')->count(),
            'pending' => $registrations->where('status', 'pending')->count(),
            'rejected' => $registrations->where('status', 'rejected')->count(),
            'maxVolunteers' => $event->max_volunteers,
            'spotsRemaining' => max(0, $event->max_volunteers - $registrations->where('status', 'approved')->count()),
        ];

        $pdf = Pdf::loadView('reports.event-participation', compact('event', 'registrations', 'stats'));
        
        return $pdf->download('event-participation-report-' . $event->event_id . '.pdf');
    }

    /**
     * Generate organization summary report (PDF)
     */
    public function organizationSummaryReport($organizationId)
    {
        $organization = Organization::with(['user', 'events', 'events.registrations'])
            ->findOrFail($organizationId);

        $events = $organization->events()
            ->with('registrations')
            ->orderBy('event_date', 'desc')
            ->get();

        $stats = [
            'totalEvents' => $events->count(),
            'upcomingEvents' => $events->filter(function($event) {
                return Carbon::parse($event->event_date)->isFuture();
            })->count(),
            'completedEvents' => $events->filter(function($event) {
                return Carbon::parse($event->event_date)->isPast();
            })->count(),
            'totalVolunteers' => $organization->events()
                ->join('event_registrations', 'events.event_id', '=', 'event_registrations.event_id')
                ->where('event_registrations.status', 'approved')
                ->distinct('event_registrations.volunteer_id')
                ->count('event_registrations.volunteer_id'),
            'totalHours' => $organization->events()
                ->join('event_registrations', 'events.event_id', '=', 'event_registrations.event_id')
                ->where('event_registrations.status', 'approved')
                ->sum('event_registrations.hours_contributed') ?? 0,
        ];

        $pdf = Pdf::loadView('reports.organization-summary', compact('organization', 'events', 'stats'));
        
        return $pdf->download('organization-summary-report-' . $organization->organization_id . '.pdf');
    }

    /**
     * Generate system-wide summary report (PDF) - Admin only
     */
    public function systemSummaryReport()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalVolunteers' => Volunteer::count(),
            'totalOrganizations' => Organization::count(),
            'verifiedOrganizations' => Organization::where('is_verified', true)->count(),
            'totalEvents' => Event::count(),
            'activeEvents' => Event::where('status', 'open')
                ->where('event_date', '>=', now())
                ->count(),
            'completedEvents' => Event::whereDate('event_date', '<', now())->count(),
            'totalRegistrations' => EventRegistration::count(),
            'approvedRegistrations' => EventRegistration::where('status', 'approved')->count(),
            'totalHours' => EventRegistration::where('status', 'approved')
                ->sum('hours_contributed') ?? 0,
        ];

        // Top organizations by events
        $topOrganizations = Organization::withCount('events')
            ->orderBy('events_count', 'desc')
            ->take(10)
            ->get();

        // Recent events
        $recentEvents = Event::with('organization')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $pdf = Pdf::loadView('reports.system-summary', compact('stats', 'topOrganizations', 'recentEvents'));
        
        return $pdf->download('system-summary-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export users to Excel (Admin only)
     */
    public function exportUsers()
    {
        return Excel::download(new \App\Exports\UsersExport, 'users-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export events to Excel
     */
    public function exportEvents(Request $request)
    {
        // If organization, filter by their events
        if (auth()->user()->user_type === 'organization') {
            $organization = Organization::where('user_id', auth()->id())->first();
            return Excel::download(
                new \App\Exports\EventsExport($organization->organization_id),
                'events-' . now()->format('Y-m-d') . '.xlsx'
            );
        }

        // Admin can export all events
        return Excel::download(new \App\Exports\EventsExport(), 'events-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export registrations to Excel
     */
    public function exportRegistrations(Request $request)
    {
        $eventId = $request->get('event_id');
        
        return Excel::download(
            new \App\Exports\RegistrationsExport($eventId),
            'registrations-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Generate volunteer certificate (PDF)
     */
    public function volunteerCertificate($registrationId)
    {
        $registration = EventRegistration::with(['volunteer', 'event', 'event.organization'])
            ->findOrFail($registrationId);

        // Only generate certificate for approved registrations of completed events
        if ($registration->status !== 'approved' || 
            Carbon::parse($registration->event->event_date)->isFuture()) {
            return redirect()->back()->with('error', 'Certificate not available for this event.');
        }

        $pdf = Pdf::loadView('reports.volunteer-certificate', compact('registration'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('certificate-' . $registration->volunteer->first_name . '-' . $registration->event->event_name . '.pdf');
    }
}
