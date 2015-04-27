<?php

require_once __DIR__.'/../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require(__DIR__."/../../config/module_loader.php");


$GLOBALS['config'] = $config;

use Solarium\Entity\AbstractEntity;
use Solarium\Service\AbstractService;
/**
 * ServiceTest test case.
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    const ID = 1234;
    const NAME = "pepe";
    const TYPE = "pdf";
    const URL = "http://ss-dev.com";

    /**
     *
     * @var ServiceTest
     */
    private $ServiceTest;
    
    /**
     * 
     * @var AbstractService
     */
    private $testService;
    
    /**
     * 
     * @var AbstractResource
     */
    private $abstractEntity;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->testService = AbstractService::getSingleton($GLOBALS["config"]);
        $resource["id"] = self::ID;
        
        $this->abstractEntity = new AbstractEntity($resource);
        $this->abstractEntity->setName(self::NAME);
        $this->abstractEntity->setTypeS(self::TYPE);
        $this->abstractEntity->setUrlS(self::URL);
    }
    
    public function testSaveEntity()
    {   
        $this->testService->save($this->abstractEntity);
        $resultEntity = $this->testService->findBy("id:".self::ID);
        
        $this->assertEquals(self::NAME, $resultEntity->getName());
        $this->assertEquals(self::ID, $resultEntity->getId());
        $this->assertEquals(self::TYPE, $resultEntity->getTypeS());
        $this->assertEquals(self::URL, $resultEntity->getUrlS());
    }
    
    public function testDeleteEntity()
    {   
        $this->testService->delete($this->abstractEntity);
        $resultEntity = $this->testService->findOneBy("id:".self::ID);
        
        $this->assertNull($resultEntity);
    }
}