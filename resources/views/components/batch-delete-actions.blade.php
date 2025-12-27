{{-- Batch Delete Actions Component --}}
{{-- Usage: @include('components.batch-delete-actions', ['routeName' => 'admin.program-studi.batch-delete']) --}}

<div id="batch-delete-actions" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-red-700 font-medium">
                <span id="selected-count">0</span> item dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="clearSelection()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                Batal
            </button>
            <button type="button" onclick="confirmBatchDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                <i class="fas fa-trash mr-2"></i>Hapus Terpilih
            </button>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="batch-delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">
                Anda yakin ingin menghapus <span id="modal-count" class="font-bold text-red-600">0</span> data yang dipilih?
                <br><span class="text-sm text-gray-500">Data akan dipindahkan ke trash dan bisa di-restore.</span>
            </p>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeBatchDeleteModal()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </button>
                <button type="button" onclick="executeBatchDelete()" id="confirm-delete-btn" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                    <i class="fas fa-trash mr-2"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedIds = [];
    const batchDeleteRoute = "{{ $routeName ?? '' }}";

    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateSelectedIds();
    }

    function updateSelectedIds() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
        
        const actionsDiv = document.getElementById('batch-delete-actions');
        const countSpan = document.getElementById('selected-count');
        
        if (selectedIds.length > 0) {
            actionsDiv.classList.remove('hidden');
            countSpan.textContent = selectedIds.length;
        } else {
            actionsDiv.classList.add('hidden');
        }

        // Update select all checkbox state
        const selectAllCheckbox = document.getElementById('select-all');
        const allCheckboxes = document.querySelectorAll('.row-checkbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0;
            selectAllCheckbox.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.row-checkbox, #select-all');
        checkboxes.forEach(cb => cb.checked = false);
        selectedIds = [];
        document.getElementById('batch-delete-actions').classList.add('hidden');
    }

    function confirmBatchDelete() {
        document.getElementById('modal-count').textContent = selectedIds.length;
        document.getElementById('batch-delete-modal').classList.remove('hidden');
    }

    function closeBatchDeleteModal() {
        document.getElementById('batch-delete-modal').classList.add('hidden');
    }

    function executeBatchDelete() {
        if (!batchDeleteRoute || selectedIds.length === 0) return;

        const btn = document.getElementById('confirm-delete-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';

        fetch(batchDeleteRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated data
                window.location.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Terjadi kesalahan saat menghapus data.', confirmButtonColor: '#1B4D3E' });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menghapus data.', confirmButtonColor: '#1B4D3E' });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus';
        });
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBatchDeleteModal();
        }
    });

    // Close modal on backdrop click
    document.getElementById('batch-delete-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBatchDeleteModal();
        }
    });
</script>
@endpush
