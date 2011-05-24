<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class PopupTest extends Phabkit_TestCase
{
    public function testDoesntCrashFromAlerts()
    {
        $this->assertEquals(
            $this->browser->findFirst('//p')->text(),
            'success'
        );
    }
}
