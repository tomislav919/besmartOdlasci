<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckinCheckout extends Model
{
    protected $fillable = [
        'userId',
        'checkin',
        'checkout',
        'arrival',
        'departure',
        'onBreakTimeStamp',
        'autoClosed',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'userId');
    }

    public static function submitArrival($userId, $now)
    {
        $user = User::where('id', $userId)->first();

        $chekinChekout = new CheckinCheckout(['userId'=>$user, 'arrival'=> $now]);

        $user->CheckinCheckout()->save($chekinChekout);
    }

    public static function submitDeparture($userId, $now, $autoclose = 0)
    {

        $user = User::where('id', $userId)->first();
        $checkinCheckout = CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('arrival')->first();
        $checkinCheckout->departure = $now;
      	if ($autoclose == 1) {
            $checkinCheckout->autoClosed = 1;
        }


        $user->CheckinCheckout()->save($checkinCheckout);
    }

    public static function submitCheckinAndOnBreak($userId, $now, $onBreakTimestamp, $autoClosed)
    {
        //Insert checkin and break
        $user = User::where('id', $userId)->first();

        $checkinCheckout = CheckinCheckout::latest()->where('userId', '=', $userId)->whereNotNull('checkout')->first();
        $checkinCheckout->checkin = $now;
        $checkinCheckout->onBreakTimestamp = $onBreakTimestamp;

        if($autoClosed == 1){
            $checkinCheckout->autoClosed = 1;
        }

        $user->CheckinCheckout()->save($checkinCheckout);

    }

    public static function submitCheckout($userId, $now)
    {
        $user = User::where('id', $userId)->first();

        $chekinChekout = new CheckinCheckout(['userId'=>$user, 'checkout'=> $now]);

        $user->CheckinCheckout()->save($chekinChekout);
    }
}
