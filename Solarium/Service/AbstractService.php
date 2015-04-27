<?php

namespace Solarium\Service;

use Solarium\Entity\AbstractEntity;
use Solarium\Client;
use Solarium\Entity\EntityInterface;
use INUtils\Singleton\AbstractSingleton;

//error_reporting(0);

abstract class AbstractService extends AbstractSingleton{

    const RESPONSE_OK = "HTTP/1.1 200 OK";
    const ASC = "asc";
    const DESC = "desc";
    const SUGGESTER_COUNT = 3;
    const ITEMS_PER_PAGE = 10;


    protected $client;
    private $update;
    public static $service;
	public $config;
	private $facetResults;

    /**
     *
     * @var number
     */
    private $queryCount;

    function __construct() {
        $config = $GLOBALS["solariumConfig"];
        $this->config = $config;
        $this->client = new Client($config);
        $this->update = $this->client->createUpdate();
        $this->init();
    }

    public function init() {

    }

    /**
     *
     * @param EntityInterface $resource
     */
    public function save(EntityInterface $resource) {
        $this->update->addDocument($resource);
        $this->update->addCommit();

        self::validateResponse($this->client->update($this->update));
    }

    /**
     *
     * @param EntityInterface $resource
     * @param boolean $isDeletingFile
     */
    public function delete(EntityInterface $resource = null, $isDeletingFile = true) {
        if($resource!=null){
            if ($isDeletingFile == true) {
                self::deleteFile($resource->getUrlS());
            }

            $this->update->addDeleteQuery("id:" . $resource->getId());
            $this->update->addCommit();


            self::validateResponse($this->client->update($this->update));
        }
    }

    /**
     *
     * @param string $url
     */
    public function deleteFile($url) {
        $filePath = __DIR__ . "/../Service/../". $url;
        try {
            unlink($filePath);
        } catch (\Exception $e) {

        }
    }

    /**
     *
     * @param $result
     * @throws \Exception
     */
    private function validateResponse($result) {
        $headerResponse = $result->getResponse()->getHeaders();

        if ($headerResponse[0] != self::RESPONSE_OK) {
            var_dump($headerResponse[0]);
            throw new \Exception($headerResponse[0]);
        }
    }

    /**
     *
     * @param $expression
     * @param array $fields
     * @param array $sort
     * @return Ambigous <NULL, \Solarium\Entity\AbstractEntity, multitype:\Solarium\Entity\AbstractEntity >
     */
    public function findBy($expression, $fields = null, array $sort = null, $pageNumber = 1, $facets = null) {
        $client = $this->client;
        $query = $this->client->createQuery($client::QUERY_SELECT);
        $this->facetResults = null;

        if ($fields != null) {
            $fields[] = "*";
            $query->setFields($fields);
        }
        else{
            $query->setFields(array("*", "score"));
        }

        if ($sort != null) {
            $query = self::addSort($query, $sort);
        }

        $query->setQuery($expression);

        if($facets != null){
        	foreach ($facets as $facetKey => $facetValue){
        		$facetSet = $query->getFacetSet();
        		$facetSet->createFacetField($facetKey)->setField($facetValue);

        		$resultset = $client->select($query);
        		$this->facetResults[$facetKey] = $resultset->getFacetSet()->getFacet($facetKey);
        	}
        }

        $endItem = self::ITEMS_PER_PAGE;
        $startItem = ($pageNumber - 1) * self::ITEMS_PER_PAGE;

        $query->setStart($startItem)->setRows($endItem);

        $resultset = $client->execute($query);
        $this->queryCount = $resultset->getNumFound();
        return self::formatAsEntities(json_decode($resultset->getResponse()->getBody(), true));
    }

    /**
     *
     * @param $expression
     * @param string $fields
     * @param array $sort
     * @return Ambigous <NULL, \Solarium\Entity\AbstractEntity, multitype:\Solarium\Entity\AbstractEntity >
     */
    public function findOneBy($expression, $fields = null, array $sort = null) {
        $client = $this->client;
        $query = $this->client->createQuery($client::QUERY_SELECT);
        $query->setQuery($expression);
        $query->setStart(2)->setRows(1);

        if ($fields != null) {
            $query->setFields($fields);
        }
        if ($sort != null) {
            $query = self::addSort($query, $sort);
        }

        $resultset = $client->execute($query);
        return self::formatAsEntities(json_decode($resultset->getResponse()->getBody(), true));
    }

    /**
     *
     * @param $results
     * @return NULL|\Solarium\Entity\AbstractEntity|multitype:\Solarium\Entity\AbstractEntity
     */
    private function formatAsEntities($results) {
        $entities = array();
        $results = $results['response']['docs'];

        if (count($results) == 0) {
            return null;
        } elseif (count($results) == 1) {
            return new AbstractEntity($results);
        } else {
            foreach ($results as $unformattedEntity) {
                $entity = new AbstractEntity($unformattedEntity);
                $entities[] = $entity;
            }
            return $entities;
        }
    }

    /**
     *
     * @param $query
     * @param array $sort
     * @return
     */
    private function addSort($query, array $sort) {
        foreach ($sort as $key => $value) {
            $query->addSort($key, $value);
        }
        return $query;
    }

    /**
     *
     * @return number
     */
    public function getNumberOfPages() {
        return ceil($this->queryCount / self::ITEMS_PER_PAGE);
    }

    /**
     *
     * @return the number
     */
    public function getQueryCount() {
        return $this->queryCount;
    }

    /**
     *
     * @return \Solarium\QueryType\Select\Result\mixed
     */
    public function getFacetResults(){
    	return $this->facetResults;
    }

}
