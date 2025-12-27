<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Global SweetAlert configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Handle Laravel flash messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{!! addslashes(session("success")) !!}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{!! addslashes(session("error")) !!}',
                confirmButtonColor: '#2D5F3F'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{!! addslashes(session("warning")) !!}',
                confirmButtonColor: '#D4AF37'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{!! addslashes(session("info")) !!}'
            });
        @endif
    });

    // =====================================================
    // GLOBAL SWAL CONFIRM FUNCTIONS
    // =====================================================

    function confirmAction(form, title, text, confirmButtonText = 'Ya, Lanjutkan!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmDelete(form, itemName = 'data ini') {
        Swal.fire({
            title: 'Hapus Data?',
            text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmApprove(form, itemName = 'data ini') {
        Swal.fire({
            title: 'Setujui Data?',
            text: `Apakah Anda yakin ingin menyetujui ${itemName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmSubmit(form, title = 'Submit Data?', text = 'Data yang sudah disubmit tidak bisa diubah lagi.') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmRestore(form, itemName = 'data ini') {
        Swal.fire({
            title: 'Restore Data?',
            text: `Apakah Anda yakin ingin mengembalikan ${itemName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Restore!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmVerify(form, itemName = 'pembayaran ini') {
        Swal.fire({
            title: 'Verifikasi Pembayaran?',
            text: `Apakah Anda yakin ingin memverifikasi ${itemName} sebagai LUNAS?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmForceApprove(form) {
        Swal.fire({
            title: 'Force Approve KRS?',
            html: '<p>PERINGATAN: Approve KRS meskipun mahasiswa belum bayar SPP.</p><p class="text-sm mt-2">Gunakan hanya untuk kasus khusus (beasiswa, cicilan, darurat).</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Force Approve!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmBatchApprove(form, prodiName) {
        Swal.fire({
            title: 'Approve Semua KRS?',
            html: `<p>Approve semua KRS yang sudah bayar SPP untuk <strong>${prodiName}</strong>?</p><p class="text-sm text-gray-500 mt-2">Yang belum bayar akan di-skip otomatis.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Approve Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmGenerate(form, text = 'Proses ini mungkin memakan waktu beberapa menit.') {
        Swal.fire({
            title: 'Generate Data?',
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Generate!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function showSuccess(message) {
        Toast.fire({ icon: 'success', title: message });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            confirmButtonColor: '#2D5F3F'
        });
    }

    function showInfo(message) {
        Toast.fire({ icon: 'info', title: message });
    }

    // =====================================================
    // ALPINE.JS COMPATIBLE - Window Object
    // =====================================================
    window.swalConfirmDelete = function(event, itemName = 'data ini') {
        event.preventDefault();
        confirmDelete(event.target, itemName);
    };

    window.swalConfirmRestore = function(event, itemName = 'data ini') {
        event.preventDefault();
        confirmRestore(event.target, itemName);
    };

    window.swalConfirmApprove = function(event, itemName = 'data ini') {
        event.preventDefault();
        confirmApprove(event.target, itemName);
    };

    window.swalConfirmAction = function(event, title, text) {
        event.preventDefault();
        confirmAction(event.target, title, text);
    };

    window.swalConfirmVerify = function(event, itemName = 'pembayaran ini') {
        event.preventDefault();
        confirmVerify(event.target, itemName);
    };

    window.swalConfirmGenerate = function(event, text) {
        event.preventDefault();
        confirmGenerate(event.target, text);
    };

    window.swalConfirmForceApprove = function(event) {
        event.preventDefault();
        confirmForceApprove(event.target);
    };

    window.swalConfirmBatchApprove = function(event, prodiName) {
        event.preventDefault();
        confirmBatchApprove(event.target, prodiName);
    };
</script>
