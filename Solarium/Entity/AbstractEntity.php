<?php

namespace Solarium\Entity;

use Solarium\QueryType\Update\Query\Document\Document;
use Solarium\Resource\ResourceInterface;
use INUtils\Entity\PostEntity;

class AbstractEntity extends Document implements EntityInterface
{
	function __construct(array $fields = array(), array $boosts = array(), array $modifiers = array()){

		if(empty($fields['id'])){
			$fields['id'] = uniqid();
		}
		foreach ($fields as $key => $value){
			if(!is_array($fields[$key]))
				$fields[$key] = htmlspecialchars_decode($value);
		}
		parent::__construct($fields, $boosts, $modifiers);
	}

	 /*
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        $tmp = $this->getFields();

        if(isset($tmp[0]["id"]))
        {
            $tmp = $tmp[0];
        }

        switch (substr($method, 0, 3)) {
            case 'get' :
		    	//Try to find a property name like: firstName
 				$key = lcfirst(substr($method,3));
				$index = isset($args[0]) ? $args[0] : null;
		    	if(($index === null) && isset($this->fields[$key])) {
		    		return $tmp[$key];
		    	}
		    	//Try to find a property name like: first_name
                $key = $this->_underscore(substr($method,3));
				$index = isset($args[0]) ? $args[0] : null;
		    	if(($index === null) && isset($tmp[$key])) {
		    		return $tmp[$key];
		    	}
				//Try to find a matched id inside a collection
				if($index !== null) {
					$found = null;
					foreach($tmp[$key] as $doc) {
						if($doc->getId() == $index) {
							$found = $doc;
						}
					}
					return $found;
				}
				return "";
            case 'set' :
				// Try properties : firstName
                $key = $this->_underscore(substr($method,3));
				$result = isset($args[0]) ? $args[0] : null;
				$this->fields[$key] = $result;
				// Try properties : first_name
                $key = $this->_underscore(substr($method,3));
				$result = isset($args[0]) ? $args[0] : null;
				$this->fields[$key] = $result;
                return $result;
            case 'inc' :
                $key = $this->_underscore(substr($method,3));
				$result = isset($args[0]) ? $args[0] : null;
				$tmp[$key] = $tmp[$key] + 1;
                return $result;
        }
        throw new \Exception("Invalid method ".$method);
    }

    /**
     * Converts field names for setters and geters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        return $result;
    }

    /**
     * @see \Resource\ResourceInterface::toArray()
     */
    public function toArray()
    {
    	return $this->fields;
    }

    /**
     * @see \Solarium\Entity\EntityInterface::getPostEntity()
     */
    public function getPostEntity(){
        return new PostEntity($this->getId());
    }

}