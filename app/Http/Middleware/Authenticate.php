<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware {

  /**
   * Handle an unauthenticated user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  array  $guards
   * @return void
   *
   * @throws \Illuminate\Auth\AuthenticationException
   */
  protected function unauthenticated($request, array $guards) {
    if (empty($guards)) $guards = [null];
    throw new AuthenticationException(
      'Unauthenticated.', $guards, $this->redirectTo($request, $guards)
    );
  }

  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request, array $guards = []): ?string {
    return $request->expectsJson() ? null : route(RouteServiceProvider::redirectTo($guards[0]));
  }
}
