<?php


namespace App\Http\Helper;


class LibHelper
{
    public static function sendOtpFunction($number,$otp){

        $ch = curl_init();
        $url            = "https://control.msg91.com/api/sendotp.php?authkey=302176AeEcfLaw5dc0355a&mobile=".$number."&message=TOP GEAR OTP%20".$otp."&sender=OWNWAY&country=91&otp=".$otp."&otp_length=6";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }


}
