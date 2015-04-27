<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require __DIR__.'/../../../autoload.php';

$configFile = __DIR__."/../../../../../config/solarium.global.php";

if (file_exists($configFile)){
    require($configFile);
}
else{
    require(__DIR__.'/config.dist.php');
}


function htmlHeader()
{
    echo '<html><head><title>Solarium examples</title></head><body>';
}

function htmlFooter()
{
    echo '</body></html>';
}
