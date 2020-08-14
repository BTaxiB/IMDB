<?php
require_once('vendor/autoload.php');
require_once('inc/controller.php');

use Facebook\WebDriver\Remote\RemoteWebDriver as DRIVER;
use Facebook\WebDriver\WebDriverBy as LOCATE;
use Facebook\WebDriver\WebDriverKeys as PRESS;

$browser_type = 'chrome';
$host = 'http://localhost:850';


if ($browser_type == 'firefox') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
}

if ($browser_type == 'chrome') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
}



$driver = DRIVER::create($host, $capabilities);

$driver->manage()->timeouts()->implicitlyWait = 10;

$driver->manage()->window()->maximize();

function search($driver, $target, $text, $selector)
{
    switch ($selector) {
        case 'name':
            $search = $driver->findElement(Locate::name($target));
            break;

        case 'class':
            $search = $driver->findElement(Locate::className($target));
            break;

        case 'id':
            $search = $driver->findElement(Locate::id($target));
            break;

        case 'css':
            $search = $driver->findElement(Locate::cssSelector($target));
            break;

        default:
            # code...
            break;
    }

    $search->click();
    $driver->getKeyboard()->sendKeys($text);
    $driver->getKeyboard()->pressKey(PRESS::ENTER);

    return $search;
}


$driver->get('https://www.google.com/');

//google search for inception
search($driver, 'q', 'inception', 'name');

//opening target link(could search with regex)
$inc = $driver->findElement(Locate::cssSelector('.r:nth-child(1) a'));
$inc->click();

$time = $driver->findElement(Locate::tagName('time'));

$desc = $driver->findElement(Locate::className('summary_text'));

$trailer = $driver->findElement(Locate::className("slate_button"));

$grade = $driver->findElement(Locate::className("ratingValue"));

$thumb = $driver->findElement(Locate::cssSelector('.poster img'));

$writer = $driver->findElement(Locate::cssSelector('div.credit_summary_item:nth-child(3) a'));

$stars = $driver->findElements(Locate::cssSelector('div.credit_summary_item:nth-child(4) a'));

$genres = $driver->findElements(Locate::cssSelector('#titleStoryLine div:nth-of-type(4) a'));

//converting to string
//genres
$g_str = '';

foreach ($genres as $g) {
    $g_str .= $g->getText().PHP_EOL;
}

//stars
$s_str = '';

foreach ($stars as $s) {
    $s_str .= $s->getText().PHP_EOL;
}

//formating 
$s_format = str_replace(",See full cast & crew", ", ", $s_str);

//assigning values to Imdb class
$imdb->writer = $writer->getText();
$imdb->description = $desc->getText();
$imdb->duration = $time->getText();
$imdb->grade = $grade->getText();
$imdb->thumbnail = $thumb->getAttribute('src');
$imdb->trailer = $trailer->getAttribute('href');
$imdb->genre = $g_str;
$imdb->stars = $s_format;

// var_dump($g_str);
sleep(1);

//database insert
if ($imdb->create()) {
    $driver->executeScript("window.history.back();");
}

