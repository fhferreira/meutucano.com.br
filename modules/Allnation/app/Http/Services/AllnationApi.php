<?php namespace Allnation\Http\Services;

use GuzzleHttp\Client;
use Allnation\
use Illuminate\Support\Facades\Log;

/**
 * Class AllnationApi
 * @package Allnation\Http\Services
 */
class AllnationApi
{
    /**
     * API Client
     *
     * @var GuzzleHttp\Client
     */
    private $api;

    /**
     * Initiate API Client and configuration
     */
    public function __construct()
    {
        try {
            $this->api = new \SoapClient(
                config('allnation.api.url'),
                [
                    'stream_context' => stream_context_create([
                        'http' => [
                            'user_agent' => 'PHPSoapClient'
                        ]
                    ]),
                    'trace'              => true,
                    'exceptions'         => true,
                    'connection_timeout' => 20,
                    'cache_wsdl'         => WSDL_CACHE_NONE
                ]
            );

            if (is_soap_fault($this->api)) {
                throw new \Exception('Falha ao tentar fazer conexão soap na Allnation', 1);
            }

            return true;
        }  catch (\Exception $e) {
            Log::warning(logMessage($e, 'Não foi possível conectar à API da Allnation'));
            return false;
        }
    }

    /**
     * Create request to Allnation API and merge query string
     *
     * @param  array $args
     * @return object|null
     */
    private function request($action, $params = [])
    {
        Log::debug('Requisição allnation para: ' . $action , $params);

        try {
            $response = $this->api->{$action}(array_merge([
                'CodigoCliente' => config('allnation.api.CodigoCliente'),
                'Senha'         => config('allnation.api.Senha'),
            ], $params));

            return $response;
        } catch (\Exception $e) {
            Log::warning(logMessage($e, 'Não foi possível fazer a requisição para: ' . $args[1] . ', method: ' . $args[0]));
            return null;
        }
    }

    /**
     * Fetch new products from API
     *
     * @return array
     */
    public function fetchProducts()
    {
        $response = $this->request('RetornarListaProdutos', [
            'Data' => '2016-12-02 08:00:00'
        ]);

        $produtos = simplexml_load_string($response->RetornarListaProdutosResult->any)->NewDataSet;

        return $produtos->Produtos;
    }
}