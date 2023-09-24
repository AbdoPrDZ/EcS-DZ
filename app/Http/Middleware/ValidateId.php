<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ValidateId
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, string $model, string $indexName = 'id'): Response
  {
    $id = $request->route()->parameter($indexName);
    if (!$id) return abort(500);
    if (!class_exists($model)) return abort(500);
    if (!Schema::hasColumn((new $model)->getTable(), $indexName)) return abort(500);
    if (!$item = app($model)->where([$indexName => $id])->first()) return abort(404);

    $request->route()->setParameter($indexName, $item);

    return $next($request);
  }
}
