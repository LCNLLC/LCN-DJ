<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
{
    return User::with('customer_package') // Eager load customer_package relationship
        ->where('user_type', 'customer') // Filter only users with user_type = 'customer'
        ->get()
        ->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'package_name' => $user->customer_package->name ?? 'N/A', // Get package name or 'N/A' if null
            ];
        });
}

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Customer Package'
        ];
    }

}
