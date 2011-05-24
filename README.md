# Phabkit
PHP wrapper around https://github.com/thoughtbot/capybara-webkit QtWebkit server.

## Warning
This is still a prototype. Documentation is nonexistent.
Tested on MacOS X and Ubuntu 11.04

## Requirements
- PHP 5.2 with --enable-sockets
- webkit_server from https://github.com/thoughtbot/capybara-webkit
  (Compile by running qmake && make qmake && make in /src dir)
- PHPUnit, netcat for running tests

## Usage
- run ./webkit_server
- see tests in /tests directory for usage
