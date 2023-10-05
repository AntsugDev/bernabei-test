<?php

namespace App\Controller;

use App\Utils\Service\ConsumerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Client\ConsumerClient;

class ConsumerController extends AbstractController
{
    #[Route('/api/consumer', name: 'app_consumer', methods: ['POST'])]
    public function index(Request $request,ConsumerService $service): JsonResponse
    {

        $body = $request->toArray();
        $queryString = $request->getQueryString();
        $obj = array();
        parse_str($queryString, $obj);
        return $this->json($service->requestConsumer($obj,$body));
    }

    #[Route('/api/user/{id}', name: 'app_user', methods: ['GET'])]
    public function user(string  $id = "" , Request $request,ConsumerService $service): JsonResponse
    {
        $response = $service->requestUser($id);
        return new JsonResponse($response, array_key_exists('status',$response) ? $response['status'] : 200);
    }



    #[Route('/auth/consumer', name: 'app_consumer_auth', methods: ['GET','POST'])]
    public function auth(Request $request,ConsumerService $service): JsonResponse
    {

        $body = $request->toArray();
        $queryString = $request->getQueryString();
        $obj = array();
        parse_str($queryString, $obj);
        return $this->json($service->requestConsumer($obj['page'], $obj['size'], $obj['order'], $obj['sortBy'],$body));
    }
}
