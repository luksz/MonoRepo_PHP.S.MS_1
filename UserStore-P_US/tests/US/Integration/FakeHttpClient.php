<?php

namespace App\Tests\US\Integration;

use LogicException;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class FakeHttpClient implements HttpClientInterface
{

    public function __construct(
        /**
         *
         * @var FakeHttpResponse[]
         */
        public array $responses = []
    ) {
        $this->setDefaultResponse();
        /**
         *
         * @var FakeHttpResponse $response
         */
        foreach ($responses as $response) {
            $this->responses[$response->url][$response->method] = $response->resonse;
        }
    }

    private function setDefaultResponse(): void
    {
        $responses = [
            [
                'url'   => '/notification',
                'method' => 'POST',
                'response' => new MockResponse('')
            ]
        ];

        foreach ($responses as  $response) {
            $this->responses[$response['url']][$response['method']] = $response['response'];
        }
    }



    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return (new MockHttpClient($this->responses[$url][$method], 'https://fake'))
            ->request($method, $url);
    }


    public function stream(ResponseInterface|iterable $responses, float $timeout = null): ResponseStreamInterface
    {
        throw new LogicException("Not Impelement");
    }


    public function withOptions(array $options): static
    {
        return $this;
    }
}