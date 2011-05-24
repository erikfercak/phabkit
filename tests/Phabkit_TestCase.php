<?php


class Phabkit_TestCase extends PHPUnit_Framework_TestCase
{
    protected $browser;

    protected $host = 'localhost';
    protected $port = 3330;

    protected function getHtmlFilename()
    {
        return strtolower(substr(get_class($this), 0, -4));
    }

    public function setUp()
    {
        proc_open(
            'nc -l ' . $this->port . ' > /dev/null < '
            . dirname(__FILE__) . '/html/' . $this->getHtmlFilename() . '.html &',
            array(), $foo
        );
        usleep(50000);
        $this->browser = new Browser();
        $this->browser->visit('http://' . $this->host . ':' . $this->port . '/');
    }
}
