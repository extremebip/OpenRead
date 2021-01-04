<?php

namespace App\Http\Controllers\Auth;

use App\Models\DB\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Service\Contracts\IAuthService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Requests\Auth\SignUpPostRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    private $authService;

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Models\Requests\Auth\SignUpPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(SignUpPostRequest $request)
    // public function register(Request $request)
    {
        event(new Registered($user = $this->create($request->validatedIntoCollection())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Collection $data)
    {
        return $this->authService->RegisterUser($data);
    }
}
