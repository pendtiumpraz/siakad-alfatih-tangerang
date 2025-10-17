<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    /**
     * Display permission matrix for all roles and modules
     */
    public function index()
    {
        // Define available roles
        $roles = ['super_admin', 'operator', 'dosen', 'mahasiswa'];

        // Define available modules
        $modules = [
            'users',
            'mahasiswa',
            'dosen',
            'program_studi',
            'mata_kuliah',
            'kurikulum',
            'semester',
            'jadwal',
            'ruangan',
            'nilai',
            'khs',
            'pembayaran',
            'role_permissions',
        ];

        // Get all existing permissions
        $permissions = RolePermission::all();

        // Create permission matrix: role -> module -> permissions
        $permissionMatrix = [];

        foreach ($roles as $role) {
            $permissionMatrix[$role] = [];

            foreach ($modules as $module) {
                // Find existing permission or create default
                $permission = $permissions->where('role', $role)
                    ->where('module', $module)
                    ->first();

                if ($permission) {
                    $permissionMatrix[$role][$module] = [
                        'id' => $permission->id,
                        'can_view' => $permission->can_view,
                        'can_create' => $permission->can_create,
                        'can_edit' => $permission->can_edit,
                        'can_delete' => $permission->can_delete,
                    ];
                } else {
                    // Default permissions (all false except for super_admin)
                    $permissionMatrix[$role][$module] = [
                        'id' => null,
                        'can_view' => $role === 'super_admin',
                        'can_create' => $role === 'super_admin',
                        'can_edit' => $role === 'super_admin',
                        'can_delete' => $role === 'super_admin',
                    ];
                }
            }
        }

        return view('admin.permissions.index', compact('permissionMatrix', 'roles', 'modules'));
    }

    /**
     * Bulk update permissions from the matrix
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*.role' => ['required', 'string', 'in:super_admin,operator,dosen,mahasiswa'],
            'permissions.*.module' => ['required', 'string'],
            'permissions.*.can_view' => ['boolean'],
            'permissions.*.can_create' => ['boolean'],
            'permissions.*.can_edit' => ['boolean'],
            'permissions.*.can_delete' => ['boolean'],
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['permissions'] as $permissionData) {
                RolePermission::updateOrCreate(
                    [
                        'role' => $permissionData['role'],
                        'module' => $permissionData['module'],
                    ],
                    [
                        'can_view' => $permissionData['can_view'] ?? false,
                        'can_create' => $permissionData['can_create'] ?? false,
                        'can_edit' => $permissionData['can_edit'] ?? false,
                        'can_delete' => $permissionData['can_delete'] ?? false,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Permissions updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update permissions: ' . $e->getMessage());
        }
    }
}
