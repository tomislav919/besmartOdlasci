<?php

namespace App\Http\Controllers;

use App\CheckinCheckout;
use App\Classes\CalcHelper;
use App\Classes\Replace;
use App\User;
use App\NewUser;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $req)
    {
        $formDate = $req['formDate'];
        $reportData = (object) [];
        session(['sessionDate' => null]);

        //format datuma iz datetimepickera(dd.mm.yyyy) jer eloquent prima yyyy-mm-dd
        if ($formDate == null)        {
            $data = CheckinCheckout::whereDate('created_at', Carbon::today())->get();
            $reportData->selectedDate = Carbon::today()->format('d.m.Y');
        } else {
            $dateF = explode('.', $req['formDate']);
            $datePicker = $dateF[2] . '-' . $dateF[1] . '-' . $dateF[0];
            $data = CheckinCheckout::whereDate('created_at', '=', date($datePicker))->get();
            $reportData->selectedDate = $formDate;
            session(['sessionDate' => $formDate]);
        }


        $users = User::all()->except('1');

        $i=0;
        foreach ($users as $user)
        {
            $id = $user->id;
            $stopLooping = false;

            foreach ($data as $d)
            {
                if ($d->userId == $id && $d->arrival == true && $stopLooping == false)
                {
                    $date = new \DateTime($d->arrival);
                    $formatedDate = $date->format('H:i:s');
                    $users[$i]->arrival = $formatedDate;
                    $stopLooping = true;
                }
                if ($d->userId == $id && $d->departure == true)
                {
                    $date = new \DateTime($d->departure);
                    $formatedDate = $date->format('H:i:s');
                    $users[$i]->departure = $formatedDate;
                }
            }

            if ($formDate == null) {
                $break = gmdate("H:i:s", CalcHelper::timeOnBreakSumForToday($id));
            } else {
                $break = gmdate("H:i:s", CalcHelper::timeOnBreakSumForDate($id, $datePicker));
            }

            $users[$i]->break = $break;
            $users[$i]->loopIt = $i+1;

            //dodavanje klase za boju
            $timeLimit = "00:30:00";
            $breakLimitTS = strtotime($timeLimit);
            $breakTS = strtotime($break);
            if ($breakLimitTS <= $breakTS)
            {
                $users[$i]->trClass = 'tr-color';
                $users[$i]->thClass = 'th-color-dark';
            }

            $i++;
        }

        return view('home')
            ->with('users', $users)
            ->with('data', $reportData);
    }

    public function userReport(Request $req, $userId)
    {

        $formDate = $req['formDate'];
        $reportData = (object) [];
        $reportData->sessionDate = session('sessionDate');

        if($reportData->sessionDate == null)
        {
            $formDate = $req['formDate'];
        } else {
            $formDate = $reportData->sessionDate;
            session(['sessionDate' => null]);
        }

        //format datuma iz datetimepickera(dd.mm.yyyy) jer eloquent prima yyyy-mm-dd
        if ($formDate == null)        {
            $dataBreak = CheckinCheckout::whereDate('checkout', Carbon::today())->where('userId', '=', $userId)->get();
            $dataWork = CheckinCheckout::whereDate('arrival', Carbon::today())->where('userId', '=', $userId)->get();
            $reportData->selectedDate = Carbon::today()->format('d.m.Y');
        } else {
            $dateF = explode('.', $formDate);
            $datePicker = $dateF[2] . '-' . $dateF[1] . '-' . $dateF[0];
            $dataBreak = CheckinCheckout::whereDate('checkout', '=', date($datePicker))->where('userId', '=', $userId)->get();
            $dataWork = CheckinCheckout::whereDate('arrival', '=', date($datePicker))->where('userId', '=', $userId)->get();
            $reportData->selectedDate = $formDate;
        }


        $i = 0;
        foreach ($dataBreak as $d)
        {
            $date = new \DateTime($d->checkout);
            $formatedDate = $date->format('H:i:s');
            $dataBreak[$i]->checkout = $formatedDate;

            $date = new \DateTime($d->checkin);
            $formatedDate = $date->format('H:i:s');
            $dataBreak[$i]->checkin = $formatedDate;

            $break = gmdate("H:i:s", $d->onBreakTimestamp);
            $dataBreak[$i]->onBreak = $break;
            $dataBreak[$i]->loopIt = $i+1;
            $i++;
        }


        $i = 0;
        foreach ($dataWork as $d)
        {
            $date = new \DateTime($d->arrival);
            $formatedDate = $date->format('H:i:s');
            $dataWork[$i]->arrival = $formatedDate;

            $date = new \DateTime($d->departure);
            $formatedDate = $date->format('H:i:s');
            $dataWork[$i]->departure = $formatedDate;

            $dataWork[$i]->loopIt = $i+1;
            $i++;
        }

        return view('/userreport')
            ->with('user', User::where('id', '=', $userId)->first())
            ->with('dataBreak', $dataBreak)
            ->with('dataWork', $dataWork)
            ->with('data', $reportData);
    }

    public function formReport (Request $req)
    {
        $formData = $req['formDate'];

        return view('home')
            ->with('data', $formData);
    }

    public function changeSession(Request $req)
    {
        $input = $req->all();
        $date = $input['date'];
        $date = str_replace('-', '.', $date);

        session(['sessionDate' => $date]);

        return response()->json($date);
    }

    public function periodReport(Request $req)
    {

        $userId = $req['user'];
        $dateRange = $req['daterange'];
        $typeFilter = $req['typeFilter'];

        switch ($typeFilter)
        {
            //početni prikaz, kada nema nikakvih podataka za prikazati u tablici
            case null;

                $data = (object) [];
                $users = User::all()->except('1');
                $requestUser = User::where('id', '=', $userId)->first();

                return View('period')
                    ->with('data', $data)
                    ->with('users', $users)
                    ->with('requestUser', $requestUser);
                break;


            //prikaz "Prikaz svih dolazaka/odlazaka"
            case 1:

                if ($userId == 'Odaberite djelatnika'){
                    echo "<script>alert('Niste odabrali djelatnika, molimo Vas da ga odaberete!');</script>";
                }

                //format datuma iz daterangepicker(dd.mm.yyyy - dd.mm.yyyy) jer eloquent prima yyyy-mm-dd
                $dateF = explode(' - ', $dateRange);

                $dateStart = explode('.', $dateF[0]);
                $dateEnd = explode('.', $dateF[1]);
                $dateFrom = $dateStart[2] . '-' . $dateStart[1] . '-' . $dateStart[0];
                $dateTo = $dateEnd[2] . '-' . $dateEnd[1] . '-' . $dateEnd[0];

                $data = CheckinCheckout::whereBetween('arrival', [$dateFrom, $dateTo])->where('userId', '=', $userId)->get();


                $users = User::all()->except('1');
                $requestUser = User::where('id', '=', $userId)->first();

                if(isset($requestUser)){
                    $requestUser->dateRange = $dateRange;
                    $requestUser->id = $userId;
                }



                $i = 0;
                foreach ($data as $d)
                {
                    $data[$i]->loopIt = $i+1;

                    $date = new \DateTime($d->arrival);
                    $data[$i]->date = $date->format('d-m-Y');
                    $data[$i]->detailsLink = '<a href=""';

                    //racunanje ukupne pauze za dan, ako danas dan nije zatvoren onda umjesto departure vremena koristim now
                    if(!($d->departure == null)){
                        $breakSum =  CheckinCheckout::whereBetween('checkout', [$d->arrival, $d->departure])->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();
                        $data[$i]->breakSum = gmdate("H:i:s", $breakSum);
                    } else {
                        $breakSum =  CheckinCheckout::whereBetween('checkout', [$d->arrival, Carbon::now()])->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();
                        $data[$i]->breakSum = gmdate("H:i:s", $breakSum);
                    }

                    $timeLimit = "00:30:00";
                    $breakLimitTS = strtotime($timeLimit);

                    $breakTS = strtotime($data[$i]->breakSum);
                    if ($breakLimitTS <= $breakTS)
                    {
                        $data[$i]->trClass = 'tr-color';
                        $data[$i]->thClass = 'th-color-dark';
                    }

                    $i++;
                }

                return View('period')
                    ->with('data', $data)
                    ->with('users', $users)
                    ->with('requestUser', $requestUser);

                break;



            //prikaz "Prikaz po danu"
            case 2:

                if ($userId == 'Odaberite djelatnika'){
                    echo "<script>alert('Niste odabrali djelatnika, molimo Vas da ga odaberete!');</script>";
                }

                //format datuma iz daterangepicker(dd.mm.yyyy - dd.mm.yyyy) jer eloquent prima yyyy-mm-dd
                $dateF = explode(' - ', $dateRange);

                $dateStart = explode('.', $dateF[0]);
                $dateEnd = explode('.', $dateF[1]);
                $dateFrom = $dateStart[2] . '-' . $dateStart[1] . '-' . $dateStart[0];
                $dateTo = $dateEnd[2] . '-' . $dateEnd[1] . '-' . $dateEnd[0];

                $data = CheckinCheckout::whereBetween('arrival', [$dateFrom, $dateTo])->where('userId', '=', $userId)->get();


                $users = User::all()->except('1');
                $requestUser = User::where('id', '=', $userId)->first();

                if(isset($requestUser)){
                    $requestUser->dateRange = $dateRange;
                    $requestUser->id = $userId;
                }


                $i = 0;
                foreach ($data as $d)
                {
                    $data[$i]->loopIt = $i+1;

                    $date = new \DateTime($d->arrival);
                    $data[$i]->date = $date->format('d-m-Y');
                    $data[$i]->detailsLink = '<a href=""';

                    //racunanje ukupne pauze za dan, ako danas dan nije zatvoren onda umjesto departure vremena koristim now
                    if(!($d->departure == null)){
                        $data[$i]->breakSum =  CheckinCheckout::whereBetween('checkout', [$d->arrival, $d->departure])->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();
                    } else {
                        $data[$i]->breakSum =  CheckinCheckout::whereBetween('checkout', [$d->arrival, Carbon::now()])->where('userId', '=', $userId)->whereNotNull('checkout')->pluck('onBreakTimestamp')->sum();
                    }

                    $i++;
                }


                $lastDate = false;
                $arr = [];
                $arrCount = count($data);
                $counter = 0;
                $i = 1;
                foreach ($data as $index => $d)
                {
                    $date = new \DateTime($d->arrival);
                    $date = $date->format('d-m-Y');

                    if(!$lastDate)
                    {
                        //ovdje ide samo u prvom loopu
                        $arr[$i]['date'] = $d->date;
                        $arr[$i]['arrival'] = $d->arrival;
                        $arr[$i]['departure'] = $d->departure;
                        $arr[$i]['sum'] = $d->breakSum;

                        $lastDate = $date;
                    }
                    else if($date == $lastDate)
                    {
                        //ovdje ide ako mu je trenutni datum isti kao prethodni
                        $arr[$i]['departure'] = $d->departure;
                        $arr[$i]['sum'] = $arr[$i]['sum'] + $d->breakSum;
                }
                    else if($date != $lastDate)
                    {
                        //ovdje ide ako je trenutni datum veci od proslog (tj drugaciji)
                        $arr[$i]['sum'] = gmdate("H:i:s", $arr[$i]['sum']);

                        $timeLimit = "00:30:00";
                        $breakLimitTS = strtotime($timeLimit);
                        $breakTS = strtotime($arr[$i]['sum']);

                        if ($breakLimitTS <= $breakTS)
                        {
                            $arr[$i]['trClass'] = 'tr-color';
                            $arr[$i]['thClass'] = 'th-color-dark';
                        }

                        $i++;
                        $arr[$i]['date'] = $d->date;
                        $arr[$i]['arrival'] = $d->arrival;
                        $arr[$i]['departure'] = $d->departure;
                        $arr[$i]['sum'] = $d->breakSum;
                        $lastDate = $date;
                    }

                    $counter++;

                    if($arrCount == $counter)
                    {
                        $arr[$i]['sum'] = gmdate("H:i:s", $arr[$i]['sum']);
                    }
                }

                $data = [];
                $i = 1;
                foreach ($arr as $item) {
                    $n = new \stdClass();
                    $n->loopIt = $i;
                    $n->date = $item['date'];
                    $n->arrival = $item['arrival'];
                    $n->departure = $item['departure'];
                    $n->breakSum = $item['sum'];
                    if(isset($item['trClass'])){
                        $n->trClass = $item['trClass'];
                    }
                    if(isset($item['trClass'])){
                        $n->thClass = $item['thClass'];
                    }
                    $data[] = $n;
                    $i++;
                }

                return View('period')
                    ->with('data', $data)
                    ->with('users', $users)
                    ->with('requestUser', $requestUser);

                break;


            //defaultni prikaz i ako ovo prikaže znači da dolazi do greške
            default:
                $data = (object) [];
                $users = User::all()->except('1');
                $requestUser = User::where('id', '=', $userId)->first();
                echo "<script>alert('Greška, nema podataka, pokušajte ponovno!');</script>";
                return View('period')
                    ->with('data', $data)
                    ->with('users', $users)
                    ->with('requestUser', $requestUser);
                break;
        }
    }

    public function newKeyAdmin(Request $req)
    {
        $userId = $req['user'];
        $data = (object) [];
        $users = User::all()->except('1');
        $requestUser = User::where('id', '=', $userId)->first();

        $newKeys = NewUser::where('userId', '=', 0)->get();



        $i = 0;
        foreach($newKeys as $n)
        {
            $time = new \DateTime($n->created_at);
            $timeFormat = $time->format('d-m-Y H:i:s');

            $newKeys[$i]->createdFormat = $timeFormat;
            $newKeys[$i]->loopIt = $i+1;
            $i++;
        }

        return View('newKeyAdmin')
            ->with('data', $data)
            ->with('users', $users)
            ->with('requestUser', $requestUser)
            ->with('newKeys', $newKeys);

    }

    public function deleteNewKey(Request $req)
    {

        $newKeyId = $req['id'];
        NewUser::destroy($newKeyId);

        return redirect('/newKeyAdmin');
    }

    public function addNewUser(Request $req)
    {

        $newKeys = NewUser::where('userId', '=', 0)->get();

        $keyId = $req['keyId'];
        $name = Replace::croLetters($req['name']);
        $lastName = Replace::croLetters($req['lastName']);
        $gender = $req['gender'];

        $checkUser = User::where('keyId', '=', $keyId)->get();

        if(!($checkUser->isEmpty()))
        {
            foreach($checkUser as $user)
            {
                if($keyId == $user->keyId)
                {
                    $alert = 'Postoji djelatnik koji koristi taj ključić, ne mogu dva djelatnika koristiti isti ključić!';
                    return redirect('/newKeyAdmin')
                        ->with('newKeys', $newKeys)
                        ->with('alert', $alert);
                }
            }
        }


        if($name == true && $lastName == true)
        {
            User::create([
                'keyId' => $keyId,
                'name' => $name,
                'lastName' => $lastName,
                'gender' => $gender,
                'isAdmin' => 0,
            ]);

            $newUser = User::latest()->first();
            NewUser::where('userKeyId', '=', $keyId)->where('userId', '=', 0)->update(['userId' => $newUser->id]);

            $alert = 'Ključić za novog korisnika je uspješno dodan!';
        }
        else
        {
            $alert = 'Ključić nije dodan, upišite ime i prezime te označite spol!';
        }


        return redirect('/newKeyAdmin')
            ->with('newKeys', $newKeys)
            ->with('alert', $alert);
    }

    public function userAdmin()
    {
        $users = User::all()->except('1');

        $i = 0;
        foreach ($users as $u)
        {
            $users[$i]->loopIt = $i+1;
            $i++;
        }

        return view('/userAdmin')
            ->with('users', $users);
    }

    public function userEdit(Request $req)
    {

        $id = $req['id'];
        $keyId = $req['keyId'];
        $name = Replace::croLetters($req['name']);
        $lastName = Replace::croLetters($req['lastName']);
        $gender = $req['gender'];

        //Provjerava da li taj ključić već postoji u bazi
        $keyExists = User::all()->except($id);
        $keyExists = $keyExists->where('keyId', '=', $keyId);
        if(!($keyExists->isEmpty()))
        {
            foreach($keyExists as $k)
            {
                if($k->keyId == $keyId)
                {
                    $alert = 'Pokušavate dodati ključić koji koristi neki drugi korisnik';
                    return Redirect('/userAdmin')
                        ->with('alert', $alert);
                }
            }
        }

        //Provjerava da li su ime i prezime uneseni
        if($keyId == true && $name == true && $lastName == true)
        {
            User::where('id', '=', $id)->update([
                'keyId' => $keyId,
                'name' => $name,
                'lastName' => $lastName,
                'gender' => $gender,
            ]);

            $alert = 'Izmjena korisnika je uspješno napravljena!';
            return Redirect('/userAdmin')
                ->with('alert', $alert);
        }

        //Ovdje zavrsava samo u slučaju greške
        $alert = 'Došlo je do greške, pokušajte ponovno!';
        return Redirect('/userAdmin')
            ->with('alert', $alert);
    }
}
