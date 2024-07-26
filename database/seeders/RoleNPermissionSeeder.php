<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleNPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Permissions
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);
        Permission::create(['name' => 'publish categories']);
        Permission::create(['name' => 'unpublish categories']);

        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'publish products']);
        Permission::create(['name' => 'unpublish products']);

        Permission::create(['name' => 'manage orders']);

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view permissions']);

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo('manage products');
        $editor->givePermissionTo('create products');
        $editor->givePermissionTo('edit products');
        $editor->givePermissionTo('manage categories');
        $editor->givePermissionTo('create categories');

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo('manage products');
        $manager->givePermissionTo('edit products');
        $manager->givePermissionTo('manage orders');


        $admin = Role::create(['name' => 'Super-Admin']);


        $superAdmin = User::create([
            'name' => 'Rana Bepari',
            'email' => 'admin@rana.my.id',
            'password' => Hash::make('admin1122'),
        ]);

        $superAdmin->assignRole($admin);

        $user1 = User::create([
            'name' => 'Manager',
            'email' => 'manager@rana.my.id',
            'password' => Hash::make('manager1122'),
        ]);

        $user1->assignRole($manager);

        $user2 = User::create([
            'name' => 'Editor',
            'email' => 'editor@rana.my.id',
            'password' => Hash::make('editor1122'),
        ]);

        $user2->assignRole($editor);

    }
}
