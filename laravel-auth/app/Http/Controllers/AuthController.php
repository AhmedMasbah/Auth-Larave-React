<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'), // Fix here
            'password' => Hash::make($request->input('password')),
        ]);
        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        // Create a token and set it as a cookie
        $token = $user->createToken('token')->plainTextToken;

        // Set the token as a cookie
        $cookie = cookie('jwt', $token, 60 * 24); // This example sets the cookie to expire in 24 hours

        // Return a response with the token and set the cookie
        return response([
            'jwt' => $token,
            'user' => $user,
        ])->withCookie($cookie);
    }

    public function user()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Retrieve the authenticated user
            $user = Auth::user();

            // You can customize the response structure according to your needs
            return response([
                'user' => $user,
            ]);
        }

        // If the user is not authenticated, return an unauthorized response
        return response(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(){

        // Clear authentication-related cookies
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Logout successful',
        ])->withCookie($cookie);
    }

    
}
