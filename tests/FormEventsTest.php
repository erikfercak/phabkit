<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';

class FormEventsTest extends PHPUnit_Framework_TestCase
{
    private $browser;

    private $host = 'localhost';
    private $port = 3331;

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/events.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }

    protected function getEvents()
    {
        $out = '';
        foreach ($this->browser->find('//li') as $li) {
            if ($out != '') {
                $out .= ' ';
            }
            $out .= $li->text();
        }
        return $out;
    }

    public function testTriggersTextInputEvents()
    {
        $this->browser->findFirst("//input[@type='text']")->set('newvalue');

        $this->assertEquals(
            $this->getEvents(),
            'focus keydown keyup change blur'
        );
    }

    public function testTriggersTextareaEvents()
    {
        $this->browser->findFirst('//textarea')->set('newvalue');

        $this->assertEquals(
            $this->getEvents(),
            'focus keydown keyup change blur'
        );
    }

    public function testTriggersPasswordInputEvents()
    {
        $this->browser->findFirst("//input[@type='password']")->set('newvalue');

        $this->assertEquals(
            $this->getEvents(),
            'focus keydown keyup change blur'
        );
    }

    public function testTriggersRadioInputEvents()
    {
        $this->browser->findFirst("//input[@type='radio']")->set(TRUE);

        $this->assertEquals(
            $this->getEvents(),
            'click'
        );
    }

    public function testTriggersCheckBoxEvents()
    {
        $this->browser->findFirst("//input[@type='checkbox']")->set(TRUE);

        $this->assertEquals(
            $this->getEvents(),
            'click'
        );
    }
}
