<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';

class FormTest extends PHPUnit_Framework_TestCase
{
    private $browser;

    private $host = 'localhost';
    private $port = 3332;

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/form.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }

    protected function initializeOptionSelectors()
    {
        $this->monkeyOption   = $this->browser->findFirst("//option[@id='select-option-monkey']");
        $this->capybaraOption = $this->browser->findFirst("//option[@id='select-option-capybara']");
        $this->animalSelect   = $this->browser->findFirst("//select[@name='animal']");
        $this->appleOption    = $this->browser->findFirst("//option[@id='topping-apple']");
        $this->bananaOption   = $this->browser->findFirst("//option[@id='topping-banana']");
        $this->cherryOption   = $this->browser->findFirst("//option[@id='topping-cherry']");
        $this->toppingsSelect = $this->browser->findFirst("//select[@name='toppings']");
    }

    protected function initializeCheckboxes()
    {
        $this->checkedBox = $this->browser->findFirst("//input[@name='checkedbox']");
        $this->uncheckedBox = $this->browser->findFirst("//input[@name='uncheckedbox']");
    }

    public function testReturnsATextareasValue()
    {
        $this->assertEquals(
            $this->browser->findFirst('//textarea')->value(),
            'what a wonderful area for text'
        );
    }

    public function testReturnsATextInputsValue()
    {
        $this->assertEquals(
            $this->browser->findFirst('//input')->value(),
            'bar'
        );
    }

    public function testReturnsASelectsValue()
    {
        $this->assertEquals(
            $this->browser->findFirst('//select')->value(),
            'Capybara'
        );
    }

    public function testSetsASelectsValue()
    {
        $select = $this->browser->findFirst('//select');
        $select->set('Monkey');

        $this->assertEquals(
            $select->value(),
            'Monkey'
        );
    }

    public function testSetsATextareasValue()
    {
        $textarea = $this->browser->findFirst('//textarea');
        $textarea->set('King kong');

        $this->assertEquals(
            $textarea->value(),
            'King kong'
        );
    }

    public function testSelectsAnOption()
    {
        $this->initializeOptionSelectors();

        $this->assertEquals(
            $this->animalSelect->value(),
            'Capybara'
        );

        $this->monkeyOption->selectOption();

        $this->assertEquals(
            $this->animalSelect->value(),
            'Monkey'
        );
    }

    public function testUnselectsAnOptionInAMultiSelect()
    {
        $this->initializeOptionSelectors();

        $this->assertEquals(
            $this->toppingsSelect->value(),
            array('Apple', 'Banana', 'Cherry')
        );

        $this->appleOption->unselectOption();

        $this->assertEquals(
            $this->toppingsSelect->value(),
            array('Banana', 'Cherry')
        );
    }

    public function testReselectsAnOptionInMultiSelect()
    {
        $this->initializeOptionSelectors();

        $this->appleOption->unselectOption();
        $this->bananaOption->unselectOption();
        $this->cherryOption->unselectOption();

        $this->assertEquals(
            $this->toppingsSelect->value(),
            array()
        );

        $this->appleOption->selectOption();
        $this->bananaOption->selectOption();
        $this->cherryOption->selectOption();

        $this->assertEquals(
            $this->toppingsSelect->value(),
            array('Apple', 'Banana', 'Cherry')
        );
    }

    public function testKnowsACheckedBoxIsChecked()
    {
        $this->initializeCheckboxes();

        $this->assertTrue(
            $this->checkedBox->isChecked()
        );
    }

    public function testKnowsAnUncheckedBoxIsUnchecked()
    {
        $this->initializeCheckboxes();

        $this->assertFalse(
            $this->uncheckedBox->isChecked()
        );
    }

    public function testChecksAnUncheckedBox()
    {
        $this->initializeCheckboxes();

        $this->uncheckedBox->set('true');

        $this->assertTrue(
            $this->uncheckedBox->isChecked()
        );
    }

    public function testUnchecksACheckedBox()
    {
        $this->initializeCheckboxes();

        $this->checkedBox->set('false');

        $this->assertFalse(
            $this->checkedBox->isChecked()
        );
    }

    public function testLeavesACheckedBoxChecked()
    {
        $this->initializeCheckboxes();

        $this->checkedBox->set('true');

        $this->assertTrue(
            $this->checkedBox->isChecked()
        );
    }

    public function testLeavesAnUncheckedBoxUnchecked()
    {
        $this->initializeCheckboxes();

        $this->uncheckedBox->set('false');

        $this->assertFalse(
            $this->uncheckedBox->isChecked()
        );
    }
}
