{{-- Batch Restore Actions Component --}}
{{-- Include this in trashed views with: @include('components.batch-restore-actions', ['routeName' => route('admin.resource.batch-restore')]) --}}

<div id="batch-restore-bar" class="hidden fixed bottom-0 left-0 right-0 bg-green-600 text-white px-6 py-4 shadow-lg z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span id="restore-selected-count" class="text-lg font-semibold">0 item dipilih</span>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="cancelRestoreSelection()" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button onclick="confirmBatchRestore()" class="px-4 py-2 bg-white text-green-600 hover:bg-gray-100 rounded-lg font-semibold transition">
                <i class="fas fa-undo mr-2"></i>Restore Terpilih
            </button>
        </div>
    </div>
</div>

<!-- Batch Restore Confirmation Modal -->
<div id="batch-restore-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeBatchRestoreModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6 z-50">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-undo text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Konfirmasi Restore</h3>
                <p class="text-gray-600 mb-6">
                    Anda akan me-restore <span id="restore-modal-count" class="font-bold text-green-600">0</span> data yang dipilih.
                    <br><span class="text-sm text-gray-500">Data yang konflik dengan data aktif tidak akan di-restore.</span>
                </p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeBatchRestoreModal()" class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-semibold">
                    Batal
                </button>
                <button onclick="executeBatchRestore()" id="confirm-restore-btn" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-undo mr-2"></i>Restore
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let restoreSelectedIds = [];
    const restoreRouteUrl = '{{ $routeName }}';

    function updateRestoreSelectedIds() {
        restoreSelectedIds = [];
        document.querySelectorAll('.restore-row-checkbox:checked').forEach(checkbox => {
            restoreSelectedIds.push(parseInt(checkbox.value));
        });

        const bar = document.getElementById('batch-restore-bar');
        const countSpan = document.getElementById('restore-selected-count');

        if (restoreSelectedIds.length > 0) {
            bar.classList.remove('hidden');
            countSpan.textContent = restoreSelectedIds.length + ' item dipilih';
        } else {
            bar.classList.add('hidden');
        }
    }

    function toggleRestoreSelectAll(checkbox) {
        document.querySelectorAll('.restore-row-checkbox').forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateRestoreSelectedIds();
    }

    function cancelRestoreSelection() {
        document.querySelectorAll('.restore-row-checkbox').forEach(cb => {
            cb.checked = false;
        });
        const selectAll = document.getElementById('restore-select-all');
        if (selectAll) selectAll.checked = false;
        updateRestoreSelectedIds();
    }

    function confirmBatchRestore() {
        document.getElementById('restore-modal-count').textContent = restoreSelectedIds.length;
        document.getElementById('batch-restore-modal').classList.remove('hidden');
    }

    function closeBatchRestoreModal() {
        document.getElementById('batch-restore-modal').classList.add('hidden');
    }

    function executeBatchRestore() {
        const btn = document.getElementById('confirm-restore-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        fetch(restoreRouteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ids: restoreSelectedIds })
        })
        .then(response => response.json())
        .then(data => {
            closeBatchRestoreModal();
            if (data.success) {
                // Show success message and reload
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-undo mr-2"></i>Restore';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-undo mr-2"></i>Restore';
            closeBatchRestoreModal();
        });
    }
</script>
@endpush
