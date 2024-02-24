<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use Exception;
use Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const FILE_PATH = __FILE__.'/'.__FUNCTION__;
    const SUCCESS_LOG_DETAIL = 'Success in '.self::FILE_PATH;
    const ERROR_LOG_DETAIL = 'Error in '.self::FILE_PATH;

    public function register(RegistrationRequest $request)
    {
        try {

            $response = User::checkEmailExist($request->email);
            if(!$response) {
                return response()->json(['message' => 'Email is already registered.', 'request'=> $request->only(['name', 'email'])], 400);
            }

            $userRow = User::createUser($request->all());
            if(!empty($userRow)){
                $accountDetail = Account::createAccount($userRow->id);

                $token = Auth::login($userRow);

                Log::info("Registration successful!", 
                    [
                        'message' => self::SUCCESS_LOG_DETAIL, 'response'=> $userRow
                    ]);
                return response()->json(['status' => 'success', 
                    'message' => 'Registration successful!', 
                    'user'=> $userRow, 
                    'account' => $accountDetail,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                        ]
                    ], 201);
            }
            else {
                Log::error("Registration Unsuccessful!",
                 ['message' => self::ERROR_LOG_DETAIL, 
                    '_error_'=> $exception->getMessage(),
                    '_trace_' => $exception->getTraceAsString()
                ]);
                return response()->json(['message' => 'Registration Unsuccessful!'], 400);
                }
            }
        catch(Exception $exception) {
            Log::error("Registration Exception!",
             ['message' => self::ERROR_LOG_DETAIL, 
                '_error_'=> $exception->getMessage(),
                '_trace_' => $exception->getTraceAsString()
            ]);
            return response()->json(['message' => 'Registration Unsuccessful!'], 400);

        }
        
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

         if (Auth::attempt($credentials)) {
            $token = JWTAuth::fromUser(Auth::user());
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized', 'credentials' => $credentials], 401);

        }
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    } 


    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}