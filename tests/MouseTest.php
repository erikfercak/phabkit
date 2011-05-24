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
