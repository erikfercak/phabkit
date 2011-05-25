<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class FormEventsTest extends Phabkit_TestCase
{
    protected $testValue = 'newvalue';

    protected function actualEvents()
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

    protected function expectedTypingEvents()
    {
        $out = '';
        for ($i = 0; $i < mb_strlen($this->testValue); $i++) {
            if ($out != '') {
                $out .= ' ';
            }
            $out .= 'keydown keypress keyup';
        }
        return $out;
    }

    public function testTriggersTextInputEvents()
    {
        $this->browser->findFirst("//input[@type='text']")->set($this->testValue);

        $this->assertEquals(
            'focus ' . $this->expectedTypingEvents() . ' change blur',
            $this->actualEvents()
        );
    }

    public function testTriggersTextareaEvents()
    {
        $this->browser->findFirst('//textarea')->set($this->testValue);

        $this->assertEquals(
            'focus ' . $this->expectedTypingEvents() . ' change blur',
            $this->actualEvents()
        );
    }

    public function testTriggersPasswordInputEvents()
    {
        $this->browser->findFirst("//input[@type='password']")->set($this->testValue);

        $this->assertEquals(
            'focus ' . $this->expectedTypingEvents() . ' change blur',
            $this->actualEvents()
        );
    }

    public function testTriggersRadioInputEvents()
    {
        $this->browser->findFirst("//input[@type='radio']")->set(TRUE);

        $this->assertEquals(
            'click',
            $this->actualEvents()
        );
    }

    public function testTriggersCheckBoxEvents()
    {
        $this->browser->findFirst("//input[@type='checkbox']")->set(TRUE);

        $this->assertEquals(
            'click',
            $this->actualEvents()
        );
    }
}
