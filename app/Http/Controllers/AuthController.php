<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
#use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'max:255'
                ],
                'password' => [
                    'required',
                    'string',
                    'max:255'
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->route('login')->withErrors($validator);
            }

            $user = DB::table('users')->where('email', '=', $request->email)->get(['id', 'password'])->first();

            if (!$user){
                return response()->json(['error' => 'Invalid login']);
            }

            $pass_check = Hash::check($request->password, $user->password);
            if (!$pass_check){
                return response()->json(['error' => 'Invalid login']);
            }

            Auth::loginUsingId($user->id);
            return response()->json(['location' => route('main')]);

        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register(Request $request)
    {
         if ($request->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users'),
                ],
                'password' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'name' => [
                    'required',
                    'string',
                    'max:250'
                ],

                //'captcha' => ['required', 'captcha']
            ]);

            if ($validator->fails()) {
                return response()->json(['valid_error' => $validator->errors()]);
            }

            $request['password'] = Hash::make($request['password']);

            $from = env('RESET_EMAIL_FROM');
            $message = "Hello. You have registered on ".env('APP_HOST');

            if ((new \App\Services\MailService())->send($request->email, "Regisration on ".env('APP_HOST'), $message, $from)) {

                $user = new User($request->all());
                $user->save();
                Auth::login($user,true);
            }

            return response()->json(['location' => route('main')]);
        }
    }
}
