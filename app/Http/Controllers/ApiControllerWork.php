<?php

namespace App\Http\Controllers;

use App\CheckinCheckout;
use App\Classes\CalcHelper;
use App\Classes\GetData;
use App\Classes\InsertInto;
use App\Http\Resources\ApiJson;
use App\Mail\WarningMail;
use App\User;
use Carbon\Carbon;
use App\Classes\Message;
use Mail;


class ApiControllerWork extends Controller
{
    public function returnMessage($id) {

        //trajanje pauze, tj. koliko djelatnici smiju biti na pauzi
        $onBreakSum = 0;
        $breakTimeAllowed = 1800;
        $mailTo = 'adriano@inmobile-accessories.hr';
        $mailTo2 = 'tomislav@besoft.hr';

        $userObject = User::where('keyId', $id)->first();


        //provjera da li je ključić povezan sa korisnikom u bazi, ako nije vraća error
        if (empty($userObject)) {
            //return to the front page with error "Ovaj ključić nije ispravan ili korisnik za njega nije napravljen"
            InsertInto::errorLog($id, 'Kljucic nije ispravan i nije povezan niti sa jednim userom', 1);
            $msg = Message::message($id, $onBreakSum, $breakTimeAllowed, 0);
            return new ApiJson($msg);
        }


        $userId = $userObject->id;
        $now = Carbon::now();

        $lastCheckout = GetData::getLastCheckout($userId)['checkout'];

        $lastArrival = GetData::getLastArrival($userId)['arrival'];
        $lastDeparture = GetData::getLastDeparture($userId)['departure'];

        $lastRowCheckin = CheckinCheckout::latest()->where('userId', '=', $userId)->first()['checkin'];
        $lastRowCheckout = CheckinCheckout::latest()->where('userId', '=', $userId)->first()['checkout'];



        //Glavna logika app, prema zadnjim unosima u bazu provjerava gdje unosi trenutni zapis
        //Insert into ARRIVAL
        if ($lastArrival < $lastDeparture || $lastArrival == $lastDeparture) {
            CheckinCheckout::submitArrival($userId, $now);
            $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed,1);
            return new ApiJson($msg);

            //insert into DEPARTURE
        } else if ($lastArrival > $lastDeparture) {

            //insert into departure, ako je je prvi departure za korisnika i ako nikad nije bio na pauzi
            if (($lastRowCheckin == 0) && ($lastRowCheckout == 0)) {
                CheckinCheckout::submitDeparture($userId, $now);

                //insert into DEPARTURE, ako je zadnja pauza otvorena zatvori ju
            } else if ($lastRowCheckin == 0) {
                $onBreak = CalcHelper::dateStringDiffInTimestamp($now, $lastCheckout);
                CheckinCheckout::submitCheckinAndOnBreak($userId, $now, $onBreak, 1);
            }

            //insert into departure, calculate Timestamp
            CheckinCheckout::submitDeparture($userId, $now);
            $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed,2);

            //ako je došlo do prekoračenja pauze pošalji mail --> gleda današnji dan
            $timeOnBreakToday = CalcHelper::timeOnBreakSumForToday($userId);

            if ($timeOnBreakToday > $breakTimeAllowed) {
                $dataMail = CheckinCheckout::where('userId', $userId)->whereDate('checkout', Carbon::today())->get();

                Mail::to($mailTo)->cc($mailTo2)->send(new WarningMail($userObject, $dataMail, $timeOnBreakToday));

            }

            return new ApiJson($msg);

            //U slučaju da ne ispunjava sve uvjete za unos u ARRIVAL ili DEPARTURE petlja izbacuje error
        } else {
            InsertInto::errorLog($id, 'User nije zadovoljio uvjete za ulazak u ARRIVAL ili DEPARTURE', 3);
            $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed, 7);
            return new ApiJson($msg);
        }




    }
}
