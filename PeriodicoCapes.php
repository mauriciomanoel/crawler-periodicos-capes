<?php

class PeriodicoCapes {

    public static function getUrl($page, $query) 
    {
        $url = "";
        $time = time() . '000';
        if ($page == 1) {            
            $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=facet&fctN=facet_lang&fctV=eng&rfnGrp=2&rfnGrpCounter=2&frbg=&rfnGrpCounter=1&indx=1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&rfnIncGrp=1&rfnIncGrp=1&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&mode=Basic&vid=CAPES_V1&ct=facet&srt=rank&tab=default_tab&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        } else if ($page == 2) {
            $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=1&pageNumberComingFrom=1&frbg=&rfnGrpCounter=2&fn=search&indx=1&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&vid=CAPES_V1&fctV=eng&mode=Basic&ct=facet&rfnGrp=2&tab=default_tab&srt=rank&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        } else {    
            $url = 'http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=' . ($page-2) . '1&pageNumberComingFrom=' . ($page-1) . '&frbg=&rfnGrpCounter=2&indx=' . ($page-2) . '1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&fctV=eng&mode=Basic&vid=CAPES_V1&ct=Next%20Page&rfnGrp=2&srt=rank&tab=default_tab&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        }

        return $url;
    }

    public static function progress($url, $file, $break_line) {
        // $parameters["referer"]  = $url;
        // $parameters["host"]     = "scholar.google.com.br";
        // $html = Util::loadURL($url, COOKIE, USER_AGENT);
        $html = file_get_contents('base.html');
        
        // // Check Google Captcha
        // if ( strpos($html, "gs_captcha_cb()") !== false || strpos($html, "sending automated queries") !== false ) {
        //     echo "Captha detected" . $break_line; exit;
        // }

        $classname = "EXLResult EXLResultMediaTYPE";
        $values = Util::getHTMLFromClass($html, $classname);

        // $classname = "EXLResultRecordId";
        // $values = Util::getAttributeFromClass($html, $classname, "name");
        
        var_dump($values); exit;
        $bibtex_new = "";
        foreach($values as $value) {

            $bibtex_new .= self::getBibtex($value);

            echo "<pre>"; var_dump($bibtex_new); exit;
            // $data = get_data_google_scholar($value);
            // while (@ ob_end_flush()); // end all output buffers if any
            //     echo $data["title"] . $break_line;
            //     $url_action = "https://scholar.google.com.br/scholar?q=info:" . $data["data_cid"] . ":scholar.google.com/&output=cite&scirp=0&hl=en";
            //     echo $url_action . $break_line . $break_line;
            //     unset($data["title"]);
            //     unset($data["data_cid"]);
            //     $bibtex     = save_data_bibtex($url_action);
            //     if (!empty($bibtex)) 
            //     {
            //         $bibtex_new .= add_fields_bibtex($bibtex, $data);
            //     }
            //     sleep(rand(4,8)); // rand between 4 and 8 seconds
            // @ flush();

        }

        if (!empty($bibtex_new)) {
            $oldContent = @file_get_contents($file);
            $newContent = $oldContent . $bibtex_new;
            file_put_contents($file, $newContent);
        }
    }

    function getCitedFromHTML($html) {

        preg_match("'<a href=\"\/scholar\?cites(.*?)>(Cited by|Citado por) (.*?)</a>'si", $html, $match);
        if (!empty(@$match[3])) {
            return $match[3];
        }
        return "";
    }
    
    private static function getBibtex($doc) {
        $fields = array("Button"=>"OK", "encode"=>"UTF-8");
        // $url = "http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/PushToAction.do?pushToType=BibTeXPushTo&fromBasket=true&docs=" . $doc;
        $url = "http://rnp-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/PushToAction.do?indx=1&doc=" . $doc . "&recId=" . $doc . "&docs=" . $doc . "&pushToType=BibTeXPushTo&fromEshelf=false";
        return Util::loadURL($url, COOKIE, USER_AGENT, $fields);
    }


    // function getDOM($value) 
    // {
    //     libxml_use_internal_errors(true) && libxml_clear_errors(); // for html5
    //     $dom = new DOMDocument('1.0', 'UTF-8');
    //     $dom->loadHTML(mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8'));
    //     $dom->preserveWhiteSpace = true;
        
    //     return $dom;
    // }

    // function getDataCID($html) {

    //     preg_match_all('/data-cid="([^"]+)"/', $html, $values, PREG_PATTERN_ORDER);

    //     if (!empty($values[1])) {
    //         return $values[1];
    //     }
    //     return array();
    // }

    // function save_data_bibtex($url) {
    //     $parameters = array();
    //     $content    = "";
    //     $parameters["host"] = "scholar.google.com.br";        
    //     $html = loadURL($url, COOKIE, USER_AGENT, array(), $parameters);

    //     $parameters["host"] = "scholar.googleusercontent.com";
    //     $parameters["referer"] = $url;

    //     $dom = getDOM($html);
    //     foreach ($dom->getElementsByTagName('div') as $node) {
    //         if ($node->hasAttribute( 'id' )) {
    //             if ($node->getAttribute( 'id' ) == "gs_citi") {
    //                 $child = $node->firstChild;
    //                 $urlBibtex = trim($child->getAttribute( 'href' ));
    //                 $content .=  loadURL($urlBibtex, COOKIE, USER_AGENT, array(), $parameters);
    //                 break;
    //             }
    //         }
    //     }
    //     return $content;
    // }

    // function progress_google($url, $file, $break_line) {
    //     $parameters["referer"]  = $url;
    //     $parameters["host"]     = "scholar.google.com.br";
    //     $html = loadURL($url, COOKIE, USER_AGENT, array(), $parameters["referer"]);
        
    //     // Check Google Captcha
    //     if ( strpos($html, "gs_captcha_cb()") !== false || strpos($html, "sending automated queries") !== false ) {
    //         echo "Captha detected" . $break_line; exit;
    //     }

    //     $classname="gs_r gs_or gs_scl";
    //     $values = getHTMLFromClass($html, $classname);
        
    //     $bibtex_new = "";
    //     foreach($values as $value) {

    //         $data = get_data_google_scholar($value);
    //         while (@ ob_end_flush()); // end all output buffers if any
    //             echo $data["title"] . $break_line;
    //             $url_action = "https://scholar.google.com.br/scholar?q=info:" . $data["data_cid"] . ":scholar.google.com/&output=cite&scirp=0&hl=en";
    //             echo $url_action . $break_line . $break_line;
    //             unset($data["title"]);
    //             unset($data["data_cid"]);
    //             $bibtex     = save_data_bibtex($url_action);
    //             if (!empty($bibtex)) 
    //             {
    //                 $bibtex_new .= add_fields_bibtex($bibtex, $data);
    //             }
    //             sleep(rand(4,8)); // rand between 4 and 8 seconds
    //         @ flush();

    //     }

    //     if (!empty($bibtex_new)) {
    //         $oldContent = @file_get_contents($file);
    //         $newContent = $oldContent . $bibtex_new;
    //         file_put_contents($file, $newContent);
    //     }
    // }

    // function add_fields_bibtex($bibtex, $data) 
    // {
    //     $bibtex = trim($bibtex);
    //     $string = "";
    //     if (getDelimiter($bibtex) == "{") {
    //         foreach($data as $key => $value) {
    //             $string .= "  " . $key . "={" . $value . "}," . "\n";
    //         }
    //         $string = rtrim(trim($string), ",");
    //         $string .= "\n";
    //     }

    //     $bibtex = trim(substr($bibtex, 0, -1));        
    //     if ( substr($bibtex, strlen($bibtex)-1) == ",") {
    //         $bibtex .= "\n";
    //     } else {
    //         $bibtex .= ",\n";
    //     }
    //     $bibtex .= "  " . $string;
    //     $bibtex .= "}\n";

    //     return $bibtex;
    // }

    // function get_data_google_scholar($value) {

    //     $data_cid               = @getDataCID($value)[0];
    //     $html_pdf_article       = @arrayToString(getHTMLFromClass($value, "gs_or_ggsm"));
    //     $pdf_article            = @getURLFromHTML($html_pdf_article);
    //     $html_link_article      = @arrayToString(getHTMLFromClass($value, "gs_rt"));
    //     $link_article           = @getURLFromHTML($html_link_article);
    //     $html_options_article   = @arrayToString(getHTMLFromClass($value, "gs_fl"));
    //     $title_article          = trim(preg_replace("/\[(.*?)\]/i", "", strip_tags($html_link_article))); // remove [*]
    //     $cited_by               = @getCitedFromHTML($html_options_article);

    //     return array("title"=>$title_article, "data_cid"=> $data_cid, "pdf_file"=>$pdf_article, "link_google"=>$link_article, "cited_by"=>$cited_by);
    // }



    // function getURLFromHTML($html) {
    //     preg_match_all('/href="([^"]+)"/', $html, $arr, PREG_PATTERN_ORDER);
    //     if (!empty($arr[1])) {
    //         return $arr[1][0];
    //     }
    //     return "";
    // }

    // function getCitedFromHTML($html) {

    //     preg_match("'<a href=\"\/scholar\?cites(.*?)>(Cited by|Citado por) (.*?)</a>'si", $html, $match);
    //     if (!empty(@$match[3])) {
    //         return $match[3];
    //     }
    //     return "";
    // }

    // function arrayToString($value) {
    //     return implode(" ", $value);
    // }

	// function getDelimiter($string)
	// {
    //     $string = trim($string);
    //     $string = str_replace(array(" ","\n"), "", $string);
    //     $position = strpos($string, "=");
    //     return substr($string, ($position+1), 1);
	// }
        
}
    
?>
