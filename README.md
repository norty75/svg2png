<pre>
svg2png
=======

Javascript based SVG to PNG conversion tool.
input: one ore more .SVG (scaleble vector graphics) file in the server ../svg_images directory
usage: sample.php or zip.png
query parameters:
  color=rrggbb (hexa) optional, if given changes colors from FF0000 to given color in result, othervise no color change
  svg_image=file      optional, if given converts only given file from svg_images dir, otherwise converts all *.svg
  size=s              optional, if given converted PNG gets x=s y=s size, default is 72 pixels

</pre>
