svg2png<br>
=======<br>
<br>
Javascript based SVG to PNG conversion tool.<br>
input: one ore more .SVG (scaleble vector graphics) file in the server ../svg_images directory<br>
usage: sample.php or zip.png<br>
query parameters:<br>
  color=rrggbb (hexa) optional, if given changes colors from FF0000 to given color in result, othervise no color change<br>
  svg_image=file      optional, if given converts only given file from svg_images dir, otherwise converts all *.svg<br>
  size=s              optional, if given converted PNG gets x=s y=s size, default is 72 pixels<br>
<br>
