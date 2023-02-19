<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class ApiSuccessResponse implements Responsable
{
  private $data, $metadata, $code, $headers;

  /**
   * @param  mixed  $data
   * @param  array  $metadata
   * @param  int  $code
   * @param  array  $headers
   */
  public function __construct(mixed $data, array $metadata = [], int $code = Response::HTTP_OK, array $headers = [])
  {
    $this->data = $data;
    $this->metadata = $metadata;
    $this->code = $code;
    $this->headers = $headers;
  }

  /**
   * @param  $request
   * @return \Symfony\Component\HttpFoundation\Response|void
   */
  public function toResponse($request)
  {
    return response()->json(
      [
        'data' => $this->data,
        'metadata' => $this->metadata,
      ],
      $this->code,
      $this->headers
    );
  }
}
