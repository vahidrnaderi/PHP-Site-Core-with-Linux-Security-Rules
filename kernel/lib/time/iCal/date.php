<?php
class iCal{

	public function dator($import = null, $format = null , $cal = 'jalali'){
		global $lang, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> dator($import, $format, $cal)\n");
		
		if(!isset($import))
		$date = time();
		elseif(isset($import)){
			if($lang[cal] == 'jalali'){
				list( $gyear, $gmonth, $gday ) = preg_split ( '/-/', date("Y-m-d", $import) );
				list( $jyear, $jmonth, $jday ) = $this->gregorian_to_jalali($gyear, $gmonth, $gday);

				switch ($format) {
					case 1:
						$date = $this->jalali_day($import) . " " . $jday . " " . $this->jalali_month($import) . " " . $jyear;
						break;
					case 2:
						$date = $this->jalali_day($import) . " " . $jday . " " . $this->jalali_month($import) . " " . $jyear . " $lang[hour] " . date("H:i:s", $import);
						break;
					case 'y':
						$date = $jyear;
						break;
					case 'm':
						$date = $jmonth;
						break;
					case 'd':
						$date = $jday;
						break;
					case 'dateTime':
						$date = $gyear . "-" . $gmonth . "-" . $gday . "T" . date("H:i:s", $import) . $settings['localTime'];
						break;
					default:
						$date = $jyear . "/" . $jmonth . "/" . $jday;
						break;
				}
			}elseif($lang[cal] == 'gregorian')
			$date = @date("Y/m/d", $import);
		}
		return $date;
	}

	public function jalali_month($time=null){
		global $lang, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> jalali_month($time)\n");

		$time = (!empty($time) ? $time : time());
		$number_of_day = date("z", $time);

		if($number_of_day >= 0 && $number_of_day <= 19)
		return $lang[month10];
		elseif($number_of_day > 19 && $number_of_day <= 49)
		return $lang[month11];
		elseif($number_of_day > 49 && $number_of_day <= 78)
		return $lang[month12];
		elseif($number_of_day > 78 && $number_of_day <= 109)
		return $lang[month1];
		elseif($number_of_day > 109 && $number_of_day <= 140)
		return $lang[month2];
		elseif($number_of_day > 140 && $number_of_day <= 171)
		return $lang[month3];
		elseif($number_of_day > 171 && $number_of_day <= 202)
		return $lang[month4];
		elseif($number_of_day > 202 && $number_of_day <= 233)
		return $lang[month5];
		elseif($number_of_day > 233 && $number_of_day <= 264)
		return $lang[month6];
		elseif($number_of_day > 264 && $number_of_day <= 294)
		return $lang[month7];
		elseif($number_of_day > 294 && $number_of_day <= 324)
		return $lang[month8];
		elseif($number_of_day > 324 && $number_of_day <= 354)
		return $lang[month9];
		elseif($number_of_day > 354 && $number_of_day <= 364)
		return $lang[month10];
	}

	public function jalali_day($time=null){
		global $lang, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> jalali_day($time)\n");
		
		$time = (!empty($time) ? $time : time());
		switch(date("D", $time)){
			case "Sat":
				return $lang[sat];
				break;
			case "Sun":
				return $lang[sun];
				break;
			case "Mon":
				return $lang[mon];
				break;
			case "Tue":
				return $lang[tue];
				break;
			case "Wed":
				return $lang[wed];
				break;
			case "Thu":
				return $lang[thu];
				break;
			case "Fri":
				return $lang[fri];
				break;
		}
	}

	public function geoimport($yimp, $mimp, $dimp, $himp=0, $mmimp=0, $simp=0){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> geoimport($yimp, $mimp, $dimp, $himp, $mmimp, $simp)\n");
		
		list( $gyear, $gmonth, $gday ) = $this->jalali_to_gregorian($yimp, $mimp, $dimp);
		$time = @mktime($himp, $mmimp, $simp, $gmonth, $gday, $gyear);
		//print date("M-d-Y", @mktime(0, 0, 0, $gmonth, $gday, $gyear));
		return $time;
	}

	public function agegen($birthtime){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> agegen($birthtime)\n");
		
		$age = ($birthtime < 0 ? time() + abs($birthtime) : time() - $birthtime);
		$year = 60 * 60 * 24 * 365;
		$age = $age / $year;
		$age = floor($age);
		return $age;
	}

	public function birthtime($year, $month=0, $day=0){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> birthtime($year, $month, $day)\n");
		
		$year = 60 * 60 * 24 * 365 * $year;
		$birthtime = time() - $year;
		return $birthtime;
	}

	public function div($a,$b) {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> div($a,$b)\n");
		
		return (int) ($a / $b);
	}

	public function gregorian_to_jalali ($g_y, $g_m, $g_d){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> gregorian_to_jalali ($g_y, $g_m, $g_d)\n");
		
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $g_y-1600;
		$gm = $g_m-1;
		$gd = $g_d-1;
		$g_day_no = 365*$gy+$this->div($gy+3,4)-$this->div($gy+99,100)+$this->div($gy+399,400);
		for ($i=0; $i < $gm; ++$i)
		$g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
		/* leap and after Feb */
		$g_day_no++;
		$g_day_no += $gd;
		$j_day_no = $g_day_no-79;
		$j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;
		$jy = 979+33*$j_np+4*$this->div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461;
		if ($j_day_no >= 366){
			$jy += $this->div($j_day_no-1, 365);
			$j_day_no = ($j_day_no-1)%365;
		}
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
		$j_day_no -= $j_days_in_month[$i];
		$jm = $i+1;
		$jd = $j_day_no+1;
		return array($jy, $jm, $jd);
	}


	public function jalali_to_gregorian($j_y, $j_m, $j_d){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> date.php-> jalali_to_gregorian($j_y, $j_m, $j_d)\n");
		
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$jy = $j_y-979;
		$jm = $j_m-1;
		$jd = $j_d-1;
		$j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
		for($i=0; $i < $jm; ++$i)
		$j_day_no += $j_days_in_month[$i];
		$j_day_no += $jd;
		$g_day_no = $j_day_no+79;
		$gy = 1600 + 400*$this->div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097;
		$leap = true;
		if($g_day_no >= 36525){ /* 36525 = 365*100 + 100/4 */
			$g_day_no--;
			$gy += 100*$this->div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524;
			if ($g_day_no >= 365)
			$g_day_no++;
			else
			$leap = false;
		}
		$gy += 4*$this->div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$g_day_no %= 1461;
		if($g_day_no >= 366){
			$leap = false;
			$g_day_no--;
			$gy += $this->div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}
		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
		$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i+1;
		$gd = $g_day_no+1;
		return array($gy, $gm, $gd);
	}
}
?>
