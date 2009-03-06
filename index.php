<?php // ck-lister v0.3.2 by, Chris Kankiewicz (http://www.web-geek.com)

  // Files and directories that will not be listed
  $hidden = array(
    'ck-lister',
    'index.php',
    'error_log',
    '.htaccess',
    '.htpasswd',
  );

  // Define file extension and the associated image
    $fileIcons = array (
      // Applications
      'app' => 'app.png',
      'bat' => 'app.png',
      'exe' => 'app.png',
      'msi' => 'app.png',

      // Archives
      '7z' => 'archive.png',
      'gz' => 'archive.png',
      'rar' => 'archive.png',
      'tar' => 'archive.png',
      'zip' => 'archive.png',

      // Audio
      'aac' => 'music.png',
      'mid' => 'music.png',
      'midi' => 'music.png',
      'mp3' => 'music.png',
      'ogg' => 'music.png',
      'wma' => 'music.png',
      'wav' => 'music.png',

      // Code
      'c' => 'code.png',
      'css' => 'code.png',
      'htm' => 'code.png',
      'html' => 'code.png',
      'java' => 'code.png',
      'js' => 'code.png',
      'php' => 'code.png',
      'pl' => 'code.png',
      'xhtml' => 'code.png',
      'xml' => 'code.png',

      // Documents
      'doc' => 'word.png',
      'docx' => 'word.png',
      'odt' => 'text.png',
      'pdf' => 'pdf.png',
      'xls' => 'excel.png',

      // Images
      'gif' => 'image.png',
      'jpg' => 'image.png',
      'jpeg' => 'image.png',
      'png' => 'image.png',

      // Text
      'log' => 'text.png',
      'rtf' => 'text.png',
      'txt' => 'text.png',

      // Video
      'avi' => 'video.png',
      'mov' => 'video.png',
      'mp4' => 'video.png',
      'wmv' => 'video.png',

      // Other
      'iso' => 'cd.png',
      'mdf' => 'cd.png',
      'msg' => 'message.png',
    );

  // *** DO NOT EDIT ANYTHING BELOW UNLESS YOU ARE A PHP NINJA ***

  // Get dir if set otherwise get relative directory
  if (isset($_GET['dir']) && $_GET['dir'] != '') {
    $dir = $_GET['dir'];
  } else {
    $dir = './';
  }

  // Prevent access to files specified to be hidden
  if (in_array($dir,$hidden)) {
    $dir = './';
  }

  // Add trailing slash if none present
  if(substr($dir,-1,1) != '/') {
    $dir = $dir . '/';
	}

  $path = $path . $dir;

  // Prevent access to parent folders
  if (substr_count($path,'../') !== 0 
  || substr_count($path,'<') !== 0
  || substr_count($path,'>') !== 0) {
    $path = './';
  }

  // Open directory handle for reading
  $dirHandle = @opendir($path) or die("Unable to open $path");
    while ($file = readdir($dirHandle)) {

    if (is_dir("$path$file") && $file !== '.') {
      if ($file == '..' && !isset($_GET['dir'])) {
        continue;
      } else {
        if (!in_array($file,$hidden)) {
          $dirArray[] = $file;
        }
      }
    }

    if (!is_dir("$path$file") && !in_array($file,$hidden)) {
      $fileArray[] = $file;
    }
  }
  closedir($dirHandle);

  @natcasesort($dirArray);
  @natcasesort($fileArray);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>
    Directory listing of <?php
      if ($path == './') {
        echo(dirname($_SERVER['PHP_SELF']));
      } else {
        echo(dirname($_SERVER['PHP_SELF'])."/$path");
      }
    ?> - Powered by, CK-Lister
  </title>
  <link rel="stylesheet" type="text/css" href="ck-lister/css/reset.css" />
  <link rel="stylesheet" type="text/css" href="ck-lister/css/style.css" />
</head>

<body>

<div id="ck-lister">

  <div id="header">
    <span class="file-name">File</span>
    <span class="file-size">Size</span>
    <span class="file-time">Last Modified</span>
  </div>

  <!-- BEGIN DIRECTORY LISTING -->
<?php
  $n = 0; // Alternating BG color marker

  // Remove preceding "./" from path
  if (substr($path, 0, 2) == './') {
    $pathArray = explode("/",$path);
    unset($pathArray[0]);
    $path = implode("/",$pathArray);
  }

  // List directories
  if (isset($dirArray)) {
    foreach ($dirArray as $x) {

      $icon = 'folder.png';

      // Set varriables
      $name = $x;
      $size = "-";
      $time = date("Y-m-d H:i:s", filemtime("$path$x"));

      // Set background class
      if (isOdd($n)) {
        $bg = "light-bg";
      } else {
        $bg = "dark-bg";
      }

      // Set $dir to parent folder if directory is ".."
      if ($name == '..') {
        $pathArray = explode("/","$path$name");
        unset($pathArray[count($pathArray)-1]);
        unset($pathArray[count($pathArray)-1]);
        $dir = implode("/",$pathArray);
        $icon = 'back.png';
      } else {
        $dir = "$path$name";
      }

      echo("  <div class=\"$bg\">\r\n");
      if ($dir == '.' || $dir == '') {
        echo("    <a href=\"index.php\">\r\n");
      } else {
        echo("    <a href=\"?dir=$dir/\">\r\n");
      }
      echo("      <img src=\"ck-lister/icons/$icon\" />\r\n");
      echo('      <span class="file-name">'.$name."</span>\r\n");
      echo('      <span class="file-size">'.$size."</span>\r\n");
      echo('      <span class="file-time">'.$time."</span>\r\n");
      echo("    </a>\r\n");
      echo("  </div>\r\n");

      $n++; // Incriment BG marker
    }
  }

  // List files
  if (isset($fileArray)) {
    foreach ($fileArray as $x) {

      // Set varriables
      $name = $x;
      $size = round(filesize("$path$x")/1024);
      $time = date("Y-m-d H:i:s", filemtime("$path$x"));

      // Set icon if of a valid extension
      $ext = strtolower(substr($name, strrpos($name, '.')+1));
      if($fileIcons[$ext]) {
        $icon = $fileIcons[$ext];
      } else {
        $icon = 'blank.png';
      }

      if (isOdd($n)) {
        $bg = "light-bg";
      } else {
        $bg = "dark-bg";
      }

      echo("  <div class=\"$bg\">\r\n");
      echo("    <a href=\"$path$name\">\r\n");
      echo("      <img src=\"ck-lister/icons/$icon\" />\r\n");
      echo('      <span class="file-name">'.$name."</span>\r\n");
      echo('      <span class="file-size">'.$size."KB</span>\r\n");
      echo('      <span class="file-time">'.$time."</span>\r\n");
      echo("    </a>\r\n");
      echo("  </div>\r\n");

      $n++; // Incriment BG marker
    }
  }
?>
  <!-- END DIRECTORY LISTING -->

  <div id="ck-footer">
    <span class="footer-left">
      <a href="<?=$_SERVER['PHP_SELF'];?>">Home</a>
      <?php
        $breadCrumbs = split('/', $path);
        if(($total = sizeof($breadCrumbs))>0) {
          $current = '';
          for($x=0;$x<($total-1);$x++) {
            if ($breadCrumbs[$x] != '.') {
              $current = $current . $breadCrumbs[$x] . '/';
              echo ' &raquo; <a href="'.$_SERVER['PHP_SELF'].'?dir='.$current.'">'.$breadCrumbs[$x].'</a>';
            }
          }
        }
      ?>
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