@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Daftar Ulang</h1>
        <a href="{{ route('admin.daftar-ulang.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Status & Payment Info -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Daftar Ulang</h6>
                </div>
                <div class="card-body text-center">
                    @if($daftarUlang->status == 'pending')
                        <div class="mb-3">
                            <i class="fas fa-clock fa-4x text-warning"></i>
                        </div>
                        <h5 class="text-warning font-weight-bold">Menunggu Verifikasi</h5>
                        <p class="text-muted mb-4">Daftar ulang ini belum diverifikasi</p>

                        <!-- Action Buttons -->
                        <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#verifyModal">
                            <i class="fas fa-check-circle"></i> Verifikasi & Buat Akun
                        </button>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times-circle"></i> Tolak
                        </button>
                    @elseif($daftarUlang->status == 'verified')
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h5 class="text-success font-weight-bold">Terverifikasi</h5>
                        <p class="text-muted">Diverifikasi pada: <br><strong>{{ $daftarUlang->tanggal_verifikasi->format('d/m/Y H:i') }}</strong></p>
                        <p class="text-muted">Oleh: <strong>{{ $daftarUlang->verifier->name ?? '-' }}</strong></p>
                        @if($daftarUlang->mahasiswaUser)
                            <div class="alert alert-success">
                                <i class="fas fa-user-check"></i><br>
                                <strong>Akun Mahasiswa Sudah Dibuat</strong><br>
                                <small>Username: {{ $daftarUlang->mahasiswaUser->username }}</small>
                            </div>
                        @endif
                    @else
                        <div class="mb-3">
                            <i class="fas fa-times-circle fa-4x text-danger"></i>
                        </div>
                        <h5 class="text-danger font-weight-bold">Ditolak</h5>
                        <p class="text-muted">Ditolak pada: <br><strong>{{ $daftarUlang->tanggal_verifikasi->format('d/m/Y H:i') }}</strong></p>
                        <p class="text-muted">Oleh: <strong>{{ $daftarUlang->verifier->name ?? '-' }}</strong></p>
                    @endif

                    @if($daftarUlang->keterangan)
                        <div class="alert alert-info mt-3">
                            <strong>Keterangan:</strong><br>
                            {{ $daftarUlang->keterangan }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Biaya Daftar Ulang:</th>
                            <td class="text-right">
                                <strong class="text-success">
                                    Rp {{ number_format($daftarUlang->biaya_daftar_ulang, 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Metode:</th>
                            <td class="text-right">
                                <span class="badge badge-info">
                                    {{ strtoupper($daftarUlang->metode_pembayaran) }}
                                </span>
                            </td>
                        </tr>
                        @if($daftarUlang->nomor_referensi)
                            <tr>
                                <th>No. Referensi:</th>
                                <td class="text-right">
                                    <code>{{ $daftarUlang->nomor_referensi }}</code>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Tanggal Submit:</th>
                            <td class="text-right">
                                {{ $daftarUlang->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    </table>

                    @if($daftarUlang->bukti_pembayaran)
                        <div class="mt-3">
                            <label class="font-weight-bold">Bukti Pembayaran:</label>
                            <div class="text-center">
                                <a href="{{ $daftarUlang->bukti_pembayaran }}" target="_blank" class="btn btn-primary btn-block">
                                    <i class="fas fa-external-link-alt"></i> Lihat Bukti Pembayaran
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Pendaftar Info -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Pendaftar</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Data Pribadi</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">NIM Sementara:</th>
                                    <td><strong>{{ $daftarUlang->nim_sementara }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nomor Pendaftaran:</th>
                                    <td>{{ $daftarUlang->pendaftar->nomor_pendaftaran }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap:</th>
                                    <td>{{ $daftarUlang->pendaftar->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat/Tgl Lahir:</th>
                                    <td>{{ $daftarUlang->pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($daftarUlang->pendaftar->tanggal_lahir)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin:</th>
                                    <td>{{ $daftarUlang->pendaftar->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>Agama:</th>
                                    <td>{{ $daftarUlang->pendaftar->agama }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat:</th>
                                    <td>{{ $daftarUlang->pendaftar->alamat }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Kontak & Akademik</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Email:</th>
                                    <td>{{ $daftarUlang->pendaftar->email }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon:</th>
                                    <td>{{ $daftarUlang->pendaftar->no_telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan:</th>
                                    <td><strong>{{ $daftarUlang->pendaftar->jurusan->nama ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Jalur:</th>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ strtoupper($daftarUlang->pendaftar->jalur_pendaftaran) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Angkatan:</th>
                                    <td>{{ date('Y') }}</td>
                                </tr>
                            </table>

                            @if($daftarUlang->pendaftar->google_drive_file_id)
                                <div class="mt-3">
                                    <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Foto</h6>
                                    <img src="https://drive.google.com/thumbnail?id={{ basename(parse_url($daftarUlang->pendaftar->google_drive_file_id, PHP_URL_PATH)) }}&sz=w200"
                                         alt="Foto"
                                         class="img-thumbnail"
                                         style="max-width: 200px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Dokumen Pendukung</h6>
                    <div class="row">
                        @php
                            $documents = [
                                ['name' => 'Ijazah', 'field' => 'ijazah_google_drive_id'],
                                ['name' => 'Transkrip Nilai', 'field' => 'transkrip_google_drive_id'],
                                ['name' => 'KTP', 'field' => 'ktp_google_drive_id'],
                                ['name' => 'Kartu Keluarga', 'field' => 'kk_google_drive_id'],
                                ['name' => 'Akta Kelahiran', 'field' => 'akta_google_drive_id'],
                                ['name' => 'SKTM', 'field' => 'sktm_google_drive_id'],
                            ];
                        @endphp

                        @foreach($documents as $doc)
                            @if($daftarUlang->pendaftar->{$doc['field']})
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                            <p class="mb-2"><strong>{{ $doc['name'] }}</strong></p>
                                            <a href="{{ $daftarUlang->pendaftar->{$doc['field']} }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-external-link-alt"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.daftar-ulang.verify', $daftarUlang->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="verifyModalLabel">
                        <i class="fas fa-check-circle"></i> Verifikasi Daftar Ulang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Perhatian:</strong> Dengan memverifikasi daftar ulang ini, sistem akan otomatis membuat:
                        <ul class="mb-0 mt-2">
                            <li>Akun user dengan role <strong>mahasiswa</strong></li>
                            <li>Data mahasiswa di sistem</li>
                            <li>Username: <strong>{{ $daftarUlang->nim_sementara }}</strong></li>
                            <li>Password akan di-generate otomatis</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Catatan verifikasi (opsional)"></textarea>
                    </div>

                    <p class="mb-0 text-muted">
                        <small>Pastikan semua data dan bukti pembayaran sudah benar sebelum verifikasi.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Ya, Verifikasi & Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.daftar-ulang.reject', $daftarUlang->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle"></i> Tolak Daftar Ulang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Daftar ulang yang ditolak tidak dapat diubah kembali. Pastikan alasan penolakan sudah jelas.
                    </div>

                    <div class="form-group">
                        <label for="keterangan_reject">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan_reject" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                        <small class="text-muted">Alasan ini akan ditampilkan kepada pendaftar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
