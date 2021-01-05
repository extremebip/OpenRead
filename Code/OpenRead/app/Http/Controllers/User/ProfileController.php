<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Service\Contracts\IUserProfileService;
use App\Models\Requests\User\EditProfilePostRequest;
use App\Models\Requests\User\UploadImagePostRequest;

class ProfileController extends Controller
{
    private $userProfileService;

    public function __construct(IUserProfileService $userProfileService) {
        $this->middleware('auth')->except(['index']);
        $this->middleware('throttle:5,1')->only('upload');
        $this->userProfileService = $userProfileService;
    }

    public function index(Request $request)
    {
        $username = $request->query('u');
        if (is_null($username))
            abort(404);
        $result = $this->userProfileService->GetUser($username);
        // if (is_null($result['user']))
        if (is_null($result))
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
        return view('user.edit-profile', ['user' => $user]);
    }

    public function upload(UploadImagePostRequest $request)
    {
        $data = $request->validatedIntoCollection();
        $result = $this->userProfileService->StoreImage($data);
        return response()->json($result);
    }

    public function preview(Request $request, $name = null)
    {
        try {
            $checkDirectory = 'profilePic/';
            $temp = $request->query('temp');
            if ($temp === 'true')
                $checkDirectory = 'temp/';

            if (is_null($name))
                throw new \Exception();

            $result = $this->userProfileService->CheckImageExist($name, $checkDirectory);
            if (!$result['found'])
                throw new \Exception();

            return response()->file($result['path'], ['Content-Type' => 'image/'.$result['ext']]);
        } catch (\Exception $e) {
            return response()->file(public_path('assets/default.png'), ['Content-Type' => 'image/png']);
        }
    }

    public function save(EditProfilePostRequest $request)
    {
        $data = $request->validatedIntoCollection();
        $newUser = $this->userProfileService->UpdateProfile($data);
        return redirect()->route('show-profile', ['u' => $data['username']]);
    }
}
