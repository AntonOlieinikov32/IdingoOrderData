<?php

declare(strict_types=1);

namespace Olieinikov\OrderData\Model\Client;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\ResponseFactory;
use Psr\Log\LoggerInterface as Logger;

class Guzzle
{
    /**
     * @param ClientFactory $clientFactory
     * @param ResponseFactory $responseFactory
     * @param Logger $logger
     */
    public function __construct(
        private readonly ClientFactory   $clientFactory,
        private readonly ResponseFactory $responseFactory,
        private readonly Logger          $logger
    )
    {
    }

    /**
     * @param array $data
     * @param string $requestMethod
     */
    public function doRequest(
        array  $data,
        string $requestMethod,
    ): void
    {
        $baseUri = 'https://thirdpartyAPI.com/';
        $uriEndpoint = 'test/order/details';
        $requestHeaders = $this->getHeaders();
        $client = $this->clientFactory->create(
            ['config' => [
                'base_uri' => $baseUri,
                'headers' => $requestHeaders,
            ]]
        );

        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $data
            );
        } catch (GuzzleException $exception) {
            $response = $this->responseFactory->create([
                'status' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ]);
        }

        if (preg_match('/^40/', (string)$response->getStatusCode())) {
            $this->logger->debug('error');
        } else if (preg_match('/^20/', (string)$response->getStatusCode())) {
            $this->logger->debug('success');
        }
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}
