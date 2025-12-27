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
                title: '{{ session("success") }}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                confirmButtonColor: '#2D5F3F'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session("warning") }}',
                confirmButtonColor: '#D4AF37'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session("info") }}'
            });
        @endif
    });

    // Custom confirm function for forms
    function confirmAction(form, title, text, confirmButtonText = 'Ya, Lanjutkan!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2D5F3F',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    // Custom confirm for delete actions
    function confirmDelete(form, itemName = 'data ini') {
        Swal.fire({
            title: 'Hapus Data?',
            text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
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

    // Custom confirm for approve actions
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

    // Custom confirm for submit actions
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

    // Custom confirm for restore actions
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

    // Custom confirm for verify actions
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

    // Show success alert
    function showSuccess(message) {
        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Show error alert
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            confirmButtonColor: '#2D5F3F'
        });
    }

    // Show info alert
    function showInfo(message) {
        Toast.fire({
            icon: 'info',
            title: message
        });
    }
</script>
