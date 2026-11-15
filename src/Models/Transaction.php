<?php

namespace App\Models;

class Transaction 
{
    private string $transactionId;
    private User $user;
    private EBook $ebook;
    private int $rentalDays;
    private RentalRuleInterface $rentalRule;

    public function __construct(string $transactionId, User $user, EBook $ebook, int $rentalDays, RentalRuleInterface $rentalRule) 
    {
        $this->transactionId = $transactionId;
        $this->user = $user;
        $this->ebook = $ebook;
        $this->rentalDays = $rentalDays;
        $this->rentalRule = $rentalRule;
    }

    public function processTransaction(): string 
    {
        $totalCost = $this->rentalRule->calculateTotalCost($this->ebook->getBasePrice(), $this->rentalDays);

        return json_encode([
            'status' => 'success',
            'message' => 'Transaksi berhasil diproses',
            'data' => [
                'transaction_id' => $this->transactionId,
                'total_cost' => $totalCost,
                'rental_days' => $this->rentalDays
            ]
        ]);
    }
}