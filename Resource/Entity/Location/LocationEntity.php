<?php

namespace Resource\Entity\Location;

class LocationEntity {
	
	/**
	 * 
	 * @var string
	 */
	private $longitude;
	
	/**
	 * 
	 * @var string
	 */
	private $latitude;

	/**
	 * 
	 * @var string
	 */
	private $place;
	
	function __construct($latitude = null, $longitude = null){
		if($latitude != null){
			$this->setLatitude($latitude);
			$this->setLongitude($longitude);
		}
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getLongitude() {
		return $this->longitude;
	}
	
	/**
	 *
	 * @param $longitude
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getLatitude() {
		return $this->latitude;
	}
	
	/**
	 *
	 * @param $latitude
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function toString(){
		return $this->getLatitude().", ".$this->getLongitude();
	}
	
	/**
	 * 
	 * @param string $location
	 * @throws \Exception
	 */
	public function setFromString($location){
		if(self::validateString($location)){
			$locationCor = explode(", ", $location);
			$this->setLatitude($locationCor[0]);
			$this->setLongitude($locationCor[1]);
		}
		else{
			throw new \Exception("The string is not a location");
		}
	}
	
	/**
	 * 
	 * @param string $location
	 * @return boolean
	 */
	private function validateString($location){
		$locationCor = explode(", ", $location);
		
		if(count($locationCor) == 2){		
			return true;
		}
		else{
			return false;
		}
	}
	

    /**
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     *
     * @param string $place the place
     * @return self
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }
}