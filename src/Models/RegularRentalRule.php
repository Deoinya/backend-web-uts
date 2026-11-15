<?php

namespace App\Models;

class RegularRentalRule implements RentalRuleInterface 
{
    public function calculateTotalCost(float $basePrice, int $days): float 
    {
        return $basePrice * $days;
    }
}