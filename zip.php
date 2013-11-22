<html><body>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script> 
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script> 
<script type="text/javascript" src="lib/zip.js"></script>

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
        $color = "FF0000";
    }
?>

<canvas id="canvas" width="<?php echo $size?>px" height="<?php echo $size?>px"></canvas>
<script type="text/javascript" src="lib/zip.js"></script>

<script type="text/javascript">
var svga = new Array();

    try {
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
<?php
  $c = 0;
    foreach ($icons as $i) {?>
    ctx.drawSvg('colorize.php?dir=<?php echo $root ?>&color=<?php echo $color ?>&svg_src=<?php echo $i ?>',0,0,<?php echo $size ?>, <?php echo $size ?>);//, { scaleWidth: 72, scaleHeight: 72,ignoreMouse: true });
    var img_<?php echo $i ?> = canvas.toDataURL("image/png");
    ctx.clearRect (0 ,0 , <?php echo $size ?>, <?php echo $size ?>);
    var svgo = new Object();
    svgo.name = "<?php echo $i ?>.png";
    svgo.data = img_<?php echo $i ?>;
    svga.push(svgo);
    //alert(img_<?php echo $i ?>);
    <?php } ?>
    } catch (x) {
        alert(x);
    }
    zip.workerScriptsPath = "lib/";
    zip.useWebWorkers = false;
    //alert (svga[0].name);

	//alert(2);
// use a BlobWriter to store the zip into a Blob object
var writer = new zip.Data64URIWriter("zip");
zip.createWriter(writer, function(writer) {

  // use a TextReader to read the String to add
  //alert(svga.length);
  for(var x = 0; x < svga.length; x++) {
      var o = svga[x];
      writer.add(o.name, new zip.Data64URIReader(o.data)); 
  }
  writer.add("readme.txt", new zip.TextReader("icon size: <?php echo $size."x".$size ?>\r\ncolor: <?php echo $color ?>\r\ncount: <? echo count($icons) ?>\r\n"), 
  function() {
    // onsuccess callback
// close the zip writer
    writer.close(function(blob) {
      // blob contains the zip file as a Blob object
        document.write('<a download="icon_set_<?php echo $size."x".$size ?>_<?php echo $color ?>.zip" href="'+blob+'"/>icon_set_<?php echo $size."x".$size ?>_<?php echo $color ?><a><td>');
    });

  }, function(currentIndex, totalIndex) {
    // onprogress callback
    //alert(currentIndex +"/"+ totalIndex);
  });
}, function(error) {
  // onerror callback
  alert(error);
}, true);
	</script>
	</body></html>
