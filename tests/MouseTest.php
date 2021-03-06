<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class MouseTest extends Phabkit_TestCase
{
    public function testClicksAnElement()
    {
        $this->markTestSkipped('Need better HTTP test server');
    }

    public function testFiresAMouseEvent()
    {
        $this->browser->findFirst("//*[@id='mouseup']")->trigger("mouseup");
        $this->assertNotEmpty(
            $this->browser->find("//*[@class='triggered']")
        );
    }

    public function testFiresANonMouseEvent()
    {
        $this->browser->findFirst("//*[@id='change']")->trigger("change");
        $this->assertNotEmpty(
            $this->browser->find("//*[@class='triggered']")
        );
    }

    public function testItFiresAChangeOnSelect()
    {
        $select = $this->browser->findFirst('//select');
        $this->assertEquals('1', $select->value());

        $option = $this->browser->findFirst("//option[@id='option-2']");
        $option->selectOption();
        $this->assertEquals('2', $select->value());

        $this->assertNotEmpty(
            $this->browser->findFirst('//select[@class="triggered"]')
        );
    }

    public function testIfFiresDragEvents()
    {
        $draggable = $this->browser->findFirst("//*[@id='mousedown']");
        $container = $this->browser->findFirst("//*[@id='mouseup']");
        $draggable->dragTo($container);

        $this->assertEquals(
            count($this->browser->find("//*[@class='triggered']")),
            1
        );
    }
}
