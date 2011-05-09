<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';

class PopupTest extends PHPUnit_Framework_TestCase
{
    private $browser;

    private $host = 'localhost';
    private $port = 3335;

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/popup.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }

    public function tearDown()
    {
    }

    public function testDoesntCrashFromAlerts()
    {
        $this->assertEquals(
            $this->browser->findFirst('//p')->text(),
            'success'
        );
    }
}
