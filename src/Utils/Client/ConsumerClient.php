<?php

namespace App\Utils\Client;

use App\Utils\Pageable;



class ConsumerClient extends AppClient
{
    const API = "https://fakestoreapi.com/products";
    const API_USER = "https://fakestoreapi.com/users";
    

    public function requestConsumer(array $queryString, array $body)
    {
        $content = $this->HttpRequest('GET', self::API,$queryString,$body);
        $page = $queryString['page']; 
        $size= $queryString['size']; 
        $order = $queryString['order'];
         $sortBy = $queryString['sortBy'];
        $pageable = new Pageable($page, $size, $order, $sortBy, $content);
        return $pageable->toArray($body);
    }

    public function requestUser(string $id)
    {
        $content = $this->HttpRequest('GET', self::API_USER);
        $content = $this->LetUser($content);
        if (strcmp($id, "") !== 0) {
            return $this->ClientValidToken($id, $content);
        } else return $content;
    }

    private function LetUser(array $content)
    {
        $newArray = array();
        array_map(function ($value) use (&$newArray) {
            array_push($newArray, array('id' => $value['id'], 'username' => $value['username'], 'pwd' => $value['password'], 'firstaname' => $value['name']['firstname'], 'lastname' => $value['name']['lastname']));
        }, $content);
        return $newArray;
    }

    private function ClientValidToken(string $encode, array $content): array
    {

        return array_filter($content, function ($value) use ($encode) {
            return strcmp(base64_encode($value['username'] . ':' . $value['pwd']), $encode) === 0;
        });
    }
}
