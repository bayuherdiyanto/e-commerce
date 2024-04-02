<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        // if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        $user = User::where("username",$this->email)->first();
        $password = $this->hash_password($this->password,$user);
        // dd($password);
        $user = User::where("username",$this->email)->where("password",$password)->first();
        
        if($user){
            if(!Auth::loginUsingId($user->id)) {
                RateLimiter::hit($this->throttleKey());
    
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
        } else {
            RateLimiter::hit($this->throttleKey());
    
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        

        RateLimiter::clear($this->throttleKey());
    }

    private function hash_password($password, $user)
    {
        $salt = substr($user->password, 0, 10);

        $db_password = $salt . substr(sha1($salt . $password), 0, -10);

        return $db_password;
    }

    public function salt()
    {
        return substr(md5(uniqid(rand(), true)), 0, 10);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
