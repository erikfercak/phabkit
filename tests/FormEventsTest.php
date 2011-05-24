<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class FormEventsTest extends Phabkit_TestCase
{
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
