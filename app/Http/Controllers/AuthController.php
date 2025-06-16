<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate(
            [
                'name'=> 'required|string|max:255',
                'email'=> 'required|string|email|unique:users',
                'password'=> 'required|string|min:6'
            ]
        );

        if($request->hasFile('avatar')){
            $image = $request->file('avatar');
           $path =  Storage::disk('public')->put('users',$image);
           $request->avatar = $path;

        }
        User::create(
            [
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'avatar'=> $request->avatar

            ]
        );

        return response()->json([
            'message'=> 'user created successful',
            'user'=> [
                'name'=> $request->name,
                'email'=> $request->email,
                'avatar'=> $request->avatar ? asset('storage/'.$request->avatar) : null
            ]
        ],200);

    }

    public function login(Request $request){
        $request->validate(
            [

                'email'=> 'required|string|email',
                'password'=> 'required|string'
            ]
        );

        $user  = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(
                [
                    'message'=> 'invalid credential'
                ]
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->avatar = $user->avatar ? asset('storage/'.$user->avatar):null;

        return response()->json(
            [
                'token'=> $token,
                'user'=> $user
            ]
        );


    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message'=> "logout successfully"
        ]);
    }

    public function update(Request $request){
        $user = $request->user(); // get the authenticated user
        if($request->hasFile('avatar')){
            $image = $request->file('avatar');
            $path =  Storage::disk('public')->put('users',$image);

            if(Storage::disk('public')->exists($user->avatar)){
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;


        }

        if($request->password){
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;

        $user->save();

        return response()->json([
            'message'=> 'user updated successfully',
            'user'=> $user
        ]);

    }

    // get user profile
    public function profile(Request $request){
        $user = $request->user();
        $user->avatar = $user->avatar ? asset('storage/'.$user->avatar) : null;

        return response()->json([
            'message'=> 'user profile retrieved successfully',
            'user'=> $user
        ]);
    }
    public function delete(Request $request){
        $user = $request->user();
        if(Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message'=> 'user deleted successfully'
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Here you would typically send a password reset link to the user's email.
        // For simplicity, we'll just return a success message.

        return response()->json(['message' => 'Password reset link sent to your email'], 200);
    }
}
