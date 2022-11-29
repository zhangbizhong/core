<?php


namespace Core\Database\Mysql;


class PageInfo
{
    public $total = 0;
    /**
     * @var ListModel
     */
    public $list;
    
    public function toArray()
    {
        return [
            'total' => $this->total,
            'list'  => $this->list->toArray()
        ];
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->list->valid();
    }
}