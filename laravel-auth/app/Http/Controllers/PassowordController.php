<?php

namespace App\Http\Controllers;

use App\Http\Requests\RessetRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PassowordController extends Controller
{
    public function forget(Request $request)
    {
        $email = $request->input('email');


        // Generate a unique token using Str::uuid()
        $token = Str::uuid()->toString();

        // Insert into the password_reset table
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token
        ]);

        // Send the password reset email
        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset your password');
            $message->to($email);
        });

        return response([
            'message' => 'Check your email for password reset instructions.'
        ]);
    }

    public function reset(RessetRequest $request){
        $passwordRest = DB::table('password_reset_tokens')
            ->where('token', $request->input('token'))->first();
        if(!$user = User::where('email', $passwordRest->email)->first()){
            throw new NotFoundHttpException('User not found ');

        }
        $user->password = Hash::make($request->input('password'));  
        $user->save();

        return response([
            'message' => 'Success.'
        ]);
    }
}
