<?php

namespace App\Controllers;

use App\Helpers\JsonStorage;
use App\Models\EBook;

class BookController
{
    private JsonStorage $storage;

    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    public function getAllBooks(): string
    {
        $rawBooks = $this->storage->readAll();

        // Memanfaatkan model EBook untuk memvalidasi struktur dasar, lalu melengkapi atribut katalog FE
        $baseResponse = json_decode(EBook::fetchCatalog($rawBooks), true);
        $catalog = [];

        foreach ($rawBooks as $index => $book) {
            $baseItem = $baseResponse['data'][$index] ?? [];
            $catalog[] = [
                'id' => $baseItem['id'] ?? $book['id'],
                'title' => $baseItem['title'] ?? $book['title'],
                'author' => $baseItem['author'] ?? $book['author'],
                'price' => $baseItem['price'] ?? ($book['price'] ?? 0),
                'category' => $book['category'] ?? 'Umum',
                'status' => $book['status'] ?? 'available',
                'cover' => $book['cover'] ?? ''
            ];
        }

        return json_encode([
            'status' => 'success',
            'data' => $catalog
        ]);
    }
}