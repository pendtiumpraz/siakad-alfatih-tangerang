<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operatorUser = User::where('role', 'operator')->first();

        if ($operatorUser) {
            Operator::create([
                'user_id' => $operatorUser->id,
                'nama_lengkap' => 'Siti Aminah',
                'no_telepon' => '081234567890',
            ]);
        }
    }
}
