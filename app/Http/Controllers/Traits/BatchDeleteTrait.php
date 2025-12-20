<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait BatchDeleteTrait
{
    /**
     * Batch delete (soft delete) multiple records
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $modelClass = $this->getModelClass();
        $count = $modelClass::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} data berhasil dihapus.",
            'count' => $count,
        ]);
    }

    /**
     * Get the model class for batch operations
     * Override this method in the controller
     */
    protected function getModelClass(): string
    {
        throw new \Exception('getModelClass() must be implemented in the controller');
    }
}
