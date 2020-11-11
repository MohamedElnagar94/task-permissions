<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\DataTables\RolesDataTable;
use App\Permission;
use App\Role;
use App\RolePermission;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolesPermissionsController extends Controller
{
    public function roles(RolesDataTable $role)
    {
        return $role->render('roles');
    }

    public function showRole(Request $request)
    {
        $role = Role::where('id', $request->role_id)->first();
        return response()->json(['role' => $role], 200);
    }

    public function updateRole(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->passes()) {
            Role::where('id', $request->role_id)->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);
            return response()->json(['message' => 'role is updated successfully'], 200);
        } else {
            return response()->json(["error" => $validator->messages()], 404);
        }
    }

    public function destroyRole(Request $request)
    {
        $role = Role::findOrFail($request->id);
        DB::table('role_has_permissions')->where('role_id', $request->id)->delete();
        DB::table('model_has_roles')->where('role_id', $request->id)->delete();
        if ($role) {
            $role->delete();
        }
        return response()->json(['message' => 'role is deleted successfully'], 200);
    }

    public function addRole(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->passes()) {
            Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);
            return response()->json(['message' => 'role is created successfully'], 200);
        } else {
            return response()->json(["error" => $validator->messages()], 404);
        }
    }

    public function getRoles(Request $request)
    {
        $roles = DB::table('roles')->get();
        $roles_models = DB::table('model_has_roles')->where('model_id', $request->user_id)->get();
        return response()->json(['roles' => $roles, 'model' => $roles_models], 200);
    }

    public function setRoles(Request $request)
    {
        foreach ($request->data as $key => $roles) {
            if ($roles['active'] === 'true') {
                $roles_models = DB::table('model_has_roles')
                    ->where('model_id', $roles['userId'])
                    ->first();
                $user = User::where('id', $roles['userId'])->first();
                $permissions = DB::table('permissions')
                    ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $roles['id'])
                    ->select('permissions.name')
                    ->get();
                if (!$roles_models) {
                    foreach ($permissions as $permission) {
                        $user->givePermissionTo($permission->name);
                    }
                    $user->assignRole($roles['name']);
                } else {
                    if ($roles_models->role_id != $roles['id']){
                        DB::table('model_has_roles')
                            ->where('model_id', $roles['userId'])
                            ->delete();
                        DB::table('model_has_permissions')
                            ->where('model_id', $roles['userId'])
                            ->delete();
                        foreach ($permissions as $permission) {
                            $user->givePermissionTo($permission->name);
                        }
                        $user->assignRole($roles['name']);
                    }
                }
            }
        }
        return response()->json(['message' => 'role is created successfully'], 200);
    }

//    permissions
    public function permissions(PermissionDataTable $permission)
    {
        return $permission->render('permissions');
    }

    public function showPermission(Request $request)
    {
        $permission = Permission::where('id', $request->permission_id)->first();
        return response()->json(['permission' => $permission], 200);
    }

    public function updatePermission(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->passes()) {
            Permission::where('id', $request->permission_id)->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);
            return response()->json(['message' => 'permission is updated successfully'], 200);
        } else {
            return response()->json(["error" => $validator->messages()], 404);
        }
    }

    public function destroyPermission(Request $request)
    {
        $permission = Permission::findOrFail($request->id);
        DB::table('role_has_permissions')->where('permission_id', $request->id)->delete();
        DB::table('model_has_permissions')->where('permission_id', $request->id)->delete();
        if ($permission) {
            $permission->delete();
        }
        return response()->json(['message' => 'permission is deleted successfully'], 200);
    }

    public function addPermission(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->passes()) {
            Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);
            return response()->json(['message' => 'permission is created successfully'], 200);
        } else {
            return response()->json(["error" => $validator->messages()], 404);
        }
    }

    public function getPermissions(Request $request)
    {
        $permissions = DB::table('permissions')->get();
        $role = DB::table('roles')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->where('roles.id', $request->role_id)
            ->select(
                'roles.id as role_id',
                'roles.name as role_name',
                'roles.guard_name as role_guard_name',
                'role_has_permissions.permission_id as role_permission_permission_id',
                'role_has_permissions.role_id as role_permission_role_id'
            )
            ->get();
        return response()->json(['permissions' => $permissions, 'role' => $role], 200);
    }

    public function setPermissions(Request $request)
    {
        foreach ($request->data as $key => $item) {
            $role_permission = RolePermission::where('permission_id', $item['id'])->where('role_id', $item['roleId'])->first();
            if ($item['active'] === 'true') {
                if (!$role_permission) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $item['id'],
                        'role_id' => $item['roleId'],
                    ]);
                }
            } else {
                if ($role_permission) {
                    DB::table('role_has_permissions')
                        ->where('permission_id', $item['id'])
                        ->where('role_id', $item['roleId'])
                        ->delete();
                }
            }
        }
        return response()->json(['message' => 'permission is updated successfully'], 200);
    }

}
