<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = DB::table('roles')->get();
        $admin = ['read', 'write', 'update', 'edit', 'create', 'delete'];
        $client = ['update', 'edit', 'create'];
        foreach ($admin as $key => $item) {
            $permission_id = DB::table('permissions')->where('name', $item)->first();
            $role_permissions = DB::table('role_has_permissions')
                ->where('permission_id', $permission_id->id)
                ->where('role_id', $roles[0]->id)
                ->first();
            if (!$role_permissions) {
                DB::table('role_has_permissions')->insert([
                    [
                        'permission_id' => $permission_id->id,
                        'role_id' => $roles[0]->id,
                    ],
                ]);
            }
        }
        foreach ($client as $key => $item) {
            $permission_id = DB::table('permissions')->where('name', $item)->first();
            $role_permissions = DB::table('role_has_permissions')
                ->where('permission_id', $permission_id->id)
                ->where('role_id', $roles[1]->id)
                ->first();
            if (!$role_permissions) {
                DB::table('role_has_permissions')->insert([
                    [
                        'permission_id' => $permission_id->id,
                        'role_id' => $roles[1]->id,
                    ],
                ]);
            }
        }
    }
}
