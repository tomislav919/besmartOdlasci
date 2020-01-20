<?php


namespace App\Classes;

use App\ErrorLog;


class InsertInto
{
    public static function errorLog ($userKeyId, $description, $errorType)
    {
        $error = new ErrorLog(['keyId'=>$userKeyId, 'description'=> $description, 'errorType' => $errorType]);

        $error->save();
    }
}
