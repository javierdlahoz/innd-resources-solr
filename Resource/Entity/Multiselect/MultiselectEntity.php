<?php

namespace Resource\Entity\Multiselect;

class MultiselectEntity
{

	/**
	 *
	 * @var array
	 */
	private $options;

	/**
	 *
	 * @param array $options
	 */
	function __construct($options = null){
		if($options == null){
			$this->options = array();
		}
		else{
			$this->setOptions($options);
		}
	}

	/**
	 *
	 * @return the array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 *
	 * @param array $options
	 */
	public function setOptions($options) {
		if(is_array($options)){
			$this->options = $options;
		}
	}

	/**
	 *
	 * @param string $option
	 */
	public function addOption($option){
		$this->options = array_merge($this->options, array($option));
	}

	/**
	 * clears the options array
	 */
	public function clearOptions(){
		$this->options = array();
	}

	/**
	 *
	 * @param string $text
	 * @return boolean
	 */
	public function isTextInOptions($text){
		$optionsArray = $this->getOptions();
		foreach ($optionsArray as $option){
			if($option == $text){
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @return number
	 */
	public function getLength(){
		return count($this->getOptions());
	}

	/**
	 *
	 * @return string
	 */
	public function toString(){
	    $toString = "";
	    $isFirst = true;
	    foreach($this->getOptions() as $option){
	        if($isFirst){
	            $toString .= $option;
	            $isFirst = false;
	        }
	        else{
	            $toString .= ", ".$option;
	        }
	    }

	    return $toString;
	}
}