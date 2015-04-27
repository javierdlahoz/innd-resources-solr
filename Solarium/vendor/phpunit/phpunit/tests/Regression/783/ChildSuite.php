<?php
require_once '../modules/Solarium/vendor/phpunit/phpunit/tests/Regression/783/OneTest.php';
require_once '../modules/Solarium/vendor/phpunit/phpunit/tests/Regression/783/TwoTest.php';

class ChildSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Child');
        $suite->addTestSuite('OneTest');
        $suite->addTestSuite('TwoTest');

        return $suite;
    }
}
