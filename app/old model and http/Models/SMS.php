<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    use HasFactory;

    //send sms
    public static function send_sms($msg, $number) {
        $number = preg_replace('#[ -]+#', '', $number);
        $number = preg_replace('#[=]+#', '', $number);
        if(strlen($number)==10 || strlen($number)==13){
            $number = "0".$number; 
        }
        $msg = str_replace("<br>","\n",$msg);
        $msg = strip_tags($msg);
        $url = "https://esms.mimsms.com/smsapi";
        $data = [
            "api_key" => "C200839760cca9b9942376.05627003",
            "type" => "text",
            "contacts" => $number,
            "senderid" => "8809612436500",
            "msg" => $msg,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


}
