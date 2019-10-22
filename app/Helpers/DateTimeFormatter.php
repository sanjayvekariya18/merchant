<?php

namespace App\Helpers;

use DateTime;

class DateTimeFormatter
{

	public static function convertDate($date){
		$convert = new DateTime($date);
		return $convert->format('Ymd');
	}

	public static function convertTime($time){
		$time = preg_replace('/\s/','',$time);
		$convert = new DateTime($time);
		return $convert->format('H:i:s');
	}
	
	public static function getDateFromDatetime($datetime){

		if(preg_match("/\.\d+\s*[AaPp][Mm]/",$datetime)){
			$datetime = strrev(preg_replace('/\s/','',strrev($datetime),1));	
		}

		$convert = new DateTime($datetime);
		return $convert->format('Ymd');
	}

	public static function getTimeFromDatetime($datetime){
		if(preg_match("/\.\d+\s*[AaPp][Mm]/",$datetime)){
			$datetime = strrev(preg_replace('/\s/','',strrev($datetime),1));	
		}	
		$convert = new DateTime($datetime);
		return $convert->format('H:i:s');
	}

	public static function convertTimeToMiliseconds($time){
		$openTimeData = explode(":", $time);
		$miliSeconds = $openTimeData[0]*3600+$openTimeData[1]*60;
		return $miliSeconds;
	}
}