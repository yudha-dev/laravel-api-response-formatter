<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $credential = request(['email', 'password']);

            //jika authentikasi gagal
            if (!auth()->attempt($credential)) {
                //memberikan respon unauthorized
                return ResponseFormatter::unauthorized([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 401);
            }

            //jika hash password tidak sesuai
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            //hapus token lama
            $user->tokens()->delete();
            //buat token baru
            $token = $user->createToken('authToken')->plainTextToken;
            //memberikan respon sukses
            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user

            ], 'Authenticated', 200);
        } catch (\Exception $e) {
            //memberikan respon failed
            return ResponseFormatter::failed([
                'message' => 'Something went wrong',
                'error' => $e
            ], 'Authentication Failed', 500);
        }
    }

    //register
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'alamat'    => $request->alamat,
                'no_hp'     => $request->no_hp
            ]);

            //example response split meta and data 
            return ResponseFormatter::success([
                'access_token'   => $user->createToken('authToken')->plainTextToken,
                'token_type'     => 'Bearer',
                'user'           => $user
            ], 'User Registered', 200);
        } catch (\Exception $e) {
            return ResponseFormatter::failed([
                'message' => 'Something went wrong',
                'error' => $e
            ], 'Pendaftaran Gagal', 401);
        }
    }
}
