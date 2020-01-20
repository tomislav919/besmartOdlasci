<?php

namespace App\Http\Controllers;

use App\Classes\GetData;
use App\Classes\Message;
use App\Http\Resources\ApiJson;
use App\Http\Resources\ApiJsonNewKey;
use App\User;
use App\NewUser;

class ApiNewUserController extends Controller
{
    public function addNewUser($keyId, $name, $lastName, $gender){

        $user = GetData::getUserFromUsers($keyId);

        //provjerava da li su svi parametri unešeni, ako jesu, unosi u bazu
        if ($keyId == true && $name == true && $lastName == true && ($gender  == 0 || $gender == 1) == true) {
            if($user->isEmpty()){
                User::create([
                    'keyId' => $keyId,
                    'name' => $name,
                    'lastName' => $lastName,
                    'gender' => $gender,
                    'isAdmin' => 0,
                ]);
                $messageFirst = 'Uspjesno napravljen korisnik!';
                $messageSecond = $keyId;
                $msg = Message::ApiMsg($keyId, $name, $lastName, $gender, $messageFirst, $messageSecond);
                return new ApiJson($msg);
            } else {
                $messageFirst = 'Kljuc vec postoji!';
                $messageSecond = $keyId;
                $msg = Message::ApiMsg($keyId, $name, $lastName, $gender, $messageFirst, $messageSecond);
                return new ApiJson($msg);
            }
        } else {
            $messageFirst = 'Niste upisali neki od parametara! Napomena: oznaka za muskarce je 0, a za zene 1';
            $messageSecond = 'Nema parametra';
            $msg = Message::ApiMsg($keyId, $name, $lastName, $gender, $messageFirst, $messageSecond);
            return new ApiJson($msg);
        }
    }

    public function addKey($userKeyId){

        //userId mora biti 0 jer po tome se u administraciji prepoznaje da je to novi ključić za novog usera (kad se taj ključić iskoristi za novog usera 0 se update-a u id korisnika iz tablice "users")
        $userId = 0;

        $keyExists = NewUser::where('userKeyId', '=', $userKeyId)->get();
        $keyExistsUserTable = User::where('keyId', '=', $userKeyId)->get();

        if($keyExists->isEmpty() && $keyExistsUserTable->isEmpty())
        {
            NewUser::create([
                'userKeyId' => $userKeyId,
                'userId' => $userId,
            ]);
            $messageFirst = 'Novi kljuc je dodan!';
            $messageSecond = $userKeyId;
            $msg = Message::ApiNewKeyMsg($userKeyId, $userId, $messageFirst, $messageSecond);
            return new ApiJsonNewKey($msg);
        }
        else
        {
            foreach($keyExists as $k)
            {
                if ($k->userId == 0)
                {
                $messageFirst = 'Kljuc vec postoji!';
                $messageSecond = $userKeyId;
                $msg = Message::ApiNewKeyMsg($userKeyId, $userId, $messageFirst, $messageSecond);
                return new ApiJsonNewKey($msg);
                }
            }

            if ($keyExistsUserTable->isEmpty() == false)
            {
                $messageFirst = 'Netko koristi kljuc!';
                $messageSecond = $userKeyId;
                $msg = Message::ApiNewKeyMsg($userKeyId, $userId, $messageFirst, $messageSecond);
                return new ApiJsonNewKey($msg);
            }

            NewUser::create([
                'userKeyId' => $userKeyId,
                'userId' => $userId,
                ]);
            $messageFirst = 'Novi kljuc je dodan!';
            $messageSecond = $userKeyId;
            $msg = Message::ApiNewKeyMsg($userKeyId, $userId, $messageFirst, $messageSecond);
            return new ApiJsonNewKey($msg);

        }
    }
}
