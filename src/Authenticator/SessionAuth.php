<?php


namespace atefe93\TowFactorAuth\Authenticator;


use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    public function check()
    {
        return Auth::check();

    }

    public function loginById($uid)
    {
        \auth()->loginUsingId($uid);
    }
}
