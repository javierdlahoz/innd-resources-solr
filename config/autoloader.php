<?php

spl_autoload_register('autoloaderModules');

require __DIR__."/../../../in-utils/config/module_loader.php";
require __DIR__."/solrconfig.php";

function autoloaderModules($class){
    $initPath = __DIR__."/../";

    if(strpos($class, '\\') !== false && strpos($class, 'INUtils') === false){
        $classExploded = explode("\\", $class);

        $classFileToAdd = $initPath;
        $isFirst = true;
        foreach($classExploded as $pathPart){
            if($isFirst){
                $classFileToAdd .= $pathPart;
            }
            else{
                $classFileToAdd .= "/".$pathPart;
            }
            $isFirst = false;
        }
        $classFileToAdd .= ".php";
        if(file_exists($classFileToAdd))
        {
            require $classFileToAdd;
        }
        else{
            throw new \Exception("The class ".$class." couldn't be found");
        }
    }
}

$solariumPath = __DIR__."/../Solarium/";

require $solariumPath."vendor/solarium/solarium/examples/init.php";
require $solariumPath."Entity/EntityInterface.php";
require $solariumPath."Entity/AbstractEntity.php";
require $solariumPath."Service/AbstractService.php";