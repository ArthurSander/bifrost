<?php

namespace Bifrost\Http\Api\JsonApi;

use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use League\Fractal\Serializer\JsonApiSerializer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

trait JsonApiAware
{

  /**
   * @param $data
   * @param int|null $defaultHttpCode
   * @param array $headers
   * @return JsonResponse
   */
  public function response($data, ?int $defaultHttpCode = 200, array $headers = [])
  {
    return fractal($data, $this->transformer)
      ->serializeWith(new JsonApiSerializer())
      ->withResourceName(class_basename($this->service->getEntityClassName()))
      ->respond($defaultHttpCode, $this->getHeaders($headers));
  }

  /**
   * @param LengthAwarePaginator $data
   * @param int|null $defaultHttpCode
   * @param array $headers
   * @return JsonResponse
   */
  public function paginate(LengthAwarePaginator $data, ?int $defaultHttpCode = 200, array $headers = [])
  {
    return fractal($data, $this->transformer)
      ->serializeWith(new JsonApiSerializer())
      ->withResourceName(class_basename($this->service->getEntityClassName()))
      ->paginateWith(new IlluminatePaginatorAdapter($data))
      ->respond($defaultHttpCode, $this->getHeaders($headers));
  }

  /**
   * @param $errors
   * @param int|null $defaultHttpCode
   * @param array $headers
   * @return JsonResponse
   */
  public function errorResponse($errors, ?int $defaultHttpCode = 422, array $headers = [])
  {
    return ErrorResponse::get($errors, $defaultHttpCode, $this->getHeaders($headers));
  }

  /**
   * @param $headers
   * @return array
   */
  protected function getHeaders($headers)
  {
    $contentType = Arr::get($headers, 'Content-Type', Arr::get($headers, 'content-type'));

    if ($contentType === null)
    {
      $headers['Content-Type'] = 'application/vnd.api+json';
    }

    return $headers;
  }

}
