<?php

namespace App\Http\Controllers;

use App\Models\Forgot;
use App\Models\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $activation_code = $this->randChars();

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $register = Register::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'kode_aktivasi' => $activation_code
        ]);

        //Kirim email kode Aktivasi
        $details = [
            'title' => "Activation code for Anoda Guest Book",
            'body' => "This is your activation code, use it to activate your registration.",
            'code' => $activation_code
        ];

        Mail::to($fields['email'])->send(new \App\Mail\MailActivation($details));

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

            $token = $user->createToken('presensionprestoken')->plainTextToken;

            $response = [
                'user' => $user,
                'jwt' => $token
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

        $activation_code = $this->randChars();

        // return $user->count();

        if($user->count() > 0){

            $forgot = Forgot::create([
                'email' => $request->email,
                'kode_aktivasi' => $activation_code
            ]);

            //Kirim email kode aktivasi
            $details = [
                'title' => "Password Reset Code for Anoda Guest Book",
                'body' => "This is your activation code, use it to reset your password.",
                'code' => $activation_code
            ];

            Mail::to($request->email)->send(new \App\Mail\MailActivation($details));

            return response($forgot, 201);

        }else{

            return response(['message' => "Your email is not registered yet"], 401);

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

    public function logingoogle(Request $request)
    {
        $userx = User::where('email', $request->email);
        if($userx->exists()){
            $res = Auth::loginUsingId($userx->get()->first()->id);
            $token = $userx->get()->first()->createToken('presensionprestoken')->plainTextToken;
            $response = [
                'user' => $res,
                'jwt' => $token
            ];
            return response($response, 201);
        }
        return response(['message' => $request->email], 401);
    }

    public function cekuser($email)
    {
        $userExists = User::where('email', "=", $email)->exists();
        if($userExists) {
            return response(['exists' => true], 201);
        }
        return response(['exists' => false], 201);
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
