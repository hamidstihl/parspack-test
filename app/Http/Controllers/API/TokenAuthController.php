<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\PreRegister;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TokenAuthController extends Controller
{
    protected $username='username';
    protected $token_name='LaravelAuthApp';

    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'دادهای ورودی نامعتبر است'
            ];
            return response($response,400);
        }
        $credentials = $request->only([$this->username,"password"]);
        if (!Auth::attempt($credentials)) {
            $response = [
                'success' => false,
                'data' => '',
                'message' => 'نام کاربری یا رمزعبور اشتباه است'
            ];
            return response($response, 422);
        } else {
            //auth()->user()->tokens()->delete();
            $token = auth()->user()->createToken($this->token_name)->plainTextToken;
            $response = [
                'success' => true,
                'data' => $token,
                'message' => 'شما با موفقیت وارد شدید'
            ];
            return response($response);
        }
    }

    public function Logout(){
        if(Auth::user())
            Auth::user()->tokens()->delete();
        $response = [
            'success' => true,
            'data' => '',
            'message' => 'شما از سیستم خارج شدید'
        ];
        return response($response);
    }

    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:4', 'regex:/(^[a-zA-Z_\d]+$)/u', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {              //  اگر صحیح نبود
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'داده های ورودی نامعتبر است.'
            ];
            return response($response, 400);
        }
        $username=User::query()->where('username',$request['username'])->exists();
        if($username) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'نام کاربری قبلا استفاده شده است.',
            ];
            return response($response,400);
        }
        $user = User::query()->create([
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
        ]);
        $token = $user->createToken($this->token_name)->plainTextToken;
        $response=[
            'success' => true,
            'data' => $token,
            'message' => 'ثبت نام انجام شد',
        ];
        return response($response);
    }
}
