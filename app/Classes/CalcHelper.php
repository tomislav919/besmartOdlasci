<?php

namespace App\Classes;

use App\CheckinCheckout;
use Carbon\Carbon;
use DateTime;
use App\User;

class CalcHelper
{

    public static function dateStringDiffInTimestamp($checkin, $checkout){
        $t1 = strtotime($checkin);
        $t2 = strtotime($checkout);

        return $dateDiffInTimestamp = $t1 - $t2;
    }

    public static function timeOnBreak($userId, $arrival, $departure){


        $var =  CheckinCheckout::whereBetween('checkout', [$arrival, $departure])->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();

        return $var;
    }

    public static function timeOnBreakSumForToday($userId){

        $var = CheckinCheckout::whereDate('checkout', Carbon::today())->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();

        return $var;
    }

    public static function timeOnBreakSumForDate($userId, $date){

        $var = CheckinCheckout::whereDate('checkout', '=', date($date))->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();

        return $var;
    }

    public static function hoursMinutesToFullTime($hoursMins) {

        $now = Carbon::now();
        $now = $now->toDateString();
        $fullTime = $now . ' ' . $hoursMins . ':00';

        return $fullTime;
    }

}
