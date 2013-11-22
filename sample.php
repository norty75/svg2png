<html><body>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script> 
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script> 

<?php
    $icons = array();
    if (isset($_REQUEST['root'])) {
        $root = "../".$_REQUEST['root']."/";
    } else {
        $root = "../svg_images/";
    }
    
    if (isset($_REQUEST['svg'])) {
        $icons[] = $_REQUEST['svg'];
    } else {
        $files = scandir($root);
        foreach ($files as $f ) {
            if (strlen($f)> 4 && substr($f, -4) == ".svg") {
                $icons[] = substr($f, 0, -4);
            }
        }
    }
    //print_r($icons);
    if (isset($_REQUEST['size'])) {
        $size = $_REQUEST['size']; 
    } else {
        $size = "72";
    }
    if (isset($_REQUEST['color'])) {
        $color = $_REQUEST['color']; 
    } else {
        $color = "";
    }
?>



<script type="text/javascript">
window.onload = function() {
    document.write('<canvas id="canvas" width="<?php echo $size?>px" height="<?php echo $size?>px"></canvas>');

    document.write('<table><tr>');
    try {
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
<?php
    $c = 0;
    foreach ($icons as $i) {?>
        ctx.drawSvg('colorize.php?dir=<?php echo $root ?>&color=<?php echo $color ?>&svg_src=<?php echo $i ?>',0,0,<?php echo $size ?>, <?php echo $size ?>);//, { scaleWidth: 72, scaleHeight: 72,ignoreMouse: true });
        var img_<?php echo $i ?> = canvas.toDataURL("image/png");

        document.write('<td><img id="img_<?php echo $i ?>" src="'+img_<?php echo $i ?>+'"/><br><?php echo $i ?>.png<br><a href="../svg_images/<?php echo $i ?>.svg">original</a><td>');
        //document.write('<td>'+img_<?php echo $i ?>+'<td>');

            <?php 
                $c++;
                if ($c == 10)  { 
                    $c = 0; ?> 
                    document.write('</tr><tr>');
            <?php } ?>
        ctx.clearRect (0 ,0 , <?php echo $size ?>, <?php echo $size ?>);
  <?php } ?>
    } catch (x) {
        alert(x);
    }
    document.write('</tr></table>');
    document.body.style.background = "#808080";
}
</script>
</body></html>
