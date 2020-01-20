<?php

namespace App\Classes;

use App\CheckinCheckout;
use App\User;
use Carbon\Carbon;

class GetData
{


    public static function getLastArrival($userId){
        return CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('arrival')->first();
    }

    public static function getLastDeparture($userId){
        return CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('departure')->first();
    }

    public static function getLastCheckin($userId){
        return CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('checkin')->first();
    }

    public static function getLastCheckout($userId){
        return CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('checkout')->first();
    }

    public static function getUserFromUsers($userId){
        return User::where('keyId', '=', $userId)->get();

    }

    public static function getFirstArrivalToday($userId)
    {
        return CheckinCheckout::where('userId', '=', $userId)->whereDate('arrival', Carbon::today())->first();
    }


}
