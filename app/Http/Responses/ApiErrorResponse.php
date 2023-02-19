<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Throwable;

class ApiErrorResponse implements Responsable
{
  private $message, $exception, $code, $headers;

  public function __construct(string $message, ?Throwable $exception = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR, array $headers = [])
  {
    $this->message = $message;
    $this->exception = $exception;
    $this->code = $code;
    $this->headers = $headers;
  }

  /**
   * @param  $request
   * @return \Symfony\Component\HttpFoundation\Response|void
   */
  public function toResponse($request)
  {
    $response = ['message' => $this->message];

    if (!is_null($this->exception) && config('app.debug')) {
      $response['debug'] = [
        'message' => $this->exception->getMessage(),
        'file'    => $this->exception->getFile(),
        'line'    => $this->exception->getLine(),
        'trace'   => $this->exception->getTraceAsString()
      ];
    }

    return response()->json($response, $this->code, $this->headers);
  }
}
