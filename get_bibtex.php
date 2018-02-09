<?php
    set_time_limit(0);

    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });

    if (defined('STDIN')) {
        $break_line     = "\r\n";
        $page           = (int) $argv[1];
        $file_name      = trim($argv[2]);
        $query_string   = urlencode(trim($argv[2]));            
    } else {
        $break_line     = "<br>";
        $query_string   = urlencode(trim(@$_GET['query']));
        $file_name      = trim(@$_GET['query']);
        $page           = (int) @$_GET['page'];
    }

    if (empty($query_string)) {
        throw new Exception("Query String not found");
    } 
    if (empty($page)) {
        throw new Exception("Page not found");
    }     

    $file           = Util::slug(trim($file_name)) . ".bib";
    $url            = PeriodicoCapes::getUrl(0, $query_string);
    $cookie         = Util::getCookie($url);

    $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0";
    define('USER_AGENT', $user_agent);
    define('COOKIE', @$cookie);
    define('FILE', $file);
    define('BREACK_LINE', $break_line);

    $page = 1;
    echo "Page: " . $page . BREACK_LINE;
    $url = PeriodicoCapes::getUrl($page, $query_string);
    PeriodicoCapes::progress($url, $file);
?>