<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUser;
use App\Models\User;
use App\Models\Mail;
use App\Models\Wallet;
use Auth;
use Validator;

class UserController extends Controller {

  public function __construct() {
    $this->middleware('auth:sanctum', ['except' => [
      'register',
      'emailResend',
      'emailVerify',
      'login',
      'passwordForgot',
      'pf_emailVerify',
      'pf_passwordReset',
    ]]);
  }

  public function register(Request $request) {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|between:4,100',
      'first_name' => 'required|string|between:2,100',
      'last_name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100',
      'phone' => 'required|string',
      'address' => $request->address ? 'string' : '',
      'gander' => 'required|string',
      'password' => 'required|string|min:6',
    ]);
    if($validator->fails()){
      return $this->apiErrorResponse(null, [
        'errors' => $validator->errors(),
      ]);
    }

    $email = User::whereEmail($request->email)->first();
    $phone = User::wherePhone($request->phone)->first();
    $username = AppUser::whereUsername($request->username)->first();
    if(!is_null($email) && !is_null($email->email_verified_at)) {
      return $this->apiErrorResponse('You already registered please go to login', [
        'errors' => ['email' => 'You already registered please go to login']
      ]);
    } else if(!is_null($phone)) {
      return $this->apiErrorResponse('You already registered please go to login', [
        'errors' => ['phone' => 'You already registered please go to login']
      ]);
    } else if(!is_null($username)) {
      return $this->apiErrorResponse('You already registered please go to login', [
        'errors' => ['username' => 'You already registered please go to login']
      ]);
    }

    if($email) $email->delete();

    $user = User::create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'phone' => $request->phone,
      'address' => $request->address,
      'gander' => $request->gander,
    ]);
    $appUser = AppUser::create([
      'user_id' => $user->id,
      'username' => $request->username,
      'password' => bcrypt($request->password),
    ]);

    $verifyCode = random_int(100000, 999999);
    $token = $appUser->createToken('email_verify', ['email-verify'], [
      'code' => $verifyCode,
      'send_count' => 1,
    ]);

    // Mail::create([
    //   'title' => 'Email Verification',
    //   'template_id' => Setting::emailVerificationTemplateId(),
    //   'data' => ['<-user->' => $user->fullname, '<-code->' => $verifyCode],
    //   'targets' => [$user->id],
    //   'unreades' => Admin::unreades(),
    // ]);

    return $this->apiSuccessResponse("Successfully registering (code: $verifyCode)", [
      'token' => $token->plainTextToken,
    ]);
  }

  public function emailResend(Request $request) {
    $appUser = $request->user();
    $token = $appUser->tokens->last();
    if (now()->diffInSeconds($token->created_at) < 20) return $this->apiErrorResponse('Please wait until the waiting time has expired');
    $sCount = $token->data['send_count'];
    $token->delete();
    if ($sCount >= 5) return $this->apiErrorResponse('You have sent too many emails. Please check your email or contact support');

    $verifyCode = random_int(100000, 999999);
    $token = $appUser->createToken('email_verify', ['email-verify'], [
      'code' => $verifyCode,
      'send_count' => $sCount + 1,
    ]);

    // Mail::create([
    //   'title' => 'Email Verification',
    //   'template_id' => Setting::emailVerificationTemplateId(),
    //   'data' => ['<-user->' => $user->fullname, '<-code->' => $verifyCode],
    //   'targets' => [$user->id],
    //   'unreades' => Admin::unreades(),
    // ]);

    return $this->apiSuccessResponse("User successfully resending verification email (code: $verifyCode)", [
      'token' => $token->plainTextToken,
    ]);
  }

  public function emailVerify(Request $request) {
    $validator = Validator::make($request->all(), [
      'code' => 'required|string|size:6',
    ]);
    if ($validator->fails()) {
      return $this->apiErrorResponse(null, [
        'errors' => $validator->errors(),
      ]);
    }

    $appUser = $request->user();
    $user = $appUser->link()->user;
    $token = $appUser->tokens->last();
    if($token->data['code'] == $request->code) {
      if($user->email_verified_at != null) {
        return $this->apiErrorResponse('This email already verified', [
          'errors' => ['email' => 'This email already verified']
        ]);
      }
      $user->email_verified_at = now();
      if($appUser->wallet_id == null) {
        $wallet = Wallet::create([
          'user_id' => $appUser->id,
          'user_model' => AppUser::class,
          'balance' => 0,
          'status' => 'active',
        ]);
        $appUser->wallet_id = $wallet->id;
      } else {
        $wallet = $appUser->link()->wallet;
        $wallet->status = 'active';
        $wallet->save();
      }
      $appUser->status = 'offline';
      $appUser->save();
      $user->save();
      $token->delete();
      return $this->apiSuccessResponse('User successfully verifying email');
    } else {
      return $this->apiErrorResponse('Invalid code', [
        'errors' => ['code' => 'Invalid code']
      ]);
    }
  }

  public function login(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    if ($validator->fails()) {
      return $this->apiErrorResponse('has errors', [
        'errors' => $validator->errors(),
      ]);
    }

    $user = User::whereEmail($request->email)->first();
    if(!is_null($user)) {
      if(is_null($user->email_verified_at)) {
        return $this->apiErrorResponse('Your email is not verified.', [
          'errors' => ['email' => 'Your email is not verified']
        ]);
      }
      // $username = AppUser::whereUserId($user->id)->first()->username;
      $authed = auth('app')->attempt(['user_id' => $user->id, 'password' => $request->password]);
      if($authed) {
        $appUser = $request->user('app');
        $token = $appUser->createToken('access_token')->plainTextToken;
        return $this->apiSuccessResponse('Successfully login', [
          'token' => $token,
          'user' => $appUser->link(),
        ]);
      } else {
        return $this->apiErrorResponse('Invalid password', [
          'errors' => [
            'password' => 'Invalid Password',
          ]
        ]);
      }
    } else {
      return $this->apiErrorResponse('Invalid email', [
        'errors' => [
          'email' => 'Invalid email'
        ]
      ]);
    }
  }

  public function getUser(Request $request) {
    return $this->apiSuccessResponse('Successfully getting user', [
      'user' => $request->user()->link(),
    ]);
  }

  public function passwordForgot(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);
    if ($validator->fails()) {
      return $this->apiErrorResponse(null, [
        'errors' => $validator->errors(),
      ]);
    }
    $user = User::whereEmail($request->email)->first();
    if(!is_null($user)) {
      if(is_null($user->email_verified_at)) {
        return $this->apiErrorResponse('Email not verified, please verify your email', [
          'errors' => ['email' => 'Email not verified, please verify your email']
        ]);
      }
      $appUser = AppUser::whereUserId($user->id)->first();
      $verifyCode = random_int(100000, 999999);
      $token = $appUser->createToken('pf_email_verify', ['email-verify'], [
        'code' => $verifyCode,
        'send_count' => 1,
      ]);
      // Mail::create([
      //   'title' => 'Email Verification',
      //   'template_id' => Setting::emailVerificationTemplateId(),
      //   'data' => ['<-code->' => $verifyCode, '<-user->' => $user->fullname],
      //   'targets' => [$user->id],
      //   'unreades' => Admin::unreades(),
      // ]);
      return $this->apiSuccessResponse("Successfully verifying email (code: $verifyCode)", [
        'token' => $token->plainTextToken,
      ]);
    } else {
      return $this->apiErrorResponse('Invalid email', [
        'errors' => ['email' => 'Invalid email']
      ]);
    }
  }

  public function pf_emailVerify(Request $request) {
    $validator = Validator::make($request->all(), [
      'code' => 'required|string|size:6',
    ]);
    if ($validator->fails()) {
      return $this->apiErrorResponse(null, [
        'errors' => $validator->errors(),
      ]);
    }

    $appUser = $request->user();
    $token = $appUser->tokens->last();
    if($token->data['code'] == $request->code) {
      $token->delete();
      $token = $appUser->createToken('pf_password_reset', ['password-reset']);
      return $this->apiSuccessResponse('User successfully verifying email', [
        'token' => $token->plainTextToken,
      ]);
    } else {
      return $this->apiErrorResponse('Invalid code', [
        'errors' => ['code' => 'Invalid code']
      ]);
    }
  }

  public function pf_passwordReset(Request $request) {
    $validator = Validator::make($request->all(), [
      'new_password' => 'required|string|min:6',
    ]);
    if ($validator->fails()) {
      return $this->apiErrorResponse(null, [
        'errors' => $validator->errors(),
      ]);
    }

    $appUser = $request->user();
    $token = $appUser->tokens->last();
    $token->delete();
    $appUser->password = bcrypt($request->new_password);
    $appUser->save();
    return $this->apiSuccessResponse('Successfully reset password');
  }

}
