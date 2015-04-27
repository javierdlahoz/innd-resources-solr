<?php

namespace Request\Helper;

require __DIR__ . "/../../../config/module_loader.php";
header('Content-Type: application/json');

use File\Service\FileService;
use File\Entity\FileEntity;
use File\Facade\FileFacade;
use Resource\Controller\ResourceController;
use Resource\Service\ResourceService;
use Resource\Facade\ResourceFacade;

class RestHelper {

    public static function getFilesByName($expression) {
        $query = "file_name_s:*" . $expression . "* OR ";
        $query .= "file_url_s:*" . $expression . "*";

        $files = FileService::getSingleton()->findFilesBy($query);
        return $files;
    }

    public static function getResourcesByQuery($expression) {
        $query = ResourceController::constructQuery($expression);
        $resources = ResourceService::getSingleton()->findResourcesBy($query);

        return $resources;
    }

}

if (isset($_POST['action_type'])) {

    switch ($_POST['action_type']) {

        case "get_files":
            $files = RestHelper::getFilesByName($_POST['query']);
            echo json_encode(FileFacade::formatFilesObjectsToArray($files));
            return true;
            break;

        case "get_resources":
            $resources = RestHelper::getResourcesByQuery($_POST['query']);
            echo json_encode(ResourceFacade::formatResourcesToArray($resources));
            return true;
            break;
    }
}

header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
echo json_encode(array('status' => 'failed'));
