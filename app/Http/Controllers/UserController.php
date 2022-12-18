<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailResets;
use Carbon\Carbon;
use App\Custom\Security;
use App\Custom\Mailer;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    public function login(Request $request){

         /**find user in the database */
        $username = Security::kenProtectFunc($request->post('email'));
        $password = Security::kenProtectFunc($request->post('password'));
        /**find the email in the user table */
        $users = User::where('email', $username)->first();

        /**check if user exist */
        if(empty($users)){
            return response()->json([
                'status' => false,
                'message' => 'You are not a registered user, please register',
            ], 200);
        }

        /**check if the user credentials is correct */
        if($username  != $users->email){
            return response()->json([
                'status' => false,
                'message' => 'wrong username or password',
            ], 200);
        }
         /**check if the user credentials is correct */
        if(Security::kenhashword($password, Security::passwordSalt())  != $users->password){
            return response()->json([
                'status' => false,
                'message' => 'wrong username or password',
            ], 200);
        }

        /**check if the user has verified his/her email*/
        if($users->email_verified_at == null){
            return response()->json([
                'status' => false,
                'message' => 'Please check your email inbox or spam folder, use the verification code sent to you to verify and activate your account',
            ], 202);
        }

        /* this is a mock data in place of a real database data*/
        return [
             "status" => true,
             "message" => "Login successful",
             "data" => [
                "id" => $users->id,
                "username" => $users->name,
                "email" => $users->email,
                "password" => $users->password,
                "account_ype" => $users->account_type == 0 ? "user" : "merchant",
                "email_verified_at" => $users->email_verified_at,
                "created_at" => $users->created_at,
            ],
        ];
    }

    public function register(Request $request){
        /**user posted credentials and sanitize it */
        $username = Security::kenProtectFunc($request->post('username'));
        $email = Security::kenProtectFunc($request->post('email'));
        $password = Security::kenProtectFunc($request->post('password'));
        $smsCode = Security::kenProtectFunc($request->post('smsCode'));
        $accountType = Security::kenProtectFunc($request->post('accountType'));
        /**check if the email is a valid email */
        if(!Security::emailRegularExpression($email)){
            return response()->json([
                'status' => false,
                'message' => 'Email pattern not valid',
            ], 200);
        }
        /**check if the password is a valid type accepted by lox corporate services*/
        if(!Security::passwordRegularExpression($password)){
            return response()->json([
                'status' => false,
                'message' => 'Password must be at least 6, and should contain only alphanumeric characters',
            ], 200);
        }
        /**find user in the database */
        $users = User::where('email', $email)->first();
        /**check if the user exist already */
        if(!empty($users)){
            return response()->json([
                'status' => false,
                'message' => 'User email already exists',
            ], 200);
        }
        /**create the user row if the user doesn't exist */
        $userModel = User::create([
            'name' => $username,
            'email' => $email,
            'password' => Security::kenhashword($password, Security::passwordSalt()),
            'account_type' => $accountType,
        ]);
        /**get otp if in table */
        $otp = EmailResets::where('email',$userModel->email)->first();
        /**check if there is an otp code and update it */
        if(!empty($otp) && $otp->otp_code != $smsCode){
            $otp->update([
                'otp_code'=> $smsCode,
                'updated_at' => date('Y-m-d H:i:s',time()),
                'type' => 'reset',
            ]);
        }

        if(empty($otp)){
        /**create the user otp row if the row doesn't exist */
        EmailResets::create([
            'user_id' => $userModel->id,
            'email' => $userModel->email,
            'otp_code' => $smsCode,
            'type' => 'reset',
          ]);
        }
        /*send email you user */
        $response = Mailer::emailMethod(
            receiverEmail: $userModel->email,
            senderEmail: ENV('MAIL_FROM_ADDRESS'),
            companyName: ENV('COMPANY_NAME'),
            title: "Lox Corporate Services Account Create",
            messageData: $smsCode,
        );
        /**check if the email was sent successfully */
       if($response){
        /**return the user data back as response */
        return response()->json([
            'status' => true,
            'message' => 'Account created successfully',
            "data" => [
                "id" => $userModel->id,
                "email" => $userModel->email,
                "account_ype" => $accountType == 0 ? "user" : "merchant",
                "created_at" => $userModel->created_at,
            ],
        ], 200);
       }
       /**return the user data back as response */
       return response()->json([
        'status' => true,
        'message' => 'Account created successfully, with error sending email',
        "data" => [
            "id" => $userModel->id,
            "email" => $userModel->email,
            "account_ype" => $accountType,
            "created_at" => $userModel->created_at,
        ],
    ], 200);
    }
    /**send a password reset email to the user which contain the reset code */
    public function forgotPassword(Request $request){
        /**find user in the database */
        $username = Security::kenProtectFunc($request->post('email'));
        $smsCode = Security::kenProtectFunc($request->post('smsCode'));
        /**find the email in the user table */
        $users = User::where('email', $username)->first();
        /**check if the email is a valid email */
        if(!Security::emailRegularExpression($username)){
            return response()->json([
                'status' => false,
                'message' => 'Email pattern not valid',
            ], 200);
        }
        /**check if user exist */
       if(empty($users)){
           return response()->json([
               'status' => false,
               'message' => 'You are not a registered user, please register',
           ], 200);
       }
       /**check if the sms code is empty */
       if(empty($smsCode)){
            return response()->json([
                'status' => false,
                'message' => 'OTP code empty',
            ], 200);
        }
        /**get otp if in table */
        $otp = EmailResets::where('user_id', $users->id)
        ->orWhere('email',$users->email)
        ->first();
        /**check if there is an otp code and update it */
        if(!empty($otp) && $otp->otp_code != $smsCode){
            $otp->update([
                'otp_code'=> $smsCode,
                'updated_at' => date('Y-m-d H:i:s',time()),
                'type' => 'reset',
            ]);
        }

        if(empty($otp)){
        /**create the user otp row if the row doesn't exist */
        $userModel = EmailResets::create([
            'user_id' => $users->id,
            'email' => $users->email,
            'otp_code' => $smsCode,
            'type' => 'reset',
          ]);
        }
        /*send email you user */
        $response = Mailer::emailMethod(
            receiverEmail: $users->email,
            senderEmail: ENV('MAIL_FROM_ADDRESS'),
            companyName: ENV('COMPANY_NAME'),
            title: "Password Reset Request",
            messageData: $smsCode,
        );
        /**check if the email went through */
        if($response){
            return response()->json([
                'status' => true,
                'message' => 'Email Sent Successfully, Please check your inbox or spam folder ',
            ], 200);
        }
        /**return the error back to the user */
        return response()->json([
            'status' => true,
            'message' => 'Email Sending did not go through, Please try again',
        ], 200);
   }
    /**finally change the password */
    public function resetPassword(Request $request){
        /**find user in the database */
        $username = Security::kenProtectFunc($request->post('email'));
        $smsCode = Security::kenProtectFunc($request->post('smsCode'));
        $password = Security::kenProtectFunc($request->post('password'));
        /**find the email in the user table */
        $users = User::where('email', $username)->first();
        /**check if the email is a valid email */
        if(!Security::emailRegularExpression($username)){
            return response()->json([
                'status' => false,
                'message' => 'Email pattern not valid',
            ], 200);
        }
        /**check if the password is a valid type accepted by lox corporate services*/
        if(!Security::passwordRegularExpression($password)){
            return response()->json([
                'status' => false,
                'message' => 'Password must be at least 6, and should contain only alphanumeric characters',
            ], 200);
        }
        /**check if user exist */
       if(empty($users)){
           return response()->json([
               'status' => false,
               'message' => 'You are not a registered user, please register',
           ], 200);
       }
       /**check if the sms code is empty */
       if(empty($smsCode)){
            return response()->json([
                'status' => false,
                'message' => 'OTP code empty',
            ], 200);
        }
        /**get otp if in table */
        $otp = EmailResets::where('user_id', $users->id)
        ->orWhere('email',$users->email)
        ->first();
        /**check if there is an otp code*/
        if(empty($otp)){
            return response()->json([
                'status' => false,
                'message' => 'No otp code matches your entry',
            ], 200);
        }
        /**check if the user entered a correct otp */
        if($otp->otp_code != $smsCode){
            return response()->json([
                'status' => false,
                'message' => 'Wrong code entered, please check your email inbox or spam folder and use the code sent to you',
            ], 200);
        }
        /*delete the email token */
        $deleted = EmailResets::where('user_id', $users->id)
        ->orWhere('email',$users->email)
        ->delete();
        /**update the user password if otp is correct */
        $users->update([
            'password' => Security::kenhashword($password, Security::passwordSalt()),
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);
        /*send email you user */
        $response = Mailer::emailMethod(
            receiverEmail: $users->email,
            senderEmail: ENV('MAIL_FROM_ADDRESS'),
            companyName: ENV('COMPANY_NAME'),
            title: "Password changed successfully",
            messageData: "Your password has been changed, you may now login with your new password",
        );
        /**check if the email went through */
        if($response){
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
            ], 200);
        }
        /**return the error back to the user */
        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully, Error sending email',
        ], 200);
    }
    /**verify and activate user account */
    public function verifyAccount(Request $request){
        /**find user in the database */
        $username = Security::kenProtectFunc($request->post('email'));
        $smsCode = Security::kenProtectFunc($request->post('smsCode'));
        $password = Security::kenProtectFunc($request->post('password'));
        /**find the email in the user table */
        $users = User::where('email', $username)->first();
        /**check if the account is already actived */
        if(!empty($users) && $users->email_verified_at != null){
            /**success response message */
            return response()->json([
                'status' => false,
                'message' => 'Account activated already',
            ], 202);
        }
        /**check if the email is a valid email */
        if(!Security::emailRegularExpression($username)){
            return response()->json([
                'status' => false,
                'message' => 'Email pattern not valid',
            ], 200);
        }
        /**check if the password is a valid type accepted by lox corporate services*/
        if(!Security::passwordRegularExpression($password)){
            return response()->json([
                'status' => false,
                'message' => 'Password must be at least 6, and should contain only alphanumeric characters',
            ], 200);
        }
        /**check if user exist */
       if(empty($users)){
           return response()->json([
               'status' => false,
               'message' => 'You are not a registered user, please register',
           ], 200);
       }
       /**check if the sms code is empty */
       if(empty($smsCode)){
        return response()->json([
            'status' => false,
            'message' => 'OTP code empty',
        ], 200);
        }
        /**get otp if in table */
        $otp = EmailResets::where('user_id', $users->id)
        ->orWhere('email',$users->email)
        ->orWhere('type','register')
        ->first();
        /**check if there is an otp code*/
        if(empty($otp)){
            return response()->json([
                'status' => false,
                'message' => 'No otp code matches your entry',
            ], 200);
        }
        /**check if the user entered a correct otp */
        if($otp->otp_code != $smsCode){
            return response()->json([
                'status' => false,
                'message' => 'Wrong code entered, please check your email inbox or spam folder and use the code sent to you',
            ], 200);
        }
        /*delete the email token */
        $deleted = EmailResets::where('email', $users->email)->delete();
        /**update the user password if otp is correct */
        if($users->email_verified_at == null){
            $users->update([
                'email_verified_at' => Security::timeNow(),
                'updated_at' => Security::timeNow(),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Account activated successfully, You may now login',
            ], 200);

         }

    }
}
