<?php

namespace App\Exports;

use App\Models\EventRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        $query = EventRegistration::with(['volunteer', 'event']);
        
        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Volunteer Name',
            'Event Name',
            'Status',
            'Hours Contributed',
            'Registered At',
            'Updated At',
        ];
    }

    public function map($registration): array
    {
        return [
            $registration->registration_id,
            $registration->volunteer->first_name . ' ' . $registration->volunteer->last_name,
            $registration->event->event_name,
            ucfirst($registration->status),
            $registration->hours_contributed ?? 0,
            $registration->registered_at ?? $registration->created_at->format('Y-m-d H:i:s'),
            $registration->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
