<?php

use Facebook\WebDriver\Remote\RemoteWebDriver as DRIVER;
use Facebook\WebDriver\WebDriverBy as LOCATE;
use Facebook\WebDriver\WebDriverKeys as PRESS;

if ($browser_type == 'firefox') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
}

if ($browser_type == 'chrome') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
}

$browser_type = 'chrome';

$host = 'http://localhost:850';

$driver = DRIVER::create($host, $capabilities);

$driver->manage()->timeouts()->implicitlyWait = 10;
