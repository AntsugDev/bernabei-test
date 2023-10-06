<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    #[Route('/error', name: 'app_error')]
    public function error(Request $request): Response
    {
        $result = array();
        $status = 503;
        $msg = "Service Unavailable ";
        $queryString = $request->getQueryString();
        if(!is_null($queryString)){
            parse_str($queryString,$result);
            $status = $result['status'];
            $msg = !is_null($result['msg']) ? base64_decode($result['msg']) : "Service Unavailable";
        }

        return $this->render('error/index.html.twig', [
            'controller_name' => 'ErrorController',
            'msg' => $msg,
            'status' =>$status
        ]);
    }
}
