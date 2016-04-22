<?php namespace App\Http\Controllers;

/**
 * Class RestResponseTrait
 * @package App\Http\Controllers
 */
trait RestResponseTrait
{
    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createdResponse($data)
    {
        $response = [
            'code' => 201,
            'status' => 'succcess',
            'data' => $data
        ];
        return response()->json($response, $response['code']);
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function showResponse($data)
    {
        $response = [
            'code' => 200,
            'status' => 'succcess',
            'data' => $data
        ];
        return response()->json($response, $response['code']);
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function listResponse($data)
    {
        $response = [
            'code' => 200,
            'status' => 'succcess',
            'data' => $data
        ];
        return response()->json($response, $response['code']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function notFoundResponse()
    {
        $response = [
            'code' => 404,
            'status' => 'error',
            'data' => 'Resource Not Found',
            'message' => 'Not Found'
        ];
        return response()->json($response, $response['code']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function deletedResponse()
    {
        $response = [
            'code' => 204,
            'status' => 'success',
            'data' => [],
            'message' => 'Resource deleted'
        ];
        return response()->json($response, $response['code']);
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function clientErrorResponse($data)
    {
        $response = [
            'code' => 422,
            'status' => 'error',
            'data' => $data,
            'message' => 'Unprocessable entity'
        ];
        return response()->json($response, $response['code']);
    }
}