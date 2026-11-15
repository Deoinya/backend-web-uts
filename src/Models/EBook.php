<?php

namespace App\Models;

class EBook 
{
    private string $id;
    private string $title;
    private string $author;
    private float $basePrice;

    public function __construct(string $id, string $title, string $author, float $basePrice) 
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->basePrice = $basePrice;
    }

    public function getBasePrice(): float 
    {
        return $this->basePrice;
    }

    public function getDetailsAsJson(): string 
    {
        return json_encode([
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'base_price' => $this->basePrice
        ]);
    }

    public static function fetchCatalog(array $rawData): string 
    {
        $catalog = [];
        foreach ($rawData as $data) {
            $catalog[] = [
                'id' => $data['id'],
                'title' => $data['title'],
                'author' => $data['author'],
                'price' => $data['price']
            ];
        }

        return json_encode([
            'status' => 'success',
            'data' => $catalog
        ]);
    }
}