<?php // ck-lister v0.1.0 by, Chris Kankiewicz (http://www.web-geek.com)

  //define the path as relative
  $path = getcwd();

  //using the opendir function
  $dir_handle = @opendir($path) or die("Unable to open $path");

  echo "Directory Listing of $path<br/>";

  //running the while loop
  while ($file = readdir($dir_handle)) {
    if ($file !== '.') {
      echo "<a href='$file'>$file</a><br/>";
    }
  }
  
  echo $path;

  //closing the directory
  closedir($dir_handle);

?>