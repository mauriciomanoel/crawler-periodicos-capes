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
    //$cookie         = Util::getCookie($url);

    $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0";
    define('USER_AGENT', $user_agent);
    define('COOKIE', @$cookie);
    define('FILE', $file);

    $page = 1;
    echo "Page: " . $page . $break_line;
    $url = PeriodicoCapes::getUrl($page, $query_string);
        // var_dump($url); exit;
    PeriodicoCapes::progress($url, $file, $break_line);



    // // include('config.php');
    // include('functions.php');
    // function progress_capes($url) {
    //     $dom = new DOMDocument;
    //     $html = loadURL($url, COOKIE_CAPES, USER_AGENT);
    //     @$dom->loadHTML($html);
    //     $dom->preserveWhiteSpace = true;
    //     foreach ($dom->getElementsByTagName('a') as $node) {
    //         if ($node->hasAttribute( 'href' )) {
    //             while (@ ob_end_flush()); // end all output buffers if any
    //             if (strpos($node->getAttribute( 'href' ), 'basket.do') !== false) {
    //                 $urls = explode("?fn=", $node->getAttribute( 'href' ));
    //                 $url_action = "http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/basket.do?fn=" . $urls[1];
    //                 loadURL($url_action, COOKIE_CAPES, USER_AGENT_WINDOWS);
    //                 echo ' <a href="' . $url . '">' . $url . '</a><br>';
    //                 @ flush();
    //                 sleep(2);
    //             }
    //         }
    //     }
    //     sleep(rand(3,5));
    // }
    // echo "Page: 1 <br>";
    // $time = time() . '000';
    // $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=facet&fctN=facet_lang&fctV=eng&rfnGrp=2&rfnGrpCounter=2&frbg=&rfnGrpCounter=1&indx=1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&rfnIncGrp=1&rfnIncGrp=1&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&mode=Basic&vid=CAPES_V1&ct=facet&srt=rank&tab=default_tab&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . QUERY;
    // progress_capes($url);
    // echo "Page: 2 <br>";
    // $time = time() . '000';
    // $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=1&pageNumberComingFrom=1&frbg=&rfnGrpCounter=2&fn=search&indx=1&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&vid=CAPES_V1&fctV=eng&mode=Basic&ct=facet&rfnGrp=2&tab=default_tab&srt=rank&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . QUERY;
    // progress_capes($url);
    // for($page=3;$page<=52;$page++) {
    //     echo "Page: " . $page . "<br>";
    //     $time = time() . '000';        
    //     $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=' . ($page-2) . '1&pageNumberComingFrom=' . ($page-1) . '&frbg=&rfnGrpCounter=2&indx=' . ($page-2) . '1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&fctV=eng&mode=Basic&vid=CAPES_V1&ct=Next%20Page&rfnGrp=2&srt=rank&tab=default_tab&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . QUERY;
    //     progress_capes($url);
    // }
?>