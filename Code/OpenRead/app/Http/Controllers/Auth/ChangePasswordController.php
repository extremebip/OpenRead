<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Service\Contracts\IAuthService;
use App\Models\Requests\Auth\ChangePasswordPostRequest;

class ChangePasswordController extends Controller
{
    private $authService;

    public function __construct(IAuthService $authService) {
        $this->middleware('auth');
        $this->authService = $authService;
    }

    public function index()
    {
        return view('auth.passwords.change');
    }

    public function change(ChangePasswordPostRequest $request)
    {
        $user_id = Auth::id();
        $this->authService->ChangePassword($user_id, $request['password']);
        return redirect()->route('show-profile', ['u' => $user_id]);
    }
}
