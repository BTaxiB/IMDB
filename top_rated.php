<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraper</title>
</head>

<body>
    <div class="container-fluid">
        <h1>
            This will take some time...
        </h1>
    </div>
</body>

</html>

<?php
require_once('vendor/autoload.php');
require_once('inc/controller.php');

use Facebook\WebDriver\Remote\RemoteWebDriver as DRIVER;
use Facebook\WebDriver\WebDriverBy as LOCATE;
use Facebook\WebDriver\WebDriverKeys as PRESS;
use Facebook\WebDriver\WebDriverExpectedCondition as CONDITION;

$browser_type = "chrome";
$port = "850";
$host = "http://localhost:{$port}";

set_time_limit(2000);

if ($browser_type == 'firefox') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
}

if ($browser_type == 'chrome') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
}

$driver = DRIVER::create($host, $capabilities);

$driver->manage()->timeouts()->implicitlyWait = 10;

$driver->manage()->window()->maximize();

$driver->get('https://www.imdb.com/chart/top/?sort=rk,desc&mode=simple&page=1');

function open_link($driver, $target, $selector)
{
    switch ($selector) {
        case 'name':
            $element = $driver->findElement(Locate::name($target));
            break;

        case 'class':
            $element = $driver->findElement(Locate::className($target));
            break;

        case 'id':
            $element = $driver->findElement(Locate::id($target));
            break;

        case 'css':
            $element = $driver->findElement(Locate::cssSelector($target));
            break;

        default:
            # code...
            break;
    }

    $element->click();
}

function extract_movie($driver, $object)
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
    
    if ($max < 8.5) {

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

// $i = 1;
foreach ($movies as $m) {
    // $grade = $driver->findElement(Locate::cssSelector(".lister-list tr:nth-child({$i}) .imdbRating"));

    // $max = number_format(str_replace(",", ".", $grade->getAttribute("innerText")));

    // if ($grade->getAttribute("innerText") > $max) {
        array_push($links, $m->getAttribute("href"));
    // }
    // $i++;
    // $test = $driver->findElement(Locate::cssSelector(".ratingValue strong:nth-child"));
    // $max = number_format(str_replace(",", ".", $test->getAttribute("innerText")));
        // print_r($max);
}

foreach ($links as $l) {
    if ($driver->get($l)) {
        $driver->manage()->timeouts()->implicitlyWait = 10;

        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector('.article time')));

        extract_movie($driver, $top);

        $top->create();
    }

    if(!$l) {
        echo "<script>
            window.location.replace('http://localhost/imdb/modules/toprated/index.php');
        </script>";
    }
}
