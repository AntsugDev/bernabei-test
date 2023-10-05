<?php

namespace App\Utils\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppClient
{



    public function HttpRequest(string $method, string $url, array $queryString = [], array $body = [])
    {
        try {
            $httpClient = HttpClient::create();
            $options = array();
            if (count($queryString) > 0)
                $options['query'] = $queryString;

            if (count($body) > 0)
                $options['body'] = $body;

            $response = $httpClient->request($method, $url, $options);

            return $response->toArray();
        } catch (HttpException $e) {
            return new RedirectResponse('/error-cust?exception=' . base64_encode($e->getMessage()));
        }
    }
}
