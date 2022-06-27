<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                ]
            );

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'point' => 0,
                'role' => 'User'
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'status_code' =>400,
            //     'message' => 'Success',
            //     'error' => $user
            // ]);
            return $th;
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (password_verify($request->password, $user->password)) {
                    $tokenResult = $user->createToken('authToken')->plainTextToken;
                    return response()->json([
                        'status_code' => 200,
                        'access_token' => 'Bearer ' . $tokenResult,
                        'data' => $user
                    ]);
                }else{
                    return response()->json([
                        'status_code' => 401,
                        'message' => 'Password Salah',

                    ]);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Username tidak terdaftar',

                ]);
            }
            
        } catch (\Throwable $th) {
            return $th;
            //throw $th;
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $data = User::where('email', $request->email)->first();
            if ($data) {
                return response()->json(
                    [
                        'data' => 'Email terdaftar',
                        'id' => $data->id
                    ],
                    200
                );
            } else {
                return response()->json(['error' => 'Email tidak terdaftar'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['password' => bcrypt($request->password)]);
            return response()->json(['data' => 'Berhasil memperbarui password'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }

    public function updateProfil(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required',
                ]
            );

            $input['username'] = $request->username;
            $input['email'] = $request->email;

            if ($request->password) {
                $input['password'] = bcrypt($request->password);
            }

            User::where('id', Auth::user()->id)->update($input);
            $data = User::where('id', Auth::user()->id)->first();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $data,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }
}
