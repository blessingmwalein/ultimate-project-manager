<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Company;
use App\Models\User;

class InviteController extends Controller
{
    public function accept(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $companyId = $request->query('company');
        $email = $request->query('email');

        $company = Company::find($companyId);
        if (! $company) abort(404);

        $user = User::where('email', $email)->first();
        if (! $user) abort(404);

        // attach if not already
        $company->users()->syncWithoutDetaching([$user->id => ['role' => 'viewer']]);

        // create one-time token for magic sign-in
        $token = $user->createToken('invite')->plainTextToken;

        // Redirect to frontend with token (frontend should consume token and store it securely)
        $frontend = rtrim(config('app.frontend_url') ?? env('FRONTEND_URL', ''), '/');
        $redirect = $frontend ? $frontend . '/invites/accepted?token=' . urlencode($token) . '&company=' . $company->id : url('/');

        return redirect($redirect);
    }
}
