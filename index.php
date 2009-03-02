<?php // ck-lister v0.1.0 by, Chris Kankiewicz (http://www.web-geek.com)

  // Get path if set otherwise get relative path
  if (isset($_GET['dir'])) {
    $path = $_GET['dir'];
  } else {
    $path = '.';
  }
  
  if(substr($path,-1,1) != '/') {
    $path = $path . '/';
	}

  // Open directory handle for reading
  $dirHandle = @opendir($path) or die("Unable to open $path");
  if ($dirHandle = opendir($path)) {
    while ($file = readdir($dirHandle)) {
      if ($file !== '.') {
        $fileArray[] = array (
          "icon" => $icon,
          "name" => $file,
          "size" => round(filesize("$path$file")/1024),
          "time" => filemtime("$path$file")
        );
      }
    }
  closedir($dirHandle);
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Directory listing of <?php echo(dirname($_SERVER['PHP_SELF'])."/$path"); ?></title>
  <link rel="stylesheet" type="text/css" href="ck-lister/css/reset.css" />
  <link rel="stylesheet" type="text/css" href="ck-lister/css/style.css" />
  <!--[if IE]><link rel="stylesheet" href="ck-lister/css/ie-fixes.css" type="text/css" /><![endif]-->
  <!--[if lte IE 6]><link rel="stylesheet" href="ck-lister/css/ie6-fixes.css" type="text/css" /><![endif]-->
</head>

<body>

<div id="ck-lister">

  <div id="header">
    <span class="file-name">File</span>
    <span class="file-size">Size</span>
    <span class="file-time">Last Modified</span>
  </div>
  
  <!-- BEGIN DIRECTORY LISTING -->
<?php // Echo directory list list
  for ($x = 0; $x < count($fileArray); $x++) {
    
    // Set varriables
    $icon = 'blank.png';
    $name = $fileArray[$x][name];
    $size = $fileArray[$x][size];
    $time = date("Y-m-d H:i:s", $fileArray[$x][time]);
    
    // Define file extension and the associated image
    $filetypes = array (
      'exe' => 'app.png',
      'gif' => 'image.png',
      'htm' => 'code.png',
      'html' => 'code.png',
      'iso' => 'cd.png',
      'jpeg' => 'image.png',
      'jpg' => 'image.png', 
      'php' => 'code.png',
      'png' => 'image.png',
      'txt' => 'text.png',
      'zip' => 'archive.png'
    );
    
    // Set icon if of a valid extension
		$ext = strtolower(substr($name, strrpos($name, '.')+1));
    if($filetypes[$ext]) {
			$icon = $filetypes[$ext];
		} 
    
  
    if (isOdd($x)) {
      echo("  <div class=\"light-bg\">\r\n");
    } else {
      echo("  <div class=\"dark-bg\">\r\n");
    }
    
    if (is_dir($name)) {
      echo("    <a href=\"?dir=$path$name\">\r\n");
    } else {
      echo("    <a href=\"$path$name\">\r\n");
    }
    
    echo("      <img src=\"ck-lister/icons/$icon\" />\r\n");
    
    echo('      <span class="file-name">'.$name."</span>\r\n");
    echo('      <span class="file-size">'.$size."KB</span>\r\n");
    echo('      <span class="file-time">'.$time."</span>\r\n");
    echo("    </a>\r\n");
    echo("  </div>\r\n");
  }
?>
  <!-- END DIRECTORY LISTING -->
  
  <div id="ck-footer">
    <span class="footer-left">
      &copy; 2009 
      <strong>&middot;</strong> Chris Kankiewicz 
      <strong>&middot;</strong> Some Rights Reserved
    </span>
    <span class="footer-right">
      Powered by, 
      <a href="http://github.com/PHLAK/ck-lister">CK-Lister</a>
    </span>
  </div>

</div>

</body>
</html>

<?php
  // *** FUNCTIONS ***
  function isOdd($number) {
    return $number & 1; // 0 = even, 1 = odd
  }
?>