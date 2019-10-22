<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExistUserGoogleLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistUserLogin()
    {
    	$provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('redirect')->andReturn('Redirected');
        $providerName = class_basename($provider);
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->id = 'XXXXXXXX'; //pass user id already exist
        $abstractUser->name = str_random(15);
        $abstractUser->email = str_random(50);
        $abstractUser->avatar = 'https://en.gravatar.com/userimage';
        $abstractUser->avatar_original = 'https://en.gravatar.com/userimage';
        $abstractUser->token = str_random(50);
        $abstractUser->refreshToken = str_random(25);
        $abstractUser->expires_in = 3600;

        $userOtherDetails = array();
        $userOtherDetails['name']['givenName'] = str_random(15);
        $userOtherDetails['name']['familyName'] = str_random(15);
        $userOtherDetails['gender'] = str_random(4);

        $provider->shouldReceive('user')->andReturn($abstractUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
        $abstractUser->shouldReceive('getRaw')->andReturn($userOtherDetails);
        $_COOKIE['timeZoneOffset'] = rand(0,9)/2;
        $this->visit('/google_login/connect')->seePageIs('/home');
        $this->assertTrue(true);
    }
}
