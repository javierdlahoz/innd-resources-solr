<?php

namespace Resource\Facade;

class ResourceFacade
{

	/**
	 * 
	 * @param array $resources
	 * @return multitype:|multitype:multitype:NULL
	 */
	public static function formatResourcesToArray($resources){
		$formattedResources = array();
	
		if($resources == null){
			return array();
		}
	
		foreach ($resources as $resource){
			$formattedResources[] = array(
					"resource_id" => $resource->getId(),
					"resource_title" => $resource->getTitle()
					);
		}
	
		return $formattedResources;
	}
	
}