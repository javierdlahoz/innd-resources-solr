<?php

namespace Resource\Entity\Date;

/**
 * @author jdelahoz1
 */
class DateEntity {

	/**
	 *
	 * @var string
	 */
	private $year;

	/**
	 *
	 * @var string
	 */
	private $month;

	/**
	 *
	 * @var string
	 */
	private $day;

	/**
	 *
	 * @param string $date
	 */
	function __construct($date = null){
		if($date != null){
			$this->setFromString($date);
		}
	}

	/**
	 *
	 * @return the string
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 *
	 * @param $year
	 */
	public function setYear($year) {
		$this->year = $year;
		return $this;
	}

	/**
	 *
	 * @return the string
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 *
	 * @param $month
	 */
	public function setMonth($month) {
		$this->month = $month;
		return $this;
	}

	/**
	 *
	 * @return the string
	 */
	public function getDay() {
		return $this->day;
	}

	/**
	 *
	 * @param $day
	 */
	public function setDay($day) {
		$this->day = $day;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function toString(){
		if(!empty($this->month) && !empty($this->day) && !empty($this->year)){
			return $this->getMonth()."-".$this->getDay()."-".$this->getYear();
		}
		else{
			return "";
		}
	}

	/**
	 *
	 * @param string $date
	 */
	public function setFromSolrDate($date){
		if($date != ""){
			$dateArray = explode("-", $date);
			$this->setYear($dateArray[0]);
			$this->setMonth($dateArray[1]);
			$dateWithOutTime = explode("T", $dateArray[2]);
			$this->setDay($dateWithOutTime[0]);
		}
	}

	/**
	 *
	 * @param string $date
	 */
	public function setFromString($date){
		if($date != ""){
			$dateArray = explode("-", $date);
			$this->setDay($dateArray[1]);
			$this->setMonth($dateArray[0]);
			$this->setYear($dateArray[2]);
		}
	}

	/**
	 *
	 * @return string
	 */
	public function toSolrDate(){
		return $this->getYear()."-".$this->getMonth()."-".$this->getDay()."T00:00:00Z";
	}

	/**
	 *
	 * @return string
	 */
	public function getFormattedDate(){
		$monthText = "";

		switch ($this->getMonth()){
			case "01":
				$monthText = "January";
				break;
			case "02":
				$monthText = "February";
				break;
			case "03":
				$monthText = "March";
				break;
			case "04":
				$monthText = "April";
				break;
			case "05":
				$monthText = "May";
				break;
			case "06":
				$monthText = "June";
				break;
			case "07":
				$monthText = "July";
				break;
			case "08":
				$monthText = "August";
				break;
			case "09":
				$monthText = "Septembre";
				break;
			case "10":
				$monthText = "October";
				break;
			case "11":
				$monthText = "November";
				break;
			case "12":
				$monthText = "December";
				break;
		}

		return $monthText." ".$this->getDay().", ".$this->getYear();
	}

	/**
	 *
	 * @return string
	 */
	public function getWPDate(){
	    return $this->getMonth()."-".$this->getDay()."-".$this->getYear();
	}
}