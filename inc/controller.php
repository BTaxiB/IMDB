<?php
//
require_once 'Database.php';
require 'Helper.php';
require 'Imdb.php';
require 'TopRated.php';

// Error Handling
ini_set("display_errors", 1);
ini_set('log_errors', 1);
ini_set("error_reporting", E_ALL);

// Load config (database credentials etc.)
$cfg = parse_ini_file('config.ini.php');


// Database init
$db = new Database($cfg);

// Creating instances
$imdb = new Imdb($db);
$top = new TopRated($db);
