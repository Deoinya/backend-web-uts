<?php

header('Content-Type: application/json');

require_once 'src/models/User.php';
require_once 'src/models/Member.php';
require_once 'src/models/EBook.php';
require_once 'src/models/RentalRuleInterface.php';
require_once 'src/models/RegularRentalRule.php';
require_once 'src/models/PremiumRentalRule.php';
require_once 'src/models/Transaction.php';

use App\Models\Member;
use App\Models\EBook;
use App\Models\PremiumRentalRule;
use App\Models\Transaction;

$output = [];

$member = new Member("M001", "Budi Santoso", "budi@email.com", "Premium");
$output['test_registrasi'] = json_decode($member->register(['password' => 'rahasia123']));

$book = new EBook("BK-001", "Mastering PHP OOP", "John Doe", 50000);
$output['test_ebook'] = json_decode($book->getDetailsAsJson());

$premiumRule = new PremiumRentalRule(); 
$transaction = new Transaction("TRX-999", $member, $book, 3, $premiumRule);
$output['test_transaksi'] = json_decode($transaction->processTransaction());

echo json_encode($output, JSON_PRETTY_PRINT);