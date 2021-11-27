<?php

namespace App\Http\Controllers;

use App\Models\Forgot;
use App\Models\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $register = Register::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'kode_aktivasi' => $this->randChars()
        ]);

        //Kirim email kode Aktivasi

        $response = ['user' => $register];

        return response($response, 201);
    }

    public function activate(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required',
            'kode_aktivasi' => 'required'
        ]);

        $register = Register::where('email', $fields['email'])->where('kode_aktivasi', $fields['kode_aktivasi']);

        if($register->count() > 0){

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'email_verified_at' => Carbon::now()
            ]);

            Register::where('email', $fields['email'])->delete();

            // $token = $user->createToken('presensionprestoken')->plainTextToken;

            $response = [
                'user' => $user,
                // 'jwt' => $token
            ];

            return response($response, 201);

        }else{

            return response(['message' => "Kode aktivasi tidak ditemukan"], 401);

        }
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email);

        if($user->count() > 0){

            $forgot = Forgot::create([
                'email' => $request->email,
                'kode_aktivasi' => $this->randChars()
            ]);

            //Kirim email kode aktivasi
            return response($forgot, 201);

        }else{

            return response(['message' => "Email anda tidak terdaftar"], 401);

        }

    }

    public function activeforgot(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'kode_aktivasi' => 'required'
        ]);

        $forgot = Forgot::where('email', $request->email)->where('kode_aktivasi', $request->kode_aktivasi);

        if($forgot->count() > 0) {
            $retforgot = $forgot->get()->first();
            $forgot->delete();
            return response(['user' => $retforgot], 201);
        }

        return response(['message' => "Kode aktivasi yang anda masukkan tidak ada."], 401);
    }

    public function doreset(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email);
        $user->update(['password' => bcrypt($request->password)]);
        return response(['user' => $user->get()->first()], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' =>  'User atau password tidak ditemukan'
            ], 401);
        }

        $token = $user->createToken('presensionprestoken')->plainTextToken;

        $response = [
            'user' => $user,
            'jwt' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ['message' => "logged out"];
    }

    public function randChars()
    {
        $chars = '0123456789';
        return substr(str_shuffle($chars), 0, 5);
    }
}
