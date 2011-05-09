<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';

class NestingTest extends PHPUnit_Framework_TestCase
{
    private $browser;

    private $host = 'localhost';
    private $port = 3334;

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/nesting.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }

    public function testEvaluatesNestedXpathExpressions()
    {
      $parent = $this->browser->findFirst("//*[@id='parent']");

      $texts = '';
      foreach ($parent->find("./*[@class='find']") as $child) {
          if ($texts != '') {
              $texts .= ' ';
          }
          $texts .= $child->text();
      }

      $this->assertEquals($texts, 'Expected');
    }
}
