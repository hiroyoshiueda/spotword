<?php
/**
 * UtilDate
 */
class UtilDate {

	/**
	 * @var int
	 */
	private $timestamp = 0;

	/**
	 * UtilDate
	 * @param String $dateStr
	 * @return UtilDate
	 */
	function UtilDate($dateStr=null) {
		if ($dateStr === null) {
			$this->timestamp = time();
		} else {
			$this->timestamp = self::getTimestamp($dateStr);
		}
	}

	/**
	 * addDay
	 * @param int $day
	 */
	public function addDay($day=1) {
		//$this->timestamp += (86400 * $day);
		$date = getdate($this->timestamp);
		$this->timestamp = mktime(0, 0, 0, $date["mon"], $date["mday"]+$day, $date["year"]);
	}

	/**
	 * addMonth
	 * @param int $month
	 */
	public function addMonth($month=1) {
		$date = getdate($this->timestamp);
		$this->timestamp = mktime(0, 0, 0, $date["mon"]+$month, $date["mday"], $date["year"]);
	}

	/**
	 * addYear
	 * @param int $year
	 */
	public function addYear($year=1) {
		$date = getdate($this->timestamp);
		$this->timestamp = mktime(0, 0, 0, $date["mon"], $date["mday"], $date["year"]+$year);
	}

	/**
	 * addYmd
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 */
	public function addYmd($year, $month, $day) {
		$date = getdate($this->timestamp);
		$this->timestamp = mktime(0, 0, 0, $date["mon"]+$month, $date["mday"]+$day, $date["year"]+$year);
	}

	/**
	 * getDayOfWeek
	 * @return int
	 */
	public function getDayOfWeek() {
		$date = getdate($this->timestamp);
		return intval($date["wday"]) + 1;
	}

	/**
	 * getDayOfWeekJp
	 * @return String
	 */
	public function getDayOfWeekJp() {
		$days = array ("日", "月", "火", "水", "木", "金", "土");
		$day = $this->getDayOfWeek();
		return $days[$day];
	}

	/**
	 * toString
	 * @param String $format
	 * @return String
	 */
	public function toString($format="Y-m-d") {
		return self::format($this->timestamp, $format);
	}

	/**
	 * toTimestamp
	 * @return int
	 */
	public function toTimestamp() {
		return $this->timestamp;
	}

	/**
	 * getTimestamp
	 * @param String $dateStr
	 * @return int
	 */
	public static function getTimestamp($dateStr) {
		$time = strtotime($dateStr);
		if ($time == -1 || $time === false) {
			// strtotime() was not able to parse $string, use "now":
			$time = time();
		}
		return $time;
	}

	/**
	 * format
	 * @param int $time
	 * @param String $format
	 * @return String
	 */
	public static function format($time, $format="Y-m-d") {
		return date($format, $time);
	}

	/**
	 * getDateAddDay
	 * @param String $dateStr
	 * @param int $offset
	 * @return String
	 */
	public static function getDateAddDay($dateStr, $offset=1) {
		$date = new UtilDate($dateStr);
		$date->addDay($offset);
		return $date->toString();
	}

	/**
	 * getDateAddMonth
	 * @param String $dateStr
	 * @param int $offset
	 * @return String
	 */
	public static function getDateAddMonth($dateStr, $offset=1) {
		$date = new UtilDate($dateStr);
		$date->addMonth($offset);
		return $date->toString();
	}

	/**
	 * compare
	 * @param UtilDate $dateStr1
	 * @param UtilDate $dateStr2
	 * @return int
	 */
	public static function compare($dateStr1, $dateStr2) {
		if (is_object($dateStr1)) {
			$time1 = $dateStr1->toTimestamp();
		} else {
			$time1 = self::getTimestamp($dateStr1);
		}
		if (is_object($dateStr2)) {
			$time2 = $dateStr2->toTimestamp();
		} else {
			$time2 = self::getTimestamp($dateStr2);
		}
		// 負:> 0:= 正:<
		return ($time1 - $time2);
	}
}
?>