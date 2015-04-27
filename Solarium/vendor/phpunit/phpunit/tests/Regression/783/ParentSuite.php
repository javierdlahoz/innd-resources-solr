<?php
require_once '../modules/Solarium/vendor/phpunit/phpunit/tests/Regression/783/ChildSuite.php';

class ParentSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Parent');
        $suite->addTest(ChildSuite::suite());

        return $suite;
    }
}
