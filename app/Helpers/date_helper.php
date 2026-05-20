<?php

if (!function_exists('calculate_business_days')) {
    function calculate_business_days($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        
        // On calcule la différence absolue
        $diff = $start->diff($end);
        
        // +1 car on inclut le jour de début et de fin
        return $diff->days + 1;
    }
}
