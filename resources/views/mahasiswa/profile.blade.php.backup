@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Profil Mahasiswa</h1>
    </div>

    <div class="islamic-divider"></div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">NIM</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->nim }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Nama Lengkap</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->nama_lengkap }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Program Studi</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Angkatan</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->angkatan }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Semester Aktif</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->semester_aktif }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Status</h3>
                <p class="mt-1 text-lg">
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $mahasiswa->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($mahasiswa->status) }}
                    </span>
                </p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->tempat_lahir }}, {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d F Y') : '-' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Jenis Kelamin</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500">Alamat</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->alamat ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">No. Telepon</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->no_telepon ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Email</h3>
                <p class="mt-1 text-lg text-gray-900">{{ $mahasiswa->user->email ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
