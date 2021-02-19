<?php

namespace Database\Seeders;

use App\Enums\SystemEnums;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'see']);
        Permission::create(['name' => 'approve']);
        Permission::create(['name' => 'reject']);
        Permission::create(['name' => 'cancel']);

        $role_employee = Role::create(['name' => SystemEnums::UserIsEmployee]);
        $role_employee->givePermissionTo(['see', 'cancel']);
        $employee = User::factory()->create([
            'name' => 'Employee Name',
            'email' => 'employee@expense.com',
            'type' => SystemEnums::UserIsEmployee,
        ]);
        $employee->assignRole($role_employee);

        $role_manager = Role::create(['name' => SystemEnums::UserIsManager]);
        $role_manager->givePermissionTo(['see', 'approve', 'reject']);
        $manager = User::factory()->create([
            'name' => 'Manager Name',
            'email' => 'manager@expense.com',
            'type' => SystemEnums::UserIsManager,
        ]);
        $manager->assignRole($role_manager);
    }
}
