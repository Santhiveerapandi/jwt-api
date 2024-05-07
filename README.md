<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel
### Ref: https://onlinewebtutorblog.com/laravel-11-restful-apis-with-jwt-authentication/

> composer create-project laravel/laravel jwt-api
> cd jwt-api
> php artisan make:controller Api/ApiController

### config prefix of the database tables
.env
DB_PREFIX=jwtapi_
AUTH_GUARD=api
APP_TIMEZONE=Asia/Kolkata
LOG_CHANNEL=daily

config/database.php
'prefix' => env('DB_PREFIX', 'jwtapi_'),

> php artisan install:api

> composer require php-open-source-saver/jwt-auth

> php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"

> php artisan jwt:secret

### config/auth.php
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
### app/Models/User.php
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
...
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

### app/Http/Controllers/Api/ApiController.php

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class ApiController extends Controller
{
    //Register Method - POST (name, email, password)
    public function register(Request $request)
    {
        // Validation
        /* $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed" // password_confirmation
        ]); */
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // User model to save user in database
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user
        ]);
    }

    // Login API - POST (email, password)
    public function login(Request $request){

        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!$token){

            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in succcessfully",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);

    }

    // Profile API - GET (JWT Auth Token)
    public function profile(){

        //$userData = auth()->user();
        $userData = request()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userData,
            //"user_id" => request()->user()->id,
            //"email" => request()->user()->email
        ]);
    }

    // Refresh Token API - GET (JWT Auth Token)
    public function refreshToken(){

        $token = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $token,
            //"expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    // Logout API - GET (JWT Auth Token)
    public function logout(){

        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}

----------------------------------
## Coding Standard Checking 
1. Larastan Ref: https://github.com/larastan/larastan?tab=readme-ov-file
> composer require larastan/larastan --dev
Then, create a phpstan.neon or phpstan.neon.dist file in the root of your application.
includes:
    - vendor/larastan/larastan/extension.neon

parameters:

    paths:
        - app/

    # Level 9 is the highest level
    level: 5

#    ignoreErrors:
#        - '#PHPDoc tag @var#'
#
#    excludePaths:
#        - ./*/*/FileToBeExcluded.php
#
#    checkMissingIterableValueType: false

> ./vendor/bin/phpstan analyse

2. Pint Ref: https://laravel.com/docs/11.x/pint
> composer require laravel/pint --dev

>./vendor/bin/pint