<?php
namespace App\Model;

class Book
{
    public $id;
    public $title;
    public $price;

    public function getArrayCopy()
    {
        return array(
            'id'       => $this->id,
            'title'    => $this->title,
            'price'    => $this->price
        );
    }

    public function exchangeArray(array $array)
    {
        $this->id = $array['id'];
        $this->title = $array['title'];
        $this->price = $array['price'];
    }
}
