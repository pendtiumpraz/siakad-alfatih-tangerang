<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\User;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first superadmin email
        $superadmin = User::where('role', 'super_admin')->first();
        $adminEmail = $superadmin->email ?? 'admin@staialfatih.ac.id';

        $settings = [
            // SPMB Contact Information
            [
                'key' => 'spmb_email',
                'value' => $adminEmail,
                'group' => 'spmb',
                'type' => 'text',
                'description' => 'Email kontak untuk pendaftaran mahasiswa baru',
            ],
            [
                'key' => 'spmb_phone',
                'value' => '021-12345678',
                'group' => 'spmb',
                'type' => 'text',
                'description' => 'Nomor telepon kontak SPMB',
            ],
            [
                'key' => 'spmb_whatsapp',
                'value' => '6281234567890',
                'group' => 'spmb',
                'type' => 'text',
                'description' => 'Nomor WhatsApp untuk informasi SPMB (format: 628xxx)',
            ],

            // Payment/Bank Information
            [
                'key' => 'bank_name',
                'value' => 'BCA',
                'group' => 'payment',
                'type' => 'text',
                'description' => 'Nama bank untuk pembayaran',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '1234567890',
                'group' => 'payment',
                'type' => 'text',
                'description' => 'Nomor rekening bank',
            ],
            [
                'key' => 'bank_account_name',
                'value' => 'STAI AL-FATIH',
                'group' => 'payment',
                'type' => 'text',
                'description' => 'Nama pemilik rekening',
            ],

            // General Pricing (default values, can be overridden per jalur)
            [
                'key' => 'biaya_uang_gedung',
                'value' => '5000000',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Biaya uang gedung default (dalam Rupiah)',
            ],
            [
                'key' => 'biaya_spp_semester',
                'value' => '2500000',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Biaya SPP per semester default (dalam Rupiah)',
            ],
            [
                'key' => 'biaya_wisuda',
                'value' => '1500000',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Biaya wisuda (dalam Rupiah)',
            ],
            [
                'key' => 'biaya_daftar_ulang',
                'value' => '500000',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Biaya daftar ulang setelah diterima (dalam Rupiah)',
            ],

            // Institution Information
            [
                'key' => 'institution_name',
                'value' => 'STAI AL-FATIH',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Nama institusi',
            ],
            [
                'key' => 'institution_address',
                'value' => 'Jl. Contoh No. 123, Jakarta',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Alamat institusi',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ System settings seeded successfully!');
    }
}
