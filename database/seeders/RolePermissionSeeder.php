<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'users',
            'mahasiswa',
            'dosen',
            'operator',
            'program_studi',
            'kurikulum',
            'mata_kuliah',
            'semester',
            'ruangan',
            'jadwal',
            'nilai',
            'khs',
            'pembayaran',
            'role_permissions'
        ];

        // Super Admin - Full CRUD access to all modules
        foreach ($modules as $module) {
            RolePermission::create([
                'role' => 'super_admin',
                'module' => $module,
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
            ]);
        }

        // Operator - Full access to pembayaran, view only mahasiswa
        RolePermission::create([
            'role' => 'operator',
            'module' => 'mahasiswa',
            'can_view' => true,
            'can_create' => false,
            'can_edit' => false,
            'can_delete' => false,
        ]);

        RolePermission::create([
            'role' => 'operator',
            'module' => 'pembayaran',
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);

        // Dosen - Full CRUD for jadwal, nilai, khs; View only for kurikulum, mata_kuliah, mahasiswa
        $dosenFullAccess = ['jadwal', 'nilai', 'khs'];
        foreach ($dosenFullAccess as $module) {
            RolePermission::create([
                'role' => 'dosen',
                'module' => $module,
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
            ]);
        }

        $dosenViewOnly = ['kurikulum', 'mata_kuliah', 'mahasiswa'];
        foreach ($dosenViewOnly as $module) {
            RolePermission::create([
                'role' => 'dosen',
                'module' => $module,
                'can_view' => true,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false,
            ]);
        }

        // Mahasiswa - View only access, can edit own profile
        RolePermission::create([
            'role' => 'mahasiswa',
            'module' => 'mahasiswa',
            'can_view' => true,
            'can_create' => false,
            'can_edit' => true,
            'can_delete' => false,
        ]);

        $mahasiswaViewOnly = ['jadwal', 'pembayaran', 'khs', 'nilai'];
        foreach ($mahasiswaViewOnly as $module) {
            RolePermission::create([
                'role' => 'mahasiswa',
                'module' => $module,
                'can_view' => true,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false,
            ]);
        }
    }
}
