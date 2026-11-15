<?php

namespace App\Models;

interface RentalRuleInterface 
{
    public function calculateTotalCost(float $basePrice, int $days): float;
}