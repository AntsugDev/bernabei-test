<?php
namespace App\Utils\Service;

use App\Utils\Client\ConsumerClient;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class ConsumerService {

  use ContainerAwareTrait;


  private ConsumerClient $client;

  public function __construct(ConsumerClient $conumerClient)
  {
    $this->client = $conumerClient;
  }

  /**
   * @param string $username
   * @param string $password
   */
  public function getUser(string $username,string $password){
    $content = $this->client->requestUser("");
    $filter =  array_filter($content,function($value) use($username,$password){
      return strcmp($value['username'],$username) === 0 && strcmp($value['pwd'],$password) === 0;
    });
    if(count($filter) >0 ){
      return $filter[array_keys($filter)[0]];
    }
    else return [];
  }

  public function requestConsumer(array $queryString, array $body){
    return $this->client->requestConsumer($queryString,$body);
  }

  public function requestUser(string $id){
    return $this->client->requestUser($id);
  }

}


?>
