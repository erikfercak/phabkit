<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';

class MouseTest extends PHPUnit_Framework_TestCase
{
    private $browser;

    private $host = 'localhost';
    private $port = 3333;

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/mouse.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }

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
