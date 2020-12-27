<?php

namespace App\Http\Controllers\User;

use App\Models\Requests\User\EditProfilePostRequest;
use App\Http\Controllers\Controller;
use App\Service\Contracts\IUserProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $userProfileService;

    public function __construct(IUserProfileService $userProfileService) {
        $this->middleware('auth')->except(['index']);
        $this->userProfileService = $userProfileService;
    }

    public function index(Request $request)
    {
        $username = $request->query('u');
        if (is_null($username))
            abort(404);
        $result = $this->userProfileService->GetUser($username);
        if (is_null($result['user']))
            abort(404);

        $canEdit = false;
        if (Auth::check()){
            $canEdit = (strcmp($username, Auth::user()->username) == 0);
        }
        return view('user.profile', [
            'userData' => $result,
            'canEdit' => $canEdit
        ]);
    }

    public function edit()
    {
        $user = $this->userProfileService->GetUser(Auth::user()->username);
        return view('user.edit-profile', $user);
    }

    public function save(EditProfilePostRequest $request)
    {
        $data = $request->validatedIntoCollection();
        $newUser = $this->userProfileService->UpdateProfile($data);
        return redirect()->route('show-profile', ['u' => $data['username']]);
    }
}
