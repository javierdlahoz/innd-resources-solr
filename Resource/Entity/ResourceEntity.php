<?php

namespace Resource\Entity;

require __DIR__.'/Multiselect/MultiselectEntity.php';

use Resource\Entity\Multiselect\MultiselectEntity;
use Solarium\Entity\AbstractEntity;
use Resource\Service\ResourceService;
use INUtils\Helper\TextHelper;
use INUtils\Entity\PostEntity;
use Resource\Entity\Date\DateEntity;
use Resource\Entity\Multiselect\Resource\Entity\Multiselect;
/**
 */
class ResourceEntity extends AbstractEntity
{

    const RESOURCE_TYPE = "resource_type_ss";
    const STAKEHOLDERS = "stakeholders_ss";
    const AREA_OF_FOCUS = "areas_of_focus_ss";

	/**
	 *
	 * @var string
	 */
	private $title;

	/**
	 *
	 * @var string
	 */
	private $description;

	/**
	 *
	 * @var array
	 */
	private $authors;

	/**
	 *
	 * @var array
	 */
	private $organizationName;

	/**
	 *
	 * @var array
	 */
	private $contributors;

	/**
	 *
	 * @var string
	 */
	private $date;

	/**
	 *
	 * @var string
	 */
	private $resourceType;

	/**
	 *
	 * @var string
	 */
	private $areasFocus;

	/**
	 *
	 * @var string
	 */
	private $skateholders;

	/**
	 *
	 * @var string
	 */
	private $resourceFormat;

	/**
	 *
	 * @var MultiselectEntity
	 */
	private $copyright;

	/**
	 *
	 * @var string
	 */
	private $url;

	/**
	 *
	 * @var string
	 */
	private $fileSize;

	/**
	 *
	 * @var PostEntity
	 */
	private $postEntity;


    /**
     * @return the $postEntity
     */
    public function getPostEntity()
    {
        if(!isset($this->postEntity)){
            $this->postEntity = new PostEntity($this->getId());
        }
        return $this->postEntity;
    }

    /**
     * @param \INUtils\Entity\PostEntity $postEntity
     */
    public function setPostEntity($postEntity)
    {
        $this->postEntity = $postEntity;
    }

     /**
	 *
	 * @param string $title
	 */
	public function setTitle($title){
		$this->setTitleS($title);
	}

	/**
	 * @return string
	 */
	public function getTitle(){
		return $this->getTitleS();
	}

	/**
	 *
	 * @param string $description
	 */
	public function setDescription($description){
		$this->setDescriptionS($description);
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->getDescriptionS();
	}

	/**
	 *
	 * @param MultiselectEntity $authors
	 */
	public function setAuthors($authors){
		$this->setAuthorSs($authors->getOptions());
	}

	/**
	 *
	 * @param MultiselectEntity $organizationName
	 */
	public function setOrganizationNames($organizationNames){
		$this->setOrganizationNameSs($organizationNames->getOptions());
	}

    /**
	 * @return string
	 */
	public function getOrganizationNames(){
		return $this->getOrganizationNameSs();
	}

 /**
	 *
	 * @return array
	 */
    public function getContributors() {
        $authors = $this->getAuthorSs();
        $organizationName = $this->getOrganizationNameSs();
        $contributors = array();

        for($i = 0; $i < count($authors); $i ++){
          $contributors[] = array(
              "author" => $authors[$i],
              "organizationName" => $organizationName[$i]
          );
        }

        return $contributors;
    }

    /**
     *
     * @return \Resource\Entity\Multiselect\MultiselectEntity
     */
    public function getAuthors(){
        $authors = new MultiselectEntity();
        $authors->setOptions($this->getAuthorSs());
        return $authors;
    }


	/**
	 *
	 * @return DateEntity
	 */
	public function getDate() {
		if(empty($this->date)){
		    $this->date = new DateEntity();
		    $this->date->setFromSolrDate($this->getDateDt());
		}
		return $this->date;
	}

	/**
	 *
	 * @param string $date
	 */
	public function setDate($date) {
	    $this->date = new DateEntity();
	    $this->date->setFromString($date);
		$this->setDateDt($this->date->toSolrDate());
	}

	/**
	 *
	 * @param MultiselectEntity $areas_focus
	 */
	public function setAreasOfFocus(MultiselectEntity $areas_focus) {
	    $this->setAreasOfFocusSs($areas_focus->getOptions());
	}

	/**
	 *
	 * @return MultiselectEntity
	 */
	public function getAreasOfFocus() {
        $areasOfFocus = new MultiselectEntity();
        $areasOfFocus->setOptions($this->getAreasOfFocusSs());

	    return $areasOfFocus;
	}

