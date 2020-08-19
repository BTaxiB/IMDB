<?php
require_once('vendor/autoload.php');
require_once('inc/controller.php');

use Facebook\WebDriver\Remote\RemoteWebDriver as DRIVER;
use Facebook\WebDriver\WebDriverBy as LOCATE;
use Facebook\WebDriver\WebDriverExpectedCondition as CONDITION;

$browser_type = "chrome";
$host = "http://localhost";
$port = "850";
$serve = "{$host}:{$port}";
$go_back = "window.location.replace('http://localhost/imdb/modules/toprated/index.php');";

set_time_limit(2000);

switch ($browser_type) {
    case 'firefox':
        $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
        break;

    case 'chrome':
        $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
        break;

    default:
        # code...
        break;
}

$driver = DRIVER::create($serve, $capabilities);

$driver->manage()->timeouts()->implicitlyWait = 10;

$driver->manage()->window()->maximize();

$driver->get('https://www.imdb.com/chart/top/?sort=rk,desc&mode=simple&page=1');

function extract_movie($driver, $object, $grade)
{
    $driver->manage()->timeouts()->implicitlyWait = 10;

    $time = $driver->findElement(Locate::cssSelector('.article time'));

    $desc = $driver->findElement(Locate::className('summary_text'));

    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::className('summary_text')));

    $trailer = $driver->findElements(Locate::className("slate_button"));

    $num = count($trailer);
    
    if ($num > 0) {
        foreach ($trailer as $t) {
            $object->trailer = $t->getAttribute('href');
        }
    }
    
    $test = $driver->findElement(Locate::cssSelector(".ratingValue strong:nth-child(1)"));
    $max = number_format(str_replace(",", ".", $test->getText()), 1);
    
    if ($max < $grade) {

        $grade = $driver->findElement(Locate::className("ratingValue"));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::className('ratingValue')));

        $thumb = $driver->findElement(Locate::cssSelector('.poster img'));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('.poster img')));

        $writer = $driver->findElement(Locate::cssSelector('div.credit_summary_item:nth-child(3) a'));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('div.credit_summary_item:nth-child(3) a')));

        $stars = $driver->findElements(Locate::cssSelector('div.credit_summary_item:nth-child(4) a'));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('div.credit_summary_item:nth-child(4) a')));

        $genres = $driver->findElements(Locate::cssSelector('#titleStoryLine div:nth-of-type(4) a'));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('#titleStoryLine div:nth-of-type(4) a')));

        $title = $driver->findElement(Locate::cssSelector("#ratingWidget p:nth-child(2) strong"));

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('#ratingWidget p:nth-child(2) strong')));

        //converting to string
        //genres
        $g_str = '';

        foreach ($genres as $g) {
            $g_str .= $g->getText() . PHP_EOL;
        }

        //stars
        $s_str = '';

        foreach ($stars as $s) {
            $s_str .= $s->getText() . PHP_EOL;
        }

        //formating 
        $s_format = str_replace(",See full cast & crew", " ", $s_str);


        $object->title = $title->getText();
        $object->writer = $writer->getText();
        $object->description = $desc->getText();
        $object->duration = $time->getText();
        $object->grade = $grade->getText();
        $object->thumbnail = $thumb->getAttribute('src');
        $object->genre = $g_str;
        $object->stars = $s_format;
    }
}

$movies = $driver->findElements(Locate::cssSelector(".lister-list .titleColumn a"));
$links = [];

foreach ($movies as $m) {
        array_push($links, $m->getAttribute("href"));
}

foreach ($links as $l) {
    if ($driver->get($l)) {
        $driver->manage()->timeouts()->implicitlyWait = 10;

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('.article time')));

        extract_movie($driver, $top, 8.5);

        $top->create();
    }
    
    if(!$l) {
        $driver->executeScript($go_back);
    }
}
