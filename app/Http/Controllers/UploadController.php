<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upload\StoreReceiptRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        // require auth for uploads
        $this->middleware('auth:sanctum');
        // Use class-based middleware reference to avoid alias lookup issues
        $this->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':uploads')->only('storeReceipt');
    }

    public function storeReceipt(StoreReceiptRequest $request, int $companyId, int $projectId)
    {
        if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
            abort(403);
        }

        /** @var UploadedFile $file */
        $file = $request->file('file');
        $path = "tenants/{$companyId}/projects/{$projectId}/receipts/" . uniqid() . '_' . $file->getClientOriginalName();
        $disk = config('filesystems.default');
        \Illuminate\Support\Facades\Storage::disk($disk)->putFileAs(dirname($path), $file, basename($path));
        return response()->json(['path' => $path], 201);
    }

    public function signedUploadUrl(int $companyId, int $projectId)
    {
        $disk = config('filesystems.default');
        $filename = 'receipts/' . uniqid() . '.bin';
        $path = "tenants/{$companyId}/projects/{$projectId}/" . $filename;
        $url = Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(15));
        return response()->json(['url' => $url, 'path' => $path]);
    }
}
