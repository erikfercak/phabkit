<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class NestingTest extends Phabkit_TestCase
{
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
