<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'confirm_password' => 'required|same:password'
    ]);
        if ($validator->fails()) {
        return response()->json([
        'success' => false,
        'message' => 'Ada kesalahan',
        'data' => $validator->errors()
        ]);
    }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =
        $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
        'success' => true,
        'message' => 'Sukses register',
        'data' => $success
        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ]);
        }
        $user = Auth::user();
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
        return response()->json([
            'success' => true,
            'message' => 'Sukses login',
            'data' => $success
        ]);
    }
}
