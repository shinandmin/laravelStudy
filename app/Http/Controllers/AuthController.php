<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }

    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function signup_post(Request $request)
    {
        $data = new User;
        $data->userid = $request->userid;
        $data->password = bcrypt($request->password);
        $data->name = $request->name;
        $data->save();

        return redirect('/');
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            echo "<script>alert('정상적이지 않은 입력값'); location.replace('/auth/signin');</script>";
        }

        $params = $request->only(['userid', 'password']);

        if (Auth::attempt($params)) {
            $user = User::where('userid', $params['userid'])->first();

            return redirect('/');
        } else {
            echo "<script>alert('존재하지 않거나, 올바르지 않은 계정정보입니다.'); location.replace('/auth/signin');</script>";
        }
    }

    public function signout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }
}
