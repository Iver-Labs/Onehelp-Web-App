<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::with(['volunteer', 'organization'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'User Type',
            'Active',
            'Verified',
            'Name',
            'Created At',
        ];
    }

    public function map($user): array
    {
        $name = '';
        if ($user->user_type === 'volunteer' && $user->volunteer) {
            $name = $user->volunteer->first_name . ' ' . $user->volunteer->last_name;
        } elseif ($user->user_type === 'organization' && $user->organization) {
            $name = $user->organization->organization_name;
        }

        return [
            $user->user_id,
            $user->email,
            ucfirst($user->user_type),
            $user->is_active ? 'Yes' : 'No',
            $user->is_verified ? 'Yes' : 'No',
            $name,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
