<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Service\Contracts\IAuthService;
use App\Models\Requests\Auth\LoginPostRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * What column to check user to
     * 
     * @var string
     */
    protected $checkTo = 'email';

    private $authService;

    /**
     * Error messages when login failed
     * 
     * @var array
     */
    private $messages = [
        'username' => 'Username does not exist',
        'email' => 'E-mail is not associated with any account',
        'password' => 'Invalid password'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Change column to check to based on given field
     * 
     * @param string $field
     * @return void
     */
    protected function checkWith($field)
    {
        if (filter_var($field, FILTER_VALIDATE_EMAIL))
        {
            $this->checkTo = 'email';
        }
        else 
        {
            $this->checkTo = 'username';
        }
    }

    /**
     * Wrap failed login attempt methods into one
     * 
     * @param \Illuminate\Http\Request  $request
     * @param bool $is_password
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedLogin(Request $request, $is_password)
    {
        $this->incrementLoginAttempts($request);
        $type = $is_password ? 'password' : $this->checkTo;
        return $this->sendFailedLoginResponse($request, $type);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Model\Requests\Auth\UserLoginPostRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginPostRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $data = $request->validatedIntoCollection();
        $this->checkWith($data['username']);

        $user = $this->authService->GetUserByEmailOrUsername($data['username']);
        if (is_null($user)){
            return $this->failedLogin($request, false);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->failedLogin($request, true);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $user = $request->validatedIntoCollection();
        return [
            $this->checkTo => $user['username'],
            'password' => $user['password']
        ];
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request, $type)
    {
        $message = [];

        if ($type == 'password'){
            $message = ['password' => [$this->messages[$type]]];
        }
        else {
            $message = ['username' => [$this->messages[$type]]];
        }

        throw ValidationException::withMessages($message);
    }
}
