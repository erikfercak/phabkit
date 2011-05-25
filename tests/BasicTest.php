<?php

require_once dirname(__FILE__) . '/../src/Browser.php';
require_once dirname(__FILE__) . '/../src/Node.php';
require_once dirname(__FILE__) . '/Phabkit_TestCase.php';

class BasicTest extends Phabkit_TestCase
{
    public function testFindContentAfterLoadingUrl()
    {
        $this->assertNotEmpty($this->browser->find("//*[contains(., 'hello')]"));
    }

    public function testHasAnEmptyPageAfterReseting()
    {
        $this->browser->reset();
        $this->assertNotEmpty($this->browser->find("//*[contains(., 'hello')]"));
    }

    public function testRaisesAnErrorForAnInvalidXpathQuery()
    {
        try {
            $this->browser->find('complete bannanas');
        } catch (Exception $e) {
            if (preg_match('/xpath/i', $e->getMessage())) {
                return;
            }
        }
        $this->fail();
    }

    public function testReturnsAnAttributesValue()
    {
        $this->assertEquals(
            $this->browser->findFirst('//p')->attribute('id'),
            'greeting'
        );
    }

    public function testParsesXpathWithQuotes()
    {
        $this->assertNotEmpty(
            $this->browser->find('//*[contains(., "hello")]')
        );
    }

    public function testReturnsANodesText()
    {
        $this->assertEquals(
            $this->browser->findFirst('//p')->text(),
            'hello'
        );
    }

    public function testReturnsTheCurrentUrl()
    {
        $this->assertEquals(
            $this->browser->url(),
            'http://' . $this->host . ':' . $this->port . '/'
        );
    }

    public function testEscapesUrls()
    {
        $this->markTestSkipped('Need better HTTP test server');
    }

    public function testReturnsTheSourceCodeForThePage()
    {
        $this->assertEquals(
            preg_match('/<html>.*greeting/ms', $this->browser->source()),
            1
        );

        $this->assertEquals(
            $this->browser->source(),
            $this->browser->body()
        );
    }

    public function testEvaluatesJavascriptAndReturnsAString()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                "document.getElementById('greeting').innerText"
            ),
            'hello'
        );
    }

    public function testEvaluatesJavascriptAndReturnsAnArray()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                "['hello', 'world']"
            ),
            array('hello', 'world')
        );
    }

    public function testEvaluatesJavascriptAndReturnsAnInt()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                '123'
            ),
            123
        );
    }

    public function testEvaluatesJavascriptAndReturnsAFloat()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                '1.5'
            ),
            1.5
        );
    }

    public function testEvaluatesJavascriptAndReturnsNull()
    {
        $this->assertNull(
            $this->browser->evaluateScript(
                '(function () {})()'
            )
        );
    }

    public function testEvaluatesJavascriptAndReturnsAnObject()
    {
        $object = new StdClass();
        $object->one = 1;
        $this->assertEquals(
            $this->browser->evaluateScript(
                '({ "one" : 1 })'
            ),
            $object
        );
    }

    public function testEvaluatesJavascriptAndReturnsTrue()
    {
        $this->assertTrue(
            $this->browser->evaluateScript(
                'true'
            )
        );
    }

    public function testEvaluatesJavascriptAndReturnsFalse()
    {
        $this->assertFalse(
            $this->browser->evaluateScript(
                'false'
            )
        );
    }

    public function testEvaluatesJavascriptAndReturnAnEscapedString()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                "'\"'"
            ),
            '"'
        );
    }

    public function testEvaluatesJavascriptWithMultipleLines()
    {
        $this->assertEquals(
            $this->browser->evaluateScript(
                "[1,\n2]"
            ),
            array(1, 2)
        );
    }

    public function testExecutesJavascript()
    {
        $this->browser->executeScript(
            'document.getElementById("greeting").innerHTML = "yo"'
        );

        $this->assertNotEmpty(
            $this->browser->find("//p[contains(., 'yo')]")
        );
    }

    public function testRaisesAnErrorForFailingJavascript()
    {
        try {
            $this->browser->executeScript('invalid salad');
        } catch (Exception $e) {
            if (preg_match('/javascript/i', $e->getMessage())) {
                return;
            }
        }
        $this->fail();
    }

    public function testDoesntRaiseAnErrorForJavascriptThatDoesntReturnAnything()
    {
        try {
            $this->browser->executeScript('(function () { "returns nothing" })()');
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testReturnsANodesTagName()
    {
        $this->assertEquals(
            $this->browser->findFirst('//p')->tagName(),
            'p'
        );
    }

    public function testReadsDisabledProperty()
    {
        $this->assertTrue(
            $this->browser->findFirst('//input')->isDisabled()
        );
    }

    public function testFindsVisibleElements()
    {
        $this->assertTrue(
            $this->browser->findFirst('//p')->isVisible()
        );

        $this->assertFalse(
            $this->browser->findFirst("//*[@id='invisible']")->isVisible()
        );
    }
}
