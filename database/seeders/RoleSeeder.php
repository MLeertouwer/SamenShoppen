<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Maak de rollen
        $role_lid = Role::firstOrCreate(['name' => 'lid', 'guard_name' => 'web']);
        $role_beheerder = Role::firstOrCreate(['name' => 'beheerder', 'guard_name' => 'web']);

        // Maak de permissies voor de leden in een array
        $lid_permissies = [
            'create_shopping_list',
            'request_groceries',
            'offer_groceries',
            'register_ride',
            'join_ride',
            'view_ride_requests',
            'create_ride_request',
            'send_messages',
            'give_feedback',
            'pay_contribution',
            'create_payment_request',
            'view_opening_hours'
        ];

        // Maak de permissies voor de beheerders in een array
        $beheerder_permissies = [
            'approve_membership',
            'handle_complaints',
            'manage_users',
            'manage_chats',
            'view_statistics'
        ];

        // Permissies uit de array toevoegen
        foreach (array_merge($lid_permissies, $beheerder_permissies) as $permission_name) {
            Permission::firstOrCreate(['name' => $permission_name, 'guard_name' => 'web']);
        }

        // Koppel de permissies aan de rollen
        $role_lid->syncPermissions($lid_permissies);

        // Beheerder krijgt ALLES (lid permissies + beheerder permissies)
        $role_beheerder->syncPermissions(array_merge($lid_permissies, $beheerder_permissies));

        // Ken de rol toe aan gebruiker ID 1
        // Zoek gebruiker 1, of maak hem aan als hij niet bestaat
        $user = User::firstOrCreate(
            // Geef twee array's mee, de eerste waar Laravel op moet zoeken en de tweede met de data die toegevoegd moet worden
            ['email' => 'admin@samenshoppen.nl'],
            [
                'name' => 'De Beheerder',
                'address' => 'Hoofdkantoor 1',
                'phone' => '0612345678',
                'password' => bcrypt('wachtwoord123'), // Vergeet niet te hashen!
            ]
        );

        // Nu bestaat de user gegarandeerd, dus kunnen we de rol toewijzen
        $user->assignRole($role_beheerder);
    }
}
