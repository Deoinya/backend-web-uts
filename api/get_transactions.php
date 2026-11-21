<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../src/Helpers/JsonStorage.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Member.php';
require_once __DIR__ . '/../src/Models/EBook.php';
require_once __DIR__ . '/../src/Models/RentalRuleInterface.php';
require_once __DIR__ . '/../src/Models/RegularRentalRule.php';
require_once __DIR__ . '/../src/Models/PremiumRentalRule.php';
require_once __DIR__ . '/../src/Models/Transaction.php';
require_once __DIR__ . '/../src/Controllers/TransactionController.php';

use App\Helpers\JsonStorage;
use App\Controllers\TransactionController;

$bookStorage = new JsonStorage(__DIR__ . '/../data/books.json');
$trxStorage = new JsonStorage(__DIR__ . '/../data/transactions.json');
$controller = new TransactionController($bookStorage, $trxStorage);

$email = isset($_GET['email']) ? trim($_GET['email']) : null;
echo $controller->getHistory($email);