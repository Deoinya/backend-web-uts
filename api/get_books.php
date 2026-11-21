<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../src/Helpers/JsonStorage.php';
require_once __DIR__ . '/../src/Models/EBook.php';
require_once __DIR__ . '/../src/Controllers/BookController.php';

use App\Helpers\JsonStorage;
use App\Controllers\BookController;

$storage = new JsonStorage(__DIR__ . '/../data/books.json');
$controller = new BookController($storage);

echo $controller->getAllBooks();