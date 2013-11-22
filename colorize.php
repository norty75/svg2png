<?php
    $svg_src = $_REQUEST['svg_src'];
    if (isset($_REQUEST['color'])){
        $color = $_REQUEST['color'];
    } else {
        $color = "FF0000";
    }
    
    $f = file_get_contents($_REQUEST['dir'].$svg_src.".svg");
    if ($color != "original"){
        $f = str_replace("#FF0000", "#".$color, $f);
        $f = str_replace("#ff0000", "#".$color, $f);
    }
    echo $f;
?>