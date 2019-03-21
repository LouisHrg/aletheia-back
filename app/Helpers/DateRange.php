<?php

namespace App\Helpers;

class DateRange
{

    public static function getDateRange($date){

        switch($date){
            case 'week':
                $dateRange = date('Ymd', strtotime('-7 days')).'__'.date('Ymd');
                break;
            case 'month':
                $dateRange = date('Ymd', strtotime('-1 month')).'__'.date('Ymd');
                break;
            case 'semester':
                $dateRange = date('Ymd', strtotime('-6 month')).'__'.date('Ymd');
                break;
            case 'year':
                $dateRange = date('Ymd', strtotime('-1 year')).'__'.date('Ymd');
                break;

        }

        return $dateRange;
    }
}
