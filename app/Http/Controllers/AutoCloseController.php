<?php

namespace App\Http\Controllers;

use App\CheckinCheckout;
use App\Classes\CalcHelper;
use App\Classes\GetData;
use App\Mail\AutoClose;
use App\User;
use Carbon\Carbon;


class AutoCloseController extends Controller
{
    public function close()
    {

        $now = Carbon::now();
        $allUsers = User::all();
        $usersAutoClose = [];


        foreach($allUsers as $oneUser)
        {

            $userId = $oneUser->id;

            $lastArrival = GetData::getLastArrival($userId)['arrival'];
            $lastDeparture = GetData::getLastDeparture($userId)['departure'];

            $mailTo = 'adriano@inmobile-accessories.hr';


            if ($lastArrival > $lastDeparture)
            {

                $lastCheckout = GetData::getLastCheckout($userId)['checkout'];
                $lastRowCheckin = CheckinCheckout::latest()->where('userId', '=', $userId)->first()['checkin'];
                $lastRowCheckout = CheckinCheckout::latest()->where('userId', '=', $userId)->first()['checkout'];

                //insert into departure, ako je prvi departure za korisnika i ako nikad nije bio na pauzi
                if (($lastRowCheckin == 0) && ($lastRowCheckout == 0)) {
                    CheckinCheckout::submitDeparture($userId, $now, 1);

                    //insert into departure, ako je zadnja pauza otvorena zatvori ju
                } else if ($lastRowCheckin == 0) {
                    $onBreak = CalcHelper::dateStringDiffInTimestamp($now, $lastCheckout);
                    CheckinCheckout::submitCheckinAndOnBreak($userId, $now, $onBreak, 1);
                }

                //insert into departure
                CheckinCheckout::submitDeparture($userId, $now, 1);

                $usersAutoClose[] = $userId;


            }
        }

        if($usersAutoClose){
            \Mail::to($mailTo)->send(new AutoClose($usersAutoClose));
        }

    }
}
