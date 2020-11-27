<?php

namespace App\Providers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

		$this->app['auth']->viaRequest('api', function (Request $request) {
			if (!$request->hasHeader('Authorization')) return null;
			$header = $request->header('Authorization');
			$token = str_replace("Bearer ", "", $header);
			$data = JWT::decode($token, env('JWT_KEY'), ["HS256"]);
			error_log($data->email);
			return new User(["email" => $data->email]);
		});
    }
}
