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

class ApiControllerBreak extends Controller
{
    public function returnMessage($id)
    {

        //trajanje pauze, tj. koliko djelatnici smiju biti na pauzi
        $onBreakSum = 0;
        $breakTimeAllowed = 1800;
        $mailTo = 'adriano@inmobile-accessories.hr';
        $mailTo2 = 'tomislav@besoft.hr';

        $userObject = User::where('keyId', $id)->first();

        //provjera da li je ključić povezan sa korisnikom u bazi, ako nije vraća error
        if (empty($userObject)) {
            //return to the front page with error "Ovaj ključić nije ispravan ili korisnik za njega nije napravljen"
            InsertInto::errorLog($id, 'Kljucic nije ispravan i nije povezan niti sa jednim userom', 2);
            $msg = Message::message($id, $onBreakSum, $breakTimeAllowed, 0);
            return new ApiJson($msg);
        }

        $userId = $userObject->id;
        $now = Carbon::now();

        $lastCheckin = GetData::getLastCheckin($userId)['checkin'];
        $lastCheckout = GetData::getLastCheckout($userId)['checkout'];

        $lastArrival = GetData::getLastArrival($userId)['arrival'];
        $lastDeparture = GetData::getLastDeparture($userId)['departure'];


        //Glavna logika app, prema zadnjim unosima u bazu provjerava gdje unosi trenutni zapis
        //Insert into CHECKOUT ili CHECKIN
        if (($lastDeparture < $lastArrival) && ($lastArrival < $now)) {
            //insert as first checkout today (početak pauze)
            if ($lastCheckout < $lastArrival) {
                CheckinCheckout::submitCheckout($userId, $now);
                $onBreakSum = CalcHelper::timeOnBreakSumForToday($userId);
                $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed, 3);
                return new ApiJson($msg);

                //insert into checkin (kraj pauze)
            } else if ($lastCheckin < $lastCheckout) {
                $onBreakTimestamp = CalcHelper::dateStringDiffInTimestamp($now, $lastCheckout);
                CheckinCheckout::submitCheckinAndOnBreak($userId, $now, $onBreakTimestamp, 0);
                $onBreakTodaySum = CalcHelper::timeOnBreakSumForToday($userId);
                $msg = Message::message($userId, $onBreakTodaySum, $breakTimeAllowed, 4);

                //ako je nakon pauze došlo do prekoračenja pauze, pošalji mail --> gleda današnji dan
                if ($onBreakTodaySum > $breakTimeAllowed) {
                    $dataMail = CheckinCheckout::where('userId', $userId)->whereDate('checkout', Carbon::today())->get();
                    Mail::to($mailTo)->cc($mailTo2)->send(new WarningMail($userObject, $dataMail, $onBreakTodaySum));
                }
                return new ApiJson($msg);

                //insert into checkout (početak pauze)
            } else if ($lastCheckin >= $lastCheckout) {
                CheckinCheckout::submitCheckout($userId, $now);
                $onBreakSum = CalcHelper::timeOnBreakSumForToday($userId);
                $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed, 5);
                return new ApiJson($msg);

                //insert into checkout, prva pauza za tog usera (početak pauze)
            } else if ($lastCheckin == 0 && $lastCheckout == 0) {
                CheckinCheckout::submitCheckout($userId, $now);
                $onBreakSum = CalcHelper::timeOnBreakSumForToday($userId);
                $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed, 3);
                return new ApiJson($msg);
            }

            //Error i obavijest da se moraju prijaviti sa jedinicom
        } else if ($lastDeparture > $lastArrival || $lastDeparture == $lastArrival) {
            InsertInto::errorLog($id, 'User se pokusava prijaviti na pauzu, a nije prijavljen na posao', 5);
            $msg = Message::message($userId, $onBreakSum, $breakTimeAllowed, 6);
            return new ApiJson($msg);
        } else {
            //Ovdje zavrsi ako ni jedan od uvijeta nije ispunjen i to se dogodi samo u slucaju greske
            InsertInto::errorLog($id, 'User nije zadovoljio uvjete za PAUZU', 4);
            $msg = Message::message($userId, 0, $breakTimeAllowed, 6);
            return new ApiJson($msg);
        }




}
}
