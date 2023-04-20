<?php

namespace App\Http\Controllers;

use App\Helpers\Log;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly string $_route = 'auth.',
        private readonly string $_routeView = 'auth.',
        private readonly string $_title = 'Auth',
    )
    {
        view()->share([
            'route' => $this->_route,
            'title' => $this->_title,
        ]);
    }

    /**
     * Display login page.
     *
     * @return Factory|View|Application
     */
    public function login(): Factory|View|Application
    {
        $data = [
            'route' => $this->_route . 'login.',
            'title' => 'Login'
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Authenticate user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function loginProcess(LoginRequest $request): JsonResponse
    {
        try {
            if (!auth()->attempt($request->validated())) {
                throw new BadRequestException('Email or password is wrong');
            }

            return response()->json([
                'success' => true,
                'message' => 'Login Success',
                'data' => [
                    'route' => route(RouteServiceProvider::HOME)
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Logout user.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            session()->flush();
            auth()->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logout Success',
                'data' => [
                    'route' => route('auth.login.show')
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }
}
