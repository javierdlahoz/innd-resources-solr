<?php
namespace Resource\Helper;

use INUtils\Helper\PostHelper;
use Resource\Controller\ResourceController;

class ResourceHelper
{
    const RESOURCE_POST_TYPE = "resource";
    const RESOURCE_SINGULAR = "Resource";
    const RESOURCE_PLURAL = "Resources";

    private $resourceController;

    function __construct(){
        $this->resourceController = ResourceController::getSingleton();

        PostHelper::createPostType(
            self::RESOURCE_POST_TYPE,
            self::RESOURCE_SINGULAR,
            self::RESOURCE_PLURAL
        );

        add_action('add_meta_boxes_resource', array(&$this, 'addMetaBoxesForResources'));
        add_action('save_post', array(&$this->resourceController, 'save'));
        add_action('delete_post', array(&$this->resourceController,'delete') );
    }

    /**
     *
     * @return multitype:string
     */
    public static function getResourceTypes(){
        return array(
            "concept_papers_and_publications" => "Concept Papers and Publications",
            "evaluation_reports" => "Evaluation Reports",
            "guides_handbooks_and_tip_sheets" => "Guides, Handbooks, and Tip Sheets",
            "instruments" => "Instruments",
            "seminars_and_presentations" => "Seminars and Presentations"
        );
    }

    /**
     *
     * @return multitype:string
     */
    public static function getAreasOfFocus(){
        return array(
            "evaluation_capacity_building" => "Evaluation Capacity Building",
            "inclusiveness_and_equity" => "Inclusiveness and Equity",
            "sustainable_communities" => "Sustainable Communities",
            "systems_oriented_evaluation" => "Systems-Oriented Evaluation"
        );
    }

    /**
     *
     * @return multitype:string
     */
    public static function getStakeholders(){
        return array(
            "business_and_organization_leaders" => "Business and Organization Leaders",
            "evaluators" => "Evaluators",
            "funders" => "Funders",
            "policymakers" => "Policymakers",
            "general_public" => "General Public"
        );
    }

    /**
     *
     * @return multitype:string
     */
    public static function getResourceFormats(){
        return array(
            "audio" => "Audio",
            "image" => "Image",
            "excel" => "Excel/Spreadsheet",
            "pdf" => "PDF",
            "presentation" => "Presentation/Powerpoint",
            "video" => "Video",
            "web_page" => "Web page",
            "word" => "Word/Text"
        );
    }

    /**
     *
     * @return multitype:string
     */
    public static function getCopyRight(){
        return array(
            "available_for_purchase" => "Available for purchase",
            "available_by_subscription" => "Available by subscription",
            "free_access_re_use" => "Free access/re-use",
            "free_access_with_registration" => "Free access with registration",
            "limited_free_access" => "Limited free access"
        );
    }

    public function addMetaBoxesForResources(){
        add_meta_box('more_info', 'Resource Info',
            array(&$this, 'getMoreInfoFormView'),
            self::RESOURCE_POST_TYPE, 'advanced'
        );
    }

    public function getMoreInfoFormView(){
        include __DIR__."/../views/admin/edit-form.phtml";
    }

    /**
     *
     * @param array $facets
     * @return multitype
     */
    public static function formatFacetsAsArray($facets){
        $facetArray = array();
        foreach ($facets as $key => $facetEntity){
            if($facetEntity instanceof \Solarium\QueryType\Select\Result\Facet\Field){
                $facetArray[$key] = $facetEntity->getValues();
            }
        }
        return $facetArray;
    }

}
