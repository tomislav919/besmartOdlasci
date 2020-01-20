<?php

namespace App\Classes;

use App\User;


class Message
{

    public static function message($userId, $onBreakSum, $breakTimeAllowed, $type)
    {

        $user = User::where('id', '=', $userId)->first();

        if($onBreakSum == true){
            $breakLeft = $breakTimeAllowed - $onBreakSum;
            if($breakLeft > 0){
                $breakLeftMinutes = gmdate("i", $breakLeft);
                $timeLeftMsg = 'Imate ' . $breakLeftMinutes . ' min pauze';
            } else if($breakLeft <= 0){
                $timeLeftMsg = 'Potrosili ste pauzu!';
            }
        }


        switch ($type) {
            case 0:
                $msg = (object) [];
                $msg->id = 0;
                $msg->name = 'empty';
                $msg->lastName = 'empty';
                $msg->messageFirst = 'Kljuc ne radi!';
                $msg->messageSecond = $userId;

                return $msg;
                break;

            case 1:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;
                if($user->gender == 0){
                    $msg->messageFirst =  $user->name . ' ' . $user->lastName;
                    $msg->messageSecond ='Dobro dosao!';
                } else {
                    $msg->messageFirst = $user->name . ' ' . $user->lastName;
                    $msg->messageSecond = 'Dobro dosla!';
                }

                return $msg;
                break;

            case 2:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;
                $msg->messageFirst = $user->name . ' ' . $user->lastName;
                $msg->messageSecond = 'Dovidenja!';

                return $msg;
                break;

            case 3:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;

                if(isset($timeLeftMsg)){
                    $msg->messageFirst = $timeLeftMsg;
                    $msg->messageSecond = 'Pocetak pauze';
                } else {
                    $msg->messageFirst = 'Imate 30 min pauze!';
                    $msg->messageSecond = 'Pocetak pauze';
                }

                return $msg;
                break;

            case 4:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;

                if(isset($timeLeftMsg)){
                    $msg->messageFirst = $timeLeftMsg;
                    $msg->messageSecond = 'Kraj pauze';
                } else {
                    //ovdje nebi trebao dolazit jer $timeLeftMsg uvijek postoji kod vracanja s pauze
                    $msg->messageFirst = 'Kraj pauze';
                    $msg->messageSecond = 'Kraj pauze';
                }

                return $msg;
                break;

            case 5:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;

                if(isset($timeLeftMsg)){
                    $msg->messageFirst = $timeLeftMsg;
                    $msg->messageSecond = 'Pocetak pauze';
                } else {
                    //ovdje nebi trebao dolazit jer $timeLeftMsg postoji kod svih vracanja s pauze, osim kod prve
                    $msg->messageFirst = 'Pocetak pauze';
                    $msg->messageSecond = 'Pocetak pauze';
                }

                return $msg;
                break;

            case 6:
                $msg = (object) [];
                $msg->id = $userId;
                $msg->name = $user->name;
                $msg->lastName = $user->lastName;
                $msg->messageFirst = 'Prijavite se na posao';
                $msg->messageSecond = $user->name;

                return $msg;
                break;
            
            case 7:
                $msg = (object) [];
                $msg->id = 0;
                $msg->name = 'empty';
                $msg->lastName = 'empty';
                $msg->messageFirst = 'Error 7!';
                $msg->messageSecond = $userId;

                return $msg;
                break;

            default:
            $msg = (object) [];
            $msg->id = $userId;
            $msg->name = 'empty';
            $msg->lastName = 'empty';
            $msg->messageFirst = 'Poruka nije generirana';
            $msg->messageSecond = 'Greska!';

            return $msg;


        }
    }

    public static function ApiMsg ($id, $name, $lastName, $gender, $messageFirst, $messageSecond){
        $msg = (object) [];
        $msg->id = $id;
        $msg->name = $name;
        $msg->lastName = $lastName;
        $msg->gender = $gender;
        $msg->messageFirst = $messageFirst;
        $msg->messageSecond = $messageSecond;

        return $msg;
    }

    public static function ApiNewKeyMsg ($userKeyId, $userId, $messageFirst, $messageSecond){
        $msg = (object) [];
        $msg->userKeyId = $userKeyId;
        $msg->userId = $userId;
        $msg->messageFirst = $messageFirst;
        $msg->messageSecond = $messageSecond;

        return $msg;
    }
}
