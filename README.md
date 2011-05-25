# Phabkit
PHP wrapper around [Thoughtbot's QtWebKit server](https://github.com/thoughtbot/capybara-webkit QtWebkit server). Tested on [version 0.4.0](https://github.com/thoughtbot/capybara-webkit/commit/e12198152dd8634c989afe947640c07f2ebd4549).

## Warning
This is still a prototype. Documentation is nonexistent.
Tested on MacOS X 10.6 and Ubuntu 11.04

## Requirements
- PHP 5.2 with --enable-sockets
- webkit_server from https://github.com/thoughtbot/capybara-webkit
  (Compile by running qmake && make qmake && make in /src dir)
- PHPUnit, netcat for running tests

## Usage
- run ./webkit_server
- see tests in /tests directory for usage
