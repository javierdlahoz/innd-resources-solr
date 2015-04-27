<?php

namespace Resource\Service;

use Solarium\Service\AbstractService;
use Solarium\Entity\AbstractEntity;
use Resource\Entity\ResourceEntity;
use SFUtils\Helper\TextHelper;

/**
 *
 * @author jdelahoz1
 *
 */
class ResourceService extends AbstractService {

	const RELATED_RESOURCES_LIMIT = 3;

    /**
     *
     * @param array $entities
     * @param string $resourcesAsArray
     * @return multitype:\Resource\Entity\ResourceEntity
     */
    public function toResourceEntities($entities, $resourcesAsArray = false) {
        $resourceEntities = array();
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                $resourceEntity = new ResourceEntity($entity->toArray());
                if($resourcesAsArray == true){
                    $resourceEntities[] = $resourceEntity->getAsArray();
                }
                else{
                    $resourceEntities[] = $resourceEntity;
                }
            }
        }
        else {
            $resourceEntity = new ResourceEntity($entities->toArray());
            if($resourcesAsArray == true){
                $resourceEntities[] = $resourceEntity->getAsArray();
            }
            else{
                $resourceEntities[] = $resourceEntity;
            }
        }
        return $resourceEntities;
    }

    /**
     *
     * @param string $expression
     * @param array $sort
     * @param number $pageNumber
     * @param string $facets
     * @param string $resourcesAsArray
     * @return \Resource\Service\multiple:
     */
    public function findResourcesBy($expression, array $sort = null, $pageNumber = 1, $facets = null, $resourcesAsArray = false) {
        $resultEntity = self::findBy($expression, null, $sort, $pageNumber, $facets);
        $this->expression = $expression;
        if ($resultEntity == null) {
            return null;
        }
        return self::toResourceEntities($resultEntity, $resourcesAsArray);
    }

    /**
     *
     * @param string $resourceId
     * @return ResourceEntity
     */
    public function findResourceById($resourceId) {
        $result = self::findResourcesBy("id:" . $resourceId);
        return $result[0];
    }

    /**
     *
     * @param string $resourceId
     * @return multitype: ResourceEntity
     */
    public function getRelatedResources($resourceId) {
        $resourceEntity = self::findResourceById($resourceId);
        $types = $resourceEntity->getType()->getOptions();
        $query = "-id:" . $resourceEntity->getId() . " AND (";
        $isFirst = true;
        foreach ($types as $type) {
            if ($isFirst) {
                $query .= "type_ss:" . TextHelper::formatSpaces($type) . "";
                $isFirst = false;
            } else {
                $query .= " OR type_ss:" . TextHelper::formatSpaces($type) . "";
            }
        }
        if($types == null){
        	$query .= "type_ss:*";
        }

        $query .= ")";

        $results = self::findResourcesBy($query, array('date_dt' => 'desc'));
        if ($results == null) {
            return array();
        }
        return array_slice($results, 0, self::RELATED_RESOURCES_LIMIT);
    }

    /**
     *
     * @return \Resource\Service\multiple:
     */
    public function getAllResources(){
        $resultEntity = self::findBy("type_ss:*");
        return self::toResourceEntities($resultEntity);
    }

}
