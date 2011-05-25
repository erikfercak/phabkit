<?php

class Node
{
    protected $browser;
    public $native;

    public function __construct($browser, $native)
    {
        $this->browser = $browser;
        $this->native = $native;
    }

    public function text()
    {
        return $this->invoke('text');
    }

    public function attribute($attribute)
    {
        return $this->invoke('attribute', $attribute);
    }

    public function value()
    {
        if ($this->isMultiSelect()) {
            $options = $this->find('.//option');
            $selection = array();
            foreach ($options as $option) {
                if ($option->attribute('selected') == 'selected') {
                    $selection[] = $option->value();
                }
            }
            return $selection;
        } else {
            return $this->invoke('value');
        }
    }

    public function set($value)
    {
        return $this->invoke('set', $value);
    }

    public function selectOption()
    {
        return $this->invoke('selectOption');
    }

    public function unselectOption()
    {
        return $this->invoke('unselectOption');
    }

    public function click()
    {
        return $this->invoke('click');
    }

    public function dragTo($element)
    {
        return $this->invoke('dragTo');
    }

    public function tagName()
    {
        return $this->invoke('tagName');
    }

    public function isVisible()
    {
        return ($this->invoke('visible') == 'true');
    }

    public function trigger($event)
    {
        return $this->invoke('trigger', $event);
    }

    public function find($xpath)
    {
        return array_map(
            array($this->browser, 'createNode'),
            explode(',', $this->invoke('findWithin', $xpath))
        );
    }

    public function findFirst($xpath)
    {
        $elements = $this->find($xpath);
        return array_shift($elements);
    }

    public function invoke($name, $arg = NULL)
    {
        if ($arg) {
            return $this->browser->command(
                'Node', $name, $this->native, $arg
            );
        } else {
            return $this->browser->command(
                'Node', $name, $this->native
            );
        }
    }

    public function isChecked()
    {
        $result = $this->attribute('checked');
        return (($result == 'true') ? TRUE : FALSE);
    }

    public function isDisabled()
    {
        $result = $this->attribute('disabled');
        return (($result == 'true') ? TRUE : FALSE);
    }

    private function isMultiSelect()
    {
        return (
            $this->tagName() == 'select'
            && $this->attribute('multiple') == 'multiple'
        );
    }
}
