<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error-cust', name: 'app_error_cust')]
    public function index(Request $request): JsonResponse
    {
        $queryString = $request->getQueryString();
        $result = array();
        parse_str($queryString,$result);
        return new JsonResponse([
            "status" => $result['status'],
            'msg' => base64_decode($result['msg']),
            'timeRequest' => date('d/m/Y H:i.s', time()),
        ],$result['status']);
    }
}
