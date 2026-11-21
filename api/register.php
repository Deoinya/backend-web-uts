<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../src/Helpers/JsonStorage.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Member.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';

use App\Helpers\JsonStorage;
use App\Controllers\AuthController;

$storage = new JsonStorage(__DIR__ . '/../data/users.json');
$controller = new AuthController($storage);

echo $controller->register($_POST);