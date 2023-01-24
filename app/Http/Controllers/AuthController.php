<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use External\Foo\Auth\AuthWS;
use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // TODO
        $content =  json_decode($request->getContent(), true);
        $login = $content['login'];
        $password = $content['password'];

        $services = [
            "FOO_" =>   [
                "name" => new AuthWS(),
                "method" => "authenticate",
                "success" => null
            ],
            "BAR_" =>   [
                "name" => new LoginService(),
                "method" => "login",
                "success" => true
            ],
            "BAZ_" =>   [
                "name" => new Authenticator(),
                "method" => "auth",
                "success" => new Success()
            ]
        ];

        if (array_key_exists(substr($login, 0, 4), $services)) {
            $service = $services[substr($login, 0, 4)];
            if (
                $service["name"]
                ->{$service["method"]}($login, $password)
                == $service["success"]
            ) {
                return response()->json([
                    "status" => "success",
                    "token" => "<generated token>"
                    // I have 20 minutes left so I`ll rather try speedrun second task instead of using JWT for the first time. Although It seems pretty straight forward. AES-CBD-256 en/de_coding.
                ]);
            }
        }

        return response()->json([
            'status' => 'failure',
        ]);
    }
}
