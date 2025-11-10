<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $organizationId;

    public function __construct($organizationId = null)
    {
        $this->organizationId = $organizationId;
    }

    public function collection()
    {
        $query = Event::with('organization');
        
        if ($this->organizationId) {
            $query->where('organization_id', $this->organizationId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Event Name',
            'Organization',
            'Category',
            'Date',
            'Time',
            'Location',
            'Max Volunteers',
            'Registered',
            'Status',
            'Created At',
        ];
    }

    public function map($event): array
    {
        return [
            $event->event_id,
            $event->event_name,
            $event->organization->organization_name ?? 'N/A',
            $event->category ?? 'N/A',
            $event->event_date,
            $event->start_time . ' - ' . $event->end_time,
            $event->location,
            $event->max_volunteers,
            $event->registered_count,
            ucfirst($event->status),
            $event->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
