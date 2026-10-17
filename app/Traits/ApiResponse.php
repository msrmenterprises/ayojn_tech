<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * get success response
     *
     * @param $message
     * @param array $data
     * @param array $headers
     *
     * @return Response
     */
    public function successResponse($message, $data = [], $headers = [])
    {
        $response = [
            'status' => true,
            'message' => $message
        ];

        if (! empty($data)) {
            foreach ($data as $key => $value) {
                $response[$key] = $value;
            }
        }

        $response = response($response);

        if (! empty($headers)) {
            $response->withHeaders($headers);
        }

        return $response;
    }

    /**
     * get validation errors
     *
     * @param $message
     * @param array $extra
     *
     * @return Response
     */
    public function failResponse($message, $extra = [])
    {
        $response = [
            'status' => false,
            'errors' => $message
        ];

        if (! empty($extra)) {
            foreach ($extra as $key => $value) {
                $response[$key] = $value;
            }
        }

        return response($response);
    }

    /**
     * send validation errors response
     *
     * @param $validation  \Illuminate\Contracts\Validation\Validator
     *
     * @return Response
     */
    public function validationErrors($validation)
    {
        return $this->failResponse($validation->errors()->first());
    }

    /**
     * json response for api calls
     *
     * @param $message
     * @param array $data
     * @param bool $success
     *
     * @return JsonResponse
     */
    function jsonResponse(string $message, array $data = [], bool $success = true)
    {
//        if ($success === false && !array_key_exists('message', $data)) {
//            $data['message'] = $message;
//        }
        $response = ['success' => $success, 'message' => $message];

        if ($success) {
            $response['data'] = $data;
        }

        return new JsonResponse($response, 200);
    }
}
