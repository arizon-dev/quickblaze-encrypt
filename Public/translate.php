<?php
function translate($q, $sl){
    $config = json_decode(file_get_contents("./.version", true), true);
    if($config["LANGUAGE"] == "auto"){
        $tl = getBrowserLang();
    } else{
        $tl = $config["LANGUAGE"];
    }
    $res= file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
    $res=json_decode($res);
    return $res[0][0][0];
}
function getBrowserLang(){
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    return $lang;
}
echo translate($_GET["text"], "en");
echo "<Br>";
echo getBrowserLang();