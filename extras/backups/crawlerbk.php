<?php
  //$html="<a href='http://fromabctill.xyz'></a> <a href='http://fromabctill.xyz/contact'></a> <a href='http://fromabctill.xyz/projects'></a>'";

  $queue = fopen("crawler_queue.txt", "a+");
  $itx_queue = fopen("itx_queue.txt", "a+");
  //echo strlen(fgets($queue)) . "<br />";

  if(filesize("crawler_queue.txt")==0) $url=$_POST['start'];
  else $url=fgets($queue);

  $url="http://fromabctill.xyz";

  $url=substr($url,0,strlen($url)-1);
  $url_subpath="";
  $url_last_word="";

  $itx_links=Array();

  if($url[strlen($url)-1]=="/"){
    $url=substr($url,0,strlen($url)-1);
  }

  $html=file_get_contents($url);

  preg_match_all('/(href[\ ]*=[\ ]*)["\']+[^\'"]*["\']+/', $html, $links, PREG_OFFSET_CAPTURE);
  preg_match('/.*[\/]/',$url,$url_subpath, PREG_OFFSET_CAPTURE); $url_subpath=$url_subpath[0][0]; if($url_subpath=="https://" || $url_subpath=="http://") $url_subpath=$url."/";
  preg_match('/\/[^\/]+$/',$url,$url_last_word, PREG_OFFSET_CAPTURE); $url_last_word=$url_last_word[0][0]; $url_last_word=substr($url_last_word,1);

  echo $url . "<br />";
  echo "subpath= " . $url_subpath . "<br />";
  echo "last_word= " . $url_last_word . "<br />";
  echo "<br />";

  // echo 'size of $links=' . sizeof($links[0][0][0][0]) . "<br />";
  // echo '$links[0][0][0][0]=' . $links[0][0][0];

  for($i=0;$i<sizeof($links[0]);$i++){

    //specific link: $links[0][$i][0]

    $links[0][$i][0]=preg_replace('/href[\ ]*=[\ ]*[\"\ \']*/', '', $links[0][$i][0]); //https?:\/\/
    $links[0][$i][0]=preg_replace('/[\[^"\]+]$/', '', $links[0][$i][0]);

    if($links[0][$i][0][0]=="/"){
      // echo $links[0][$i][0] . "->";
      $links[0][$i][0]= $url_subpath . substr($links[0][$i][0],1);
      // echo $links[0][$i][0] . "</br >";
    }else if($links[0][$i][0]=="#"){
      // echo $links[0][$i][0] . "->";
      $links[0][$i][0]= $url;
      // echo $links[0][$i][0] . "</br >";
    }else if(strpos($links[0][$i][0],"http") === false){
      // echo $links[0][$i][0] . "->";
      $links[0][$i][0]=$url_subpath . $links[0][$i][0];
      // echo $links[0][$i][0] . "<br />";
    }else{
      // echo $links[0][$i][0] . "</br >";
    }

    if($links[0][$i][0][strlen($links[0][$i][0])-1]=="/"){
      // echo $links[0][$i][0] . "->";
      $links[0][$i][0]= substr($links[0][$i][0],0,strlen($links[0][$i][0])-1);
      // echo $links[0][$i][0] . "</br >";
    }

    if($links[0][$i][0][strlen($links[0][$i][0])-1] == "'"){
      // echo $links[0][$i][0] . "->";
      $links[0][$i][0]= substr($links[0][$i][0],0,strlen($links[0][$i][0])-1);
      // echo $links[0][$i][0] . "<br />";
    }

    // echo $links[0][$i][0] . "->";
    $links[0][$i][0]=str_replace("/./","/",$links[0][$i][0]);
    // echo $links[0][$i][0] . "</br >";

    if(substr($links[0][$i][0],-4)==".itx"){
      // echo ".itx found: " . substr($links[0][$i][0],-3) . "<br />";
      array_push($itx_links, $links[0][$i][0]);
      if ((strpos($full_itx_queue, $links[0][$i][0] . PHP_EOL) == false) && !($links[0][$i][0]==$url)) {
        fwrite($itx_queue, $links[0][$i][0] . PHP_EOL);
      }

    } else {
      if (strpos($full_queue, $links[0][$i][0] . PHP_EOL) == false && !($links[0][$i][0]==$url)) {
        fwrite($queue, $links[0][$i][0] . PHP_EOL);
      }
    }

  }

  fclose($queue);
  fclose($itx_queue);

  if(filesize("crawler_queue.txt")!=0){
    $queue = fopen("crawler_queue.txt", "r");
    $full_queue = fread($queue,filesize("crawler_queue.txt"));
    fclose($queue);

    echo "<br />full_queue=" . $full_queue . "<br/>";
    echo "<br /><hr>url.php_eol=" . $url.PHP_EOL . "<br/>";

    $full_queue=str_replace($url.PHP_EOL,"",$full_queue);

    echo "<br />full_queue=" . $full_queue . "<br/><hr>";
    $queue = fopen("crawler_queue.txt", "w");
    fwrite($queue,$full_queue);
    fclose($queue);
  }

  print_r($links);
  echo "<hr><br/><hr>";
  print_r($itx_links);

?>
<html>
<head>
</head>
<body>
  <form action="" method="POST">
    <input type="text" value="http://fromabctill.xyz/" name="start" />
    <input type="submit" name="start_submit" />
  </form>
</body>
</html>
