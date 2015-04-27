<?php

namespace Resource\Controller;

use Resource\Entity\ResourceEntity;
use Resource\Entity\Multiselect\MultiselectEntity;
use INUtils\Controller\AbstractController;
use Resource\Helper\ResourceHelper;
use INUtils\Helper\TextHelper;
use Resource\Service\ResourceService;
use INUtils\Helper\FileHelper;
use INUtils\Entity\PostEntity;

class ResourceController extends AbstractController{

    /**
     *
     * @throws \Exception
     * @return boolean
     */
    public function save($postId)
    {
    	$post = get_post($postId);

    	if($post->post_type == ResourceHelper::RESOURCE_POST_TYPE && isset($_POST["authors"]))
    	{
	        $resourceEntity = new ResourceEntity();
	        $resourceEntityTmp = ResourceService::getSingleton()->findResourceById($postId);
	        $postEntity = new PostEntity($postId);

	        if(isset($_FILES) && $_FILES["file"]["name"] != ""){
	            $fileInfo = FileHelper::uploadFile("file");
	            $resourceEntity->setFileUrl($fileInfo["fileUrl"]);
	            $resourceEntity->setFileSize($fileInfo["fileSize"]);
	            $resourceEntity->setFileName($fileInfo["fileName"]);
	        }
	        elseif($resourceEntityTmp != null){
	            $resourceEntity->setFileUrl($resourceEntityTmp->getFileUrl());
	            $resourceEntity->setFileSize($resourceEntityTmp->getFileSize());
	            $resourceEntity->setFileName($resourceEntityTmp->getOldFileName());
	        }

			if($resourceEntityTmp != null){
	           	ResourceService::getSingleton()->delete($resourceEntityTmp, false);
	       	}

	       	$resourceEntity->setId($postId);
	        $resourceEntity->setTitle($post->post_title);
	        $resourceEntity->setDescription($post->post_content);
	        $resourceEntity->setStatus($post->post_status);
	        $resourceEntity->setVideo($this->getPost("video"));
	        $resourceEntity->setTags($postEntity->getTagsAsString());

	        $authors = new MultiselectEntity();
	        $institutionNames = new MultiselectEntity();

        	if(isset($_POST['authors'])){
		        for ($i = 0; $i < count($_POST['authors']); $i++) {
		            $authors->addOption($_POST['authors'][$i]);
		            $institutionNames->addOption($_POST['institution_name'][$i]);
		        }
        	}

	        $resourceEntity->setAuthors($authors);
	        $resourceEntity->setOrganizationNames($institutionNames);

	        if (isset($_POST ['date'])) {
	            $resourceEntity->setDate($_POST["date"]);
	        }

            if (isset($_POST['url'])) {
                $resourceEntity->setUrl($_POST['url']);
            }


	        /* Copyright options */
	        $license = new MultiselectEntity();
	        foreach (ResourceHelper::getCopyRight() as $optionKey => $optioValue) {
	            if (isset($_POST[$optionKey])) {
	                $license->addOption($_POST[$optionKey]);
	            }
	        }
	        $resourceEntity->setCopyright($license);

	        /* resource types */
	        $type = new MultiselectEntity();
	        foreach (ResourceHelper::getResourceTypes() as $optionKey => $optioValue) {
	            if (isset($_POST [$optionKey])) {
	                $type->addOption($_POST[$optionKey]);
	            }
	        }
	        $resourceEntity->setType($type);

	        /* areas of focus */
	        $areasOfFocus = new MultiselectEntity();
	        foreach (ResourceHelper::getAreasOfFocus() as $optionKey => $optioValue) {
	            if (isset($_POST [$optionKey])) {
	                $areasOfFocus->addOption($_POST[$optionKey]);
	            }
	        }
	        $resourceEntity->setAreasOfFocus($areasOfFocus);

	        /* stakeholders */
	        $stakeholders = new MultiselectEntity();
	        foreach (ResourceHelper::getStakeholders() as $optionKey => $optioValue) {
	            if (isset($_POST [$optionKey])) {
	                $stakeholders->addOption($_POST[$optionKey]);
	            }
	        }

	        $resourceEntity->setStakeholders($stakeholders);

	        /* resource formats */
            if (isset($_POST ["resource_format"])) {
                $format = $_POST["resource_format"];
            }
	        $resourceEntity->setResourceFormat($format);

	        try {
	            ResourceService::getSingleton()->save($resourceEntity);
	        }
	        catch (\Exception $e) {
	            throw new \Exception($e->getMessage());
	        }
    	}
    }

