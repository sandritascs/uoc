<?php
function normalize ($str){
    
    $originals = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modifs = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $str = utf8_decode($str);
    $str = strtr($str, utf8_decode($originals), $modifs);
    $str = strtolower($str);
    
    return utf8_encode($str);
    
}



?>