<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Wallet;
use \Validator;

class AdminController extends Controller {

  public function __construct() {
    $this->middleware('auth:admin', ['except' => [
      'auth',
      'register',
      'emailResend',
      'emailVerify',
      'login',
      // 'passwordForgot',
      // 'pf_emailVerify',
      // 'pf_passwordReset',
    ]]);
    // $this->middleware('guest:admin');
  }

  public function auth() {
    return view('admin.auth');
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
    $username = Admin::whereUsername($request->username)->first();
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
        'errors' => ['phone' => 'You already registered please go to login']
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
    $admin = Admin::create([
      'user_id' => $user->id,
      'username' => $request->username,
      'password' => bcrypt($request->password),
    ]);

    $verifyCode = random_int(100000, 999999);
    $token = $admin->createToken('email_verify', ['email-verify'], [
      'code' => $verifyCode,
      'send_count' => 1,
    ]);

    return $this->apiSuccessResponse("Successfully registering (code: $verifyCode)", [
      'token' => $token->plainTextToken,
    ]);
  }

  public function emailResend(Request $request) {
    $admin = $request->user();
    $token = $admin->tokens->last();
    if (now()->diffInSeconds($token->created_at) < 20) return $this->apiErrorResponse('Please wait until the waiting time has expired');
    $sCount = $token->data['send_count'];
    $token->delete();
    if ($sCount >= 5) return $this->apiErrorResponse('You have sent too many emails. Please check your email or contact support');

    $verifyCode = random_int(100000, 999999);
    $token = $admin->createToken('email_verify', ['email-verify'], [
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

    $admin = $request->user();
    $user = $admin->link()->user;
    $token = $admin->tokens->last();
    if($token->data['code'] == $request->code) {
      if($user->email_verified_at != null) {
        return $this->apiErrorResponse('This email already verified', [
          'errors' => ['email' => 'This email already verified']
        ]);
      }
      $user->email_verified_at = now();
      if($admin->wallet_id == null) {
        $wallet = Wallet::create([
          'user_id' => $admin->id,
          'user_model' => Admin::class,
          'balance' => 0,
          'status' => 'active',
        ]);
        $admin->wallet_id = $wallet->id;
      } else {
        $wallet = $admin->link()->wallet;
        $wallet->status = 'active';
        $wallet->save();
      }
      $admin->status = 'offline';
      $admin->save();
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
      $authed = auth('admin')->attempt(['user_id' => $user->id, 'password' => $request->password]);
      if($authed) {
        $admin = $request->user('admin');
        $token = $admin->createToken('access_token')->plainTextToken;
        return $this->apiSuccessResponse('Successfully login');
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

  public function logout(Request $request) {
    foreach ($request->user()->tokens as $token) $token->delete();
    session()->flush();
    auth('admin')->logout();
    \Log::info("success logout");
    return redirect()->route('admin.auth');
  }
}
