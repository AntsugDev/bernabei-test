<?php

namespace App\Utils;

class Pageable
{


    private int $page;
    private int $size;
    private string $order;
    private string $sortBy;

    private array $content;

    public function __construct(int $page, int $size, string $order, string $sortBy, array $content)
    {
        $this->page = $page;
        $this->size = $size;
        $this->order = $order;
        $this->sortBy = $sortBy;
        $this->content = $content;
    }




    private function init()
    {
        return array(
            "content" => [],
            "page" => 0,
            "totalPage" => 0,
            "sortBy" => "",
            "order" => 'DESC',
            "totalElements" => 0
        );
    }


    public function toArray(array $body)
    {
        try {
            $array = $this->init();
            $filter = $this->content;
            if (array_key_exists('title', $body) && !is_null($body['title'])) {
                $tmp = $filter;
                $params = $body['title'];
                $filter = array_filter($tmp, function ($value) use ($params) {
                    return stristr($value['title'], $params) !== false;
                });
            }

            if (array_key_exists('description', $body) && !is_null($body['description'])) {
                $tmp = $filter;
                $params = $body['description'];
                $filter = array_filter($tmp, function ($value) use ($params) {
                    return stristr($value['description'], $params) !== false;
                });
            }


            $column = array_column($filter, $this->sortBy);

            if (count($filter) > 0) {
                if (strcmp(strtoupper($this->order), 'DESC') === 0)
                    array_multisort($column, SORT_DESC, $filter);
                else
                    array_multisort($column, SORT_ASC, $filter);
            }
            $chunk = count($filter) > 0 ?  array_chunk($filter, $this->size) : [];

            $array['content'] = array_key_exists(intval($this->page), $chunk) ?  $chunk[intval($this->page)] : [];
            $array['totalPage'] = count($chunk);
            $array['totalElements'] = count($filter);
            $array['page'] = $this->page;
            $array['order'] = $this->order;
            $array['sortBy'] = $this->sortBy;

            return $array;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
