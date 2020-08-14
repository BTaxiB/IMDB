<?php
require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\RemoteWebDriver as driver;
use Facebook\WebDriver\WebDriverBy as LOCATE;
use Facebook\WebDriver\WebDriverKeys as PRESS;
use Facebook\WebDriver\WebDriverExpectedCondition as CONDITION;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Basic Configuration
 */
$browser_type = "chrome";
$port = "850";
$host = "http://localhost:{$port}";


if ($browser_type == 'firefox') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
}

if ($browser_type == 'chrome') {
    $capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
}

$driver = driver::create($host, $capabilities);
$driver->manage()->timeouts()->implicitlyWait = 10;

/**
 * Searching pornhub for anal videos
 */
$driver->get('https://www.pornhub.com');
$search = $driver->findElement(LOCATE::id('searchInput'));
$search->click();
$driver->getKeyboard()->sendKeys('anal');
$driver->getKeyboard()->pressKey(PRESS::ENTER);

//Opening file required for printing results
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("data.csv", "w");

//Columns
fputcsv($output, ['Vid name', 'Vid URL', 'Uploader Name', 'Likes/Dislikes', 'Categories', '# of comments']);

/**
 * Url extraction, collect_data embed in logic
 */
function collect_links($driver, $csv)
{
    $array = $driver->findElements(Locate::cssSelector("div.phimage a"));
    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector("div.phimage a:nth-child(2)")));
    $data = [];
    foreach ($array as $v) {
        array_push($data, $v->getAttribute("href"));
    }

    foreach ($data as $d) {
        if ($d !== 'javascript:void(0)') {
            if ($driver->get($d)) {
                collect_data($driver, $csv);
            }
        }
    }
    $data = [];
}

/**
 * Get string between two points in string
 */
function get_string_between($string, $start = "", $end = "")
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);

    if ($ini == 0) return '';

    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;

    return substr($string, $ini, $len);
}

/**
 * Extracting all data required from page and printing it in data.csv file
 */
function collect_data($driver, $csv)
{
    $array = [
        'title' => '',
        'link' => '',
        'user' => '',
        'like' => '',
        'categories' => '',
        'comment' => ''
    ];
    $link = $driver->getCurrentURL();
    $array['link'] = $link;

    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector(".title-container h1")));
    $title = $driver->findElement(Locate::cssSelector(".title-container h1"))->getText();
    $array['title'] = $title;

    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector(".video-info-row .usernameWrap")));
    $user = $driver->findElement(Locate::cssSelector(".video-info-row .usernameWrap"))->getText();
    $array['user'] = $user;

    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector(".votes-count-container .votesUp")));
    $like = $driver->findElement(Locate::cssSelector(".votes-count-container .votesUp"))->getText();
    $dislike = $driver->findElement(Locate::cssSelector(".votes-count-container .votesDown"))->getText();
    $array['like'] = $like . '/' . $dislike;

    $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector("#cmtWrapper h2")));
    $com = $driver->findElement(Locate::cssSelector("#cmtWrapper h2 span"))->getText();
    $array['comment'] = get_string_between($com, "(", ")");

    fputcsv($csv, $array);
}
$driver->get("https://www.pornhub.com/video/search?search=anal&page=1");
for ($i = 1; $i < 25; $i++) {
        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector(".pagination3 .page_next")));
        $p = $i + 1;
        if(!collect_links($driver, $output)){
            $driver->get("https://www.pornhub.com/video/search?search=anal&page={$p}");
        }

        if($i==25) {
            exit;
        }
        $driver->wait(10)->until(Condition::presenceOfAllElementsLocatedBy(Locate::cssSelector(".pagination3 .page_next")));
}
