<?php

class Browser
{
    protected $socket;

    public function __construct($host = '127.0.0.1', $port = 9200)
    {
        $this->socket  = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(!socket_connect($this->socket, $host, $port)) {
            throw new Exception(
                'Failed to connect: '
                . socket_strerror(socket_last_error($this->socket))
            );
        }
    }

    public function visit($url)
    {
        return $this->command('Visit', $url);
    }

    public function find($xpath)
    {
        return array_map(
            array($this, 'createNode'),
            explode(',', $this->command('Find', $xpath))
        );
    }

    public function findFirst($xpath)
    {
        $elements = $this->find($xpath);
        return array_shift($elements);
    }

    public function createNode($native)
    {
        if ($native != '') {
            return new Node($this, $native);
        }
    }

    public function reset()
    {
        return $this->command('Reset');
    }

    public function source()
    {
        return $this->command('Source');
    }

    public function body()
    {
        return $this->source();
    }

    public function url($url = NULL)
    {
        if ($url) {
            return $this->command('Url', $url);
        } else {
            return $this->command('Url');
        }
    }

    public function frameFocus($frameIdOrIndex = NULL)
    {
      if (is_numeric($frameIdOrIndex)) {
          return $this->command('FrameFocus', '', (string) $frameIdOrIndex);
      } else if ($frameIdOrIndex) {
          return $this->command('FrameFocus', $frameIdOrIndex);
      } else {
          return $this->command('FrameFocus');
      }
    }

    public function evaluateScript($script)
    {
      $json = $this->command('Evaluate', $script);
      return json_decode($json);
    }

    public function executeScript($script)
    {
        return $this->command('Execute', $script);
    }

    public function command()
    {
        $args = func_get_args();
        if (count($args) == 0) {
            return;
        }

        $command = array_shift($args);
        //echo "<<" . $command . '\n';
        socket_write($this->socket, $command . "\n");
        //echo "\n";
        //echo "<<" . count($args) . '\n';
        socket_write($this->socket, count($args) . "\n");
        //echo "\n";

        foreach ($args as $arg) {
            //echo "<<" . strlen($arg) . '\n';
            socket_write($this->socket, strlen($arg) . "\n");
            //echo "\n";
            //echo "<<" . $arg;
            socket_write($this->socket, $arg);
            //echo "\n";
        }

        $this->check();
        return $this->readResponse();
    }

    protected function check()
    {
        $res = trim(socket_read($this->socket, 1024, PHP_NORMAL_READ));
        //echo ">>$res\n";
        if ($res != 'ok') {
            $errorMsg = $this->readResponse();
            throw new Exception($errorMsg);
        }
    }

    protected function readResponse()
    {
        $len = (int) trim(socket_read($this->socket, 1024, PHP_NORMAL_READ));
        //echo ">>$len\n";
        if ($len > 0) {
            $response = trim(socket_read($this->socket, $len));
            //echo ">>$response\n";
            return $response;
        } else {
            return '';
        }
    }
}
