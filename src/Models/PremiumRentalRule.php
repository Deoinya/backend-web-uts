<?php

namespace App\Models;

class PremiumRentalRule implements RentalRuleInterface 
{
    public function calculateTotalCost(float $basePrice, int $days): float 
    {
        $discount = 0.20; 
        $total = $basePrice * $days;
        return $total - ($total * $discount);
    }
}