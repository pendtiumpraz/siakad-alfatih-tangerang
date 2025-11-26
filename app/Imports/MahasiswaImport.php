<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $errors = [];
    protected $successCount = 0;
    protected $skipCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row['nim']) || empty($row['nama_lengkap'])) {
                    $this->skipCount++;
                    continue;
                }

                // Find Program Studi by kode_prodi or nama_prodi
                $programStudi = ProgramStudi::where('kode_prodi', $row['kode_prodi'])
                    ->orWhere('nama_prodi', 'LIKE', '%' . $row['kode_prodi'] . '%')
                    ->first();

                if (!$programStudi) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Program Studi '{$row['kode_prodi']}' tidak ditemukan untuk NIM {$row['nim']}";
                    $this->skipCount++;
                    continue;
                }

                // Check if NIM already exists
                if (Mahasiswa::where('nim', $row['nim'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": NIM {$row['nim']} sudah terdaftar";
                    $this->skipCount++;
                    continue;
                }

                // Check if username already exists
                if (User::where('username', $row['username'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Username '{$row['username']}' sudah digunakan";
                    $this->skipCount++;
                    continue;
                }

                // Check if email already exists
                if (User::where('email', $row['email'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Email '{$row['email']}' sudah digunakan";
                    $this->skipCount++;
                    continue;
                }

                // Use transaction for data consistency
                DB::beginTransaction();

                // Parse tanggal_lahir
                $tanggalLahir = null;
                if (!empty($row['tanggal_lahir'])) {
                    try {
                        // Try various date formats
                        if (is_numeric($row['tanggal_lahir'])) {
                            // Excel date format (days since 1900-01-01)
                            $tanggalLahir = Carbon::createFromFormat('Y-m-d', '1900-01-01')
                                ->addDays($row['tanggal_lahir'] - 2);
                        } else {
                            // Try common formats
                            $tanggalLahir = Carbon::parse($row['tanggal_lahir']);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to parse date for NIM {$row['nim']}: {$row['tanggal_lahir']}");
                    }
                }

                // Determine status and related dates
                $status = strtolower($row['status'] ?? 'aktif');
                $tanggalLulus = null;
                $tanggalDropout = null;

                if ($status === 'lulus' && !empty($row['tanggal_lulus'])) {
                    try {
                        if (is_numeric($row['tanggal_lulus'])) {
                            $tanggalLulus = Carbon::createFromFormat('Y-m-d', '1900-01-01')
                                ->addDays($row['tanggal_lulus'] - 2);
                        } else {
                            $tanggalLulus = Carbon::parse($row['tanggal_lulus']);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to parse tanggal_lulus for NIM {$row['nim']}");
                    }
                } elseif ($status === 'dropout' && !empty($row['tanggal_dropout'])) {
                    try {
                        if (is_numeric($row['tanggal_dropout'])) {
                            $tanggalDropout = Carbon::createFromFormat('Y-m-d', '1900-01-01')
                                ->addDays($row['tanggal_dropout'] - 2);
                        } else {
                            $tanggalDropout = Carbon::parse($row['tanggal_dropout']);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to parse tanggal_dropout for NIM {$row['nim']}");
                    }
                }

                // Determine is_active based on status
                // lulus and dropout should have is_active = false
                $isActive = !in_array($status, ['lulus', 'dropout']);

                // Create User
                $user = User::create([
                    'username' => $row['username'],
                    'name' => $row['nama_lengkap'],
                    'email' => $row['email'],
                    'password' => Hash::make('mahasiswa_staialfatih'),
                    'role' => 'mahasiswa',
                    'is_active' => $isActive,
                ]);

                // Create Mahasiswa
                Mahasiswa::create([
                    'user_id' => $user->id,
                    'program_studi_id' => $programStudi->id,
                    'nim' => $row['nim'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L'),
                    'alamat' => $row['alamat'] ?? null,
                    'no_telepon' => $row['no_telepon'] ?? null,
                    'angkatan' => (int) $row['angkatan'],
                    'status' => $status,
                    'tanggal_lulus' => $tanggalLulus,
                    'tanggal_dropout' => $tanggalDropout,
                ]);

                DB::commit();
                $this->successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                $this->skipCount++;
                Log::error("Import error at row " . ($index + 2) . ": " . $e->getMessage());
            }
        }
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nim' => 'required|string|max:20',
            'nama_lengkap' => 'required|string|max:255',
            'kode_prodi' => 'required|string',
            'angkatan' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P,l,p',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getSkipCount()
    {
        return $this->skipCount;
    }

    public function onError(\Throwable $e)
    {
        // Errors are handled in collection method
    }
}
