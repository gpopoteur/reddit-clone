<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function userSignIn($user)
    {
        \Auth::loginUsingId($user->id);
    }
}
