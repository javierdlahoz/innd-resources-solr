<?php

use Resource\Controller\ResourceController;

function handleRequest()
{
	setRequest();
}

function handleRequestWithResults()
{
	return setRequest();
}

function setRequest()
{
	$resourceController = ResourceController::getSingleton();
	
	if(isset($_POST['action_type'])){
		$result = $resourceController->actionHandler($_POST);
		return $result;
	}
	
	if(isset($_GET['query'])){
		$result = $resourceController->actionHandlerGet($_GET);
		return $result;	
	}
	
	if(isset($_GET['expression'])){
		$_GET['action_type'] = ResourceController::SEARCH_ACTION;
		$result = $resourceController->actionHandler($_GET);
		return $result;
	}
	
}