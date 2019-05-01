<?php

class PeriodicoCapes {
    private static $URL = 'https://rnp-primo.hosted.exlibrisgroup.com/';

    public static function getUrl($page, $query) 
    {
        $url = "";
        $time = time() . '000';
        if ($page == 1) {            
            $url = self::$URL . 'primo_library/libweb/action/search.do?ct=facet&fctN=facet_lang&fctV=eng&rfnGrp=2&rfnGrpCounter=2&frbg=&rfnGrpCounter=1&indx=1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&rfnIncGrp=1&rfnIncGrp=1&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&mode=Basic&vid=CAPES_V1&ct=facet&srt=rank&tab=default_tab&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        } else if ($page == 2) {
            $url = self::$URL . 'primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=1&pageNumberComingFrom=1&frbg=&rfnGrpCounter=2&fn=search&indx=1&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&vid=CAPES_V1&fctV=eng&mode=Basic&ct=facet&rfnGrp=2&tab=default_tab&srt=rank&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        } else {    
            $url = self::$URL . 'primo_library/libweb/action/search.do?ct=Next+Page&pag=nxt&indx=' . ($page-2) . '1&pageNumberComingFrom=' . ($page-1) . '&frbg=&rfnGrpCounter=2&indx=' . ($page-2) . '1&fn=search&mulIncFctN=facet_rtype&mulIncFctN=facet_rtype&dscnt=0&scp.scps=scope%3A(%22CAPES%22)%2CEbscoLocalCAPES%2Cprimo_central_multiple_fe&rfnIncGrp=1&rfnIncGrp=1&fctV=eng&mode=Basic&vid=CAPES_V1&ct=Next%20Page&rfnGrp=2&srt=rank&tab=default_tab&fctN=facet_lang&dum=true&fctIncV=newspaper_articles&fctIncV=articles&dstmp=' . $time . '&vl(freeText0)=' . $query;
        }

        return $url;
    }

    public static function start($page, $query_string, $url, $file) {
        echo "Page: " . $page . BREAK_LINE;
        $url = self::getUrl($page, $query_string);
        self::progress($url, $file);
    }

    public static function progress($url, $file) {
        
        $html = Util::loadURL($url, COOKIE, USER_AGENT);
        
        // Check Google Captcha
        if ( strpos($html, "gs_captcha_cb()") !== false || strpos($html, "sending automated queries") !== false ) {
            echo "Captha detected" . BREAK_LINE; exit;
        }

        // $classname = "EXLResult EXLResultMediaTYPE";
        $classname = "EXLResultTitle";
        $htmlTitles = Util::getHTMLFromClass($html, $classname);
        // var_dump($htmlTitles); exit;

        $classname = "EXLResultRecordId";
        $docs = Util::getAttributeFromClass($html, $classname, "name");
        
        $bibtex_new = "";
        foreach($docs as $key => $doc) {
            $title = array();
            if (!empty( $htmlTitles[$key] )) {
                $htmlTitle  = $htmlTitles[$key];
                $title     = self::getTitleAndUrlFromHTML($htmlTitle);
            }
            
            Util::showMessage($title["title"]);
            $bibtex      = self::getBibtex($doc);
            if (empty($bibtex) || trim($bibtex{0}) != "@") {                    
                Util::showMessage("It was not possible download bibtex file.");
                sleep(rand(2,4)); // rand between 2 and 4 seconds
                continue;
            }

            if (!empty($title["url_article"])) {
                unset($title["title"]);
                $bibtex_new .= Util::add_fields_bibtex($bibtex, $title);
            } else {
                $bibtex_new .= $bibtex;
            }
            
            Util::showMessage("Download bibtex file OK.");
            Util::showMessage("");
            sleep(rand(2,4)); // rand between 2 and 4 seconds
        }

        if (!empty($bibtex_new)) {
            $oldContent = @file_get_contents($file);
            $newContent = $oldContent . $bibtex_new;
            file_put_contents($file, $newContent);
            Util::showMessage("File $file saved successfully.");
            Util::showMessage("");
        }
    }

    function getTitleAndUrlFromHTML($html) {
        $retorno    = array("url_article"=>"", "title"=> "");
        $classname  ="EXLResultTitle";
        $values     = Util::getHTMLFromClass($html, $classname);
        $url        = trim(Util::getURLFromHTML($values[0]));
        $title      = trim(strip_tags($values[0]));

        if (!empty($url)&& !empty($title)) {
            $retorno["url_article"] = $url;
        }
        if (!empty($title)) {
            $retorno["title"] = $title;
        }

        return $retorno;
    }
        
    private static function getBibtex($doc) {
        $fields = array("Button"=>"OK", "encode"=>"UTF-8");
        $url = self::$URL . "primo_library/libweb/action/PushToAction.do?pushToType=BibTeXPushTo&fromBasket=true&docs=" . $doc;
        $bibtex = Util::loadURL($url, COOKIE, USER_AGENT, $fields);
        $bibtex = strip_tags($bibtex); // remove html tags 
        return $bibtex;        
    }
}
    
?>
