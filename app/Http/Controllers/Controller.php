<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

  use AuthorizesRequests, ValidatesRequests;

  static function apiResponse(bool $success, $message, array $values = [], $code = 200) {
    return response()->json([
      'success' => $success,
      'message' => $message,
      ...$values,
    ]);
  }

  static function apiSuccessResponse($message, array $values = [], $code = 200) {
    return Controller::apiResponse(true, $message, $values, $code);
  }

  static function apiErrorResponse($message, array $values = [], $code = 200) {
    return Controller::apiResponse(false, $message, $values, $code);
  }

  public function tryDecodeArray(string|null $text) {
    try {
      return (array) json_decode($text);
    } catch (\Throwable $th) {
      return $text;
    }
  }
}