    /**
     *
     * @return array
     */
    public function searchAction(){

    	if($this->getPost("paged") != ""){
    		$paged = $this->getPost("paged");
    	}
    	else{
    		$paged = 1;
    	}

        $facetsArray = array(
        	"areas_of_focus" => ResourceEntity::AREA_OF_FOCUS,
            "stakeholders" => ResourceEntity::STAKEHOLDERS,
        	"resource_type_facets" => ResourceEntity::RESOURCE_TYPE
        );

    	$orderBy = array("score" => "DESC");
    	if($this->getPost("orderby") != ""){
        	$order = $this->getPost("orderby");
        	switch ($order){
        		case "title":
        			$orderBy = array("title_s" => "ASC");
        			break;
        		case "date":
        			$orderBy = array("date_dt" => "DESC");
        			break;
        	}
        }

        $filterTypes = array(
            ResourceEntity::AREA_OF_FOCUS => $this->getPost("areas-of-focus"),
            ResourceEntity::STAKEHOLDERS => $this->getPost("stakeholders"),
            ResourceEntity::RESOURCE_TYPE => $this->getPost("resource-types")
        );

        $resources = ResourceService::getSingleton()->findResourcesBy(
                $this->getQuery($this->getPost("query"), $this->getPost("audience"), $filterTypes),
        			$orderBy,
        			$paged,
        			$facetsArray,
                    true
        		);

        $count = ResourceService::getSingleton()->getQueryCount();
        $numberOfPages = ResourceService::getSingleton()->getNumberOfPages();
        $facets = ResourceService::getSingleton()->getFacetResults();
        $pages = $numberOfPages;

        return array(
            "resources" => $resources,
            "pages" => $pages,
            "count" => $count,
        	"facets" => ResourceHelper::formatFacetsAsArray($facets),
        	"page" => $paged
        );
    }

    /**
     *
     * @return multitype:string
     */
    public function areasoffocusAction(){
        $areasOfFocus = array();
        foreach(ResourceHelper::getAreasOfFocus() as $key => $value){
            array_push($areasOfFocus, $value);
        }

        return $areasOfFocus;
    }

    /**
     *
     * @return multitype:string
     */
    public function stakeholdersAction(){
        $stakeholders = array();
        foreach(ResourceHelper::getStakeholders() as $key => $value){
            array_push($stakeholders, $value);
        }

        return $stakeholders;
    }


    /**
     *
     * @return multitype:string
     */
    public function typesAction(){
        $resourceTypes = array();
        foreach(ResourceHelper::getResourceTypes() as $key => $value){
            array_push($resourceTypes, $value);
        }

        return $resourceTypes;
    }

    /**
     *
     * @return multitype:string
     */
    public function formatsAction(){
        $formats = array();
        foreach(ResourceHelper::getResourceFormats() as $key => $value){
            array_push($formats, $value);
        }

        return $formats;
    }

    /**
     *
     * @param string $expression
     * @param string $audience
     * @param array $filterTypes
     * @param string $subOptions
     * @return string
     */
    private function getQuery($expression, $audience = "", $filterTypes = null, $subOptions = null) {
        return self::constructQuery($expression, $audience, $filterTypes, $subOptions);
    }

    /**
     *
     * @param unknown $expression
     * @param string $audience
     * @param array $filterTypes
     * @return string
     */
    public static function constructQuery($expression, $audience = "", $filterTypes = null, $subOptions = null){

        $expressions[0] = $expression;

        $query = "(" . self::queryBuilder("title_s", $expressions, true);
        $query .= self::queryBuilder("description_s", $expressions);
        $query .= self::queryBuilder("tags_s", $expressions);
        $query .= self::queryBuilder("organization_name_ss", $expressions);
        $query .= self::queryBuilder("author_ss", $expressions);
        $query .= self::queryBuilder("url_s", $expressions);
        $query .= ")";

    	if ($filterTypes != null) {
    	    foreach($filterTypes as $filterKey => $filterValues){
    	        if($filterValues != "" && $filterValues != "*"){
    	            if(is_string($filterValues)){
    	                $query .= " AND ".$filterKey.":".TextHelper::formatSpaces($filterValues);
    	            }
    	            else{
    	                foreach($filterValues as $filterValue){
    	                    $query .= " AND ".$filterKey.":" . TextHelper::formatSpaces($filterValue);
    	                }
    	            }
    	        }
    	        else{
    	            $query .= " AND ".$filterKey.":*";
    	        }
    	    }
    	}

    	$query .= " AND status_s: publish";

    	return $query;
    }

    /**
     * it deletes the resource and its file
     */
    public function delete($postId)
    {
        $resource = ResourceService::getSingleton()->findResourceById($postId);
       	ResourceService::getSingleton()->delete($resource, false);
    }

    /**
     *
     * @param string $resourceId
     * @return Ambigous <NULL, \Resource\Service\multiple:>
     */
    public function getResourceById($resourceId) {
        $resource = $this->resourceService->findResourcesBy("id:" . $resourceId);
        return $resource[0];
    }

    /**
     *
     * @param multiple $resourceArray
     * @return multiple
     */
    public function getPublishedResources($resourceArray){
    	$resources = null;
    	if($resourceArray != null)
    	{
	    	foreach ($resourceArray as $resource ) {
				$post = get_post ( $resource->getId () );
				if (is_object ( $post ) && $post->post_status == "publish") {
					$resources [] = $resource;
				}
			}
		}
		return $resources;
	}

    /**
     *
     * @param  $field
     * @param  $expressions
     * @param  boolean $isFirst
     * @return
     */
    public static function queryBuilder($field, $expressions, $isFirst = false){
        $query = "";
        foreach ($expressions as $expression) {
            if($expression === ""){
                $expression = "*";
            }

            if($isFirst){
                $query = $field . ':*' . $expression . '* ';
                $isFirst = false;
            }
            else{
                $query .= "OR ". $field . ':*' . $expression . '* ';
            }
        }
        return $query;
    }
}