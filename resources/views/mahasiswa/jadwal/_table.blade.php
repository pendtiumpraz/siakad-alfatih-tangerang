<div class="bg-white rounded-lg overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($rows as $jadwal)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->hari }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Illuminate\Support\Str::substr($jadwal->jam_mulai, 0, 5) }} - {{ \Illuminate\Support\Str::substr($jadwal->jam_selesai, 0, 5) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="font-medium">{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $jadwal->mataKuliah->kode_mk ?? '' }} · {{ $jadwal->mataKuliah->sks ?? '-' }} SKS</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->dosen->nama_lengkap ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->kelas }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($jadwal->ruangan)
                            @if(($jadwal->ruangan->tipe ?? 'luring') === 'daring')
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mb-1 w-fit">
                                        <i class="fas fa-globe mr-1"></i> {{ $jadwal->ruangan->nama_ruangan }}
                                    </span>
                                    @if($jadwal->ruangan->url)
                                        <a href="{{ $jadwal->ruangan->url }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            <i class="fas fa-external-link-alt mr-1"></i>Buka Link Meeting
                                        </a>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">{{ $jadwal->ruangan->nama_ruangan }}</span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
