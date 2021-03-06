<?php

if(!defined('PHPUnit_MAIN_METHOD')) {
  define('PHPUnit_MAIN_METHOD', 'Web_AllTests::main');
}

require_once 'TAssetManagerTest.php';
require_once 'TCacheHttpSessionTest.php';
require_once 'THttpCookieCollectionTest.php';
require_once 'THttpCookieTest.php';
require_once 'THttpRequestTest.php';
require_once 'THttpResponseTest.php';
require_once 'THttpSessionTest.php';
require_once 'THttpUtilityTest.php';
require_once 'TUriTest.php';

require_once 'UI/AllTests.php';

class Web_AllTests {
  public static function main() {
    PHPUnit_TextUI_TestRunner::run(self::suite());
  }

  public static function suite() {
    $suite = new PHPUnit_Framework_TestSuite('System.Web');

	$suite->addTestSuite('TAssetManagerTest');
	$suite->addTestSuite('TCacheHttpSessionTest');
	$suite->addTestSuite('THttpCookieCollectionTest');
	$suite->addTestSuite('THttpCookieTest');
    $suite->addTestSuite('THttpRequestTest');
	$suite->addTestSuite('THttpResponseTest');
	$suite->addTestSuite('THttpSessionTest');
	$suite->addTestSuite('THttpUtilityTest');
	$suite->addTestSuite('TUriTest');

	$suite->addTest(Web_UI_AllTests::suite());

    return $suite;
  }
}

if(PHPUnit_MAIN_METHOD == 'Web_AllTests::main') {
  Web_AllTests::main();
}
