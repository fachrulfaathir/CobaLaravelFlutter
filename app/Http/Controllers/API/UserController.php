<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'phone'=> 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'string', Password::min(8)
                                                        ->mixedCase()
                                                        ->numbers()
                                                        ->symbols()
                                                        ->uncompromised()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $tokenResult = $user->createToken('auth_token')->plainTextToken;

            return ResponseFormatter::success([
                'user' => $user,
                'token' => $tokenResult
            ], 'User berhasil register');

        } catch (\Exception $e) {

            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'User gagal register', 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $credentials = request(['email', 'password']);

            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::query()->where('email', $request->email)->first();
            
            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }
            $tokenResult = $user->createToken('auth_token')->plainTextToken;

            return ResponseFormatter::success([
                'user' => $user,
                'token' => $tokenResult
            ], 'User berhasil login');

        }catch (\Exception $e) {

            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'User gagal login', 500);

        }
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Data user berhasil diambil');
    }
}
