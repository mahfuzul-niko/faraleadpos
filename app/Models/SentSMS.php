<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentSMS extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function check_sms_count($sms_length) {
        $sms_count_number = $sms_length / 67;
        $sms_count = 1;
        if($sms_count_number > 15) {
            $sms_count = 16;
        }
        else if($sms_count_number > 14) {
            $sms_count = 15;
        }
        else if($sms_count_number > 13) {
            $sms_count = 14;
        }
        else if($sms_count_number > 12) {
            $sms_count = 13;
        }
        else if($sms_count_number > 11) {
            $sms_count = 12;
        }
        else if($sms_count_number > 10) {
            $sms_count = 11;
        }
        else if($sms_count_number > 9) {
            $sms_count = 10;
        }
        else if($sms_count_number > 8) {
            $sms_count = 9;
        }
        else if($sms_count_number > 7) {
            $sms_count = 8;
        }
        else if($sms_count_number > 6) {
            $sms_count = 7;
        }
        else if($sms_count_number > 5) {
            $sms_count = 6;
        }
        else if($sms_count_number > 4) {
            $sms_count = 5;
        }
        else if($sms_count_number > 3) {
            $sms_count = 4;
        }
        else if($sms_count_number > 2) {
            $sms_count = 3;
        }
        else if($sms_count_number > 1) {
            $sms_count = 2;
        }
        else if($sms_count_number > 0) {
            $sms_count = 1;
        }

        return $sms_count;

    }

    public static function send_sms($msg='test', $number=0) {
        $number = preg_replace('#[ -]+#', '', $number);
        $number = preg_replace('#[=]+#', '', $number);
        if(strlen($number)==10 || strlen($number)==13){
            $number = "0".$number;
        }

        $msg = str_replace("<br>","\n",$msg);
        $msg = str_replace(" ","+",$msg);
        $msg = strip_tags($msg);

        // new sms
        $apiKey = 'kP0EN7n17j5EKfBCg1NoVqK9oZnYkiDd';
        $apiToken = 'VkgR1710674492';
        $senderID = '8809601004664';
        $to = '88'.$number;
        $text = $msg;
        $scheduleDate = '';
        $route = '0';

        $url = "https://mimsms.com.bd/smsAPI?sendsms&apikey=$apiKey&apitoken=$apiToken&type=sms&from=$senderID&to=$to&text=$text&scheduledate=$scheduleDate&route=$route";
        $response = file_get_contents($url);
        return $response;
        // new sms end
    }

}