	/**
	 *
	 * @param MultiselectEntity $stakeholders
	 */
	public function setStakeholders(MultiselectEntity $stakeholders) {
	    $this->setStakeholdersSs($stakeholders->getOptions());
	}

	/**
	 *
	 * @return MultiselectEntity
	 */
	public function getStakeholders() {
	    $stakeholders = new MultiselectEntity();
	    $stakeholders->setOptions($this->getStakeholdersSs());

	    return $stakeholders;
	}


	/**
	 *
	 * @return the MultiselectEntity
	 */
	public function getType() {
	    $type = new MultiselectEntity();
	    $type->setOptions($this->getResourceTypeSs());

	    return $type;
	}

	/**
	 *
	 * @param MultiselectEntity $type
	 */
	public function setType(MultiselectEntity $type) {
	    $this->setResourceTypeSs($type->getOptions());
	}

	/**
	 *
	 * @param string $areasFocus
	 */
	public function setAreasFocus($areasFocus){
		$this->setAreasFocusS($areasFocus);
	}

    /**
	 * @return string
	 */
	public function getAreasFocus(){
		return $this->getAreasFocusS();
	}

	/**
	 *
	 * @param string $resourceFormat
	 */
	public function setResourceFormat($resourceFormat){
		$this->setResourceFormatS($resourceFormat);
	}

    /**
	 * @return string
	 */
	public function getResourceFormat(){
		return $this->getResourceFormatS();
	}

	/**
	 *
	 * @return \Resource\Entity\Multiselect\MultiselectEntity
	 */
	public function getCopyright() {
		$copyright = new MultiselectEntity();
		$copyright->setOptions($this->getCopyrightSs());

		return $copyright;
	}

	/**
	 *
	 * @param MultiselectEntity $copyrigt
	 */
	public function setCopyright(MultiselectEntity $copyright) {
		$this->setCopyrightSs($copyright->getOptions());
	}

	/**
	 *
	 * @return the string
	 */
	public function getUrl() {
		return $this->getUrlS();
	}

	/**
	 *
	 * @param $url
	 */
	public function setUrl($url) {
		$this->setUrlS($url);
	}

	/**
	 *
	 * @param string $fileSize
	 */
	public function setFileSize($fileSize){
		$this->setFileSizeS($fileSize);
	}

	/**
	 *
	 * @param string $fileUrl
	 */
	public function setFileUrl($fileUrl){
	    $this->setFileUrlS($fileUrl);
	}

    /**
     * @return string
     */
	public function getFileUrl(){
	   return $this->getFileUrlS();
	}

	/**
	 *
	 * @return number
	 */
	public function getFileSize(){
		return $this->getFileSizeS();
	}

	/**
	 *
	 * @param string $fileName
	 */
	public function setFileName($fileName){
	    $this->setFileNameS($fileName);
	}

	/**
	 *
	 * @return string
	 */
	public function getOldFileName(){
	    return $this->getFileNameS();
	}

    /**
     *
     * @return string
     */
	public function getFileName(){
	    return TextHelper::getFileNameFromUniqueId($this->getFileNameS());
	}

	/**
	 *
	 * @param string $status
	 */
	public function setStatus($status){
	    $this->setStatusS($status);
	}

	/**
	 *
	 */
	public function getStatus(){
	    return $this->getStatusS();
	}

	/**
	 *
	 * @param string $video
	 */
	public function setVideo($video){
	    $this->setVideoS($video);
	}

	/**
	 * @return string
	 */
	public function getVideo(){
	    return $this->getVideoS();
	}

	/**
	 *
	 * @param string $tags
	 */
	public function setTags($tags){
	    $this->setTagsS($tags);
	}

	/**
	 * @return string
	 */
	public function getTags(){
	    return $this->getTagsS();
	}

	/**
	 *
	 * @return array
	 */
	public function getAsArray(){

	    return array(
	        "title" => $this->getTitle(),
            "description" => $this->getDescription(),
	        "contributors" => $this->getContributors(),
	        "resourceType" => $this->getType()->getOptions(),
	        "areasOfFocus" => $this->getAreasOfFocus()->getOptions(),
	        "stakeholders" => $this->getStakeholders()->clearOptions(),
	        "format" => $this->getResourceFormat(),
	        "license" => $this->getCopyright()->getOptions(),
	        "authors" => $this->getAuthors()->toString(),
	        "file" => array(
                "name" => $this->getFileName(),
                "size" => $this->getFileSize(),
                "url"  => $this->getFileUrl()
	           ),
	        "tags" => $this->getPostEntity()->getTagsAsString(),
	        "url" => $this->getUrl(),
	        "date" => $this->getDate()->getFormattedDate(),
	        "permalink" => $this->getPostEntity()->getPermalink(),
	        "limitedDescription" => TextHelper::cropText($this->getDescription(), 300)
	    );
	}

}