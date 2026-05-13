<?php

if (!function_exists('calculate_business_days')) {
    function calculate_business_days($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day');
        
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $end);
        
        $days = 0;
        foreach ($periods as $period) {
            if ($period->format('N') < 6) { // 1 (Monday) to 5 (Friday)
                $days++;
            }
        }
        return $days;
    }
}
