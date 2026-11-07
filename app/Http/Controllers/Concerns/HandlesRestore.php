<?php

namespace App\Http\Controllers\Concerns;

use App\Contracts\Restorable;
use Illuminate\Http\JsonResponse;

trait HandlesRestore
{
    public static function restore(Restorable $model): JsonResponse
    {
        if (! $model->restoreRecord()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dalam status dihapus.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dipulihkan.',
        ]);
    }
}
