<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Facades\Socialite;

class NewUserFacebookLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewUserFacebookLogin()
    {
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('redirect')->andReturn('Redirected');
        $providerName = class_basename($provider);
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->id = rand();
        $abstractUser->name = str_random(15);
        $abstractUser->email = str_random(10).'@noemail.app';
        $abstractUser->avatar = 'https://en.gravatar.com/userimage';
        $abstractUser->avatar_original = 'https://en.gravatar.com/userimage';
        $abstractUser->token = str_random(50);

        $userOtherDetails = array();
        $userOtherDetails['name'] = str_random(15);
        $userOtherDetails['gender'] = str_random(4);

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);
        Socialite::shouldReceive('driver')->with('facebook')->andReturn($provider);
        $abstractUser->shouldReceive('getRaw')->andReturn($userOtherDetails);
        $_COOKIE['timeZoneOffset'] = rand(0,9)/2;
        // After Oauth redirect back to the route
        // See the page that the user login into
        $this->visit('/facebook_login/connect')->seePageIs('/home');
        $this->assertTrue(true);
    }
}
