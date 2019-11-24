<?php
$limiter=5;
do{
  $limiter-=1;
  $crawler_queue = file('queues/crawler_queue.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $itx_queue = file('queues/itx_queue.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $crawler_visited = file('visited/crawler_visited.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $itx_visited = file('visited/itx_visited.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  $url=$crawler_queue[0];
  $url_subpath="";
  $url_last_word="";

  if($url[strlen($url)-1]=="/"){
    $url=substr($url,0,strlen($url)-1);
  }

  $html=file_get_contents($url);

  preg_match_all('/(href[\ ]*=[\ ]*)["\']+[^\'"]*["\']+/', $html, $links, PREG_OFFSET_CAPTURE);
  preg_match('/.*[\/]/',$url,$url_subpath, PREG_OFFSET_CAPTURE); $url_subpath=$url_subpath[0][0];
  if($url_subpath=="https://" || $url_subpath=="http://") $url_subpath=$url."/";
  preg_match('/\/[^\/]+$/',$url,$url_last_word, PREG_OFFSET_CAPTURE); $url_last_word=$url_last_word[0][0]; $url_last_word=substr($url_last_word,1);

  echo "<hr>Scanning link:" . $url . "<br />";
  echo "subpath= " . $url_subpath . "<br />";
  echo "last_word= " . $url_last_word . "<br />";
  echo "<br />";

  for($i=0;$i<sizeof($links[0]);$i++){

    //specific link: $links[0][$i][0]

    $links[0][$i][0]=preg_replace('/href[\ ]*=[\ ]*[\"\ \']*/', '', $links[0][$i][0]); //https?:\/\/
    $links[0][$i][0]=preg_replace('/[\[^"\]+]$/', '', $links[0][$i][0]);
    $links[0][$i][0]=preg_replace('/#.*$/','', $links[0][$i][0]);
    $links[0][$i][0]=preg_replace('/\?.*$/','', $links[0][$i][0]);

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
      if(!in_array($links[0][$i][0],$itx_queue) && !in_array($links[0][$i][0],$itx_visited)){
        array_push($itx_queue, $links[0][$i][0]);
        echo "New .itx found: " . $links[0][$i][0] . "<br />";
      }
    } else if(substr($links[0][$i][0],-4)!=".css" && substr($links[0][$i][0],-4)!=".png" &&
     substr($links[0][$i][0],-4)!=".ico" &&  substr($links[0][$i][0],-4)!=".jpg" &&
     substr($links[0][$i][0],-4)!=".jpeg" &&  substr($links[0][$i][0],-4)!=".css" &&
     substr($links[0][$i][0],-4)!=".gif" && substr($links[0][$i][0],-3)!=".js" &&
     substr($links[0][$i][0],-4)!=".pdf")
    {
      if(!in_array($links[0][$i][0],$crawler_queue) && !in_array($links[0][$i][0],$crawler_visited)){
        array_push($crawler_queue, $links[0][$i][0]);
        echo "New link to crawl found: " . $links[0][$i][0] . "<br />";
      }
    }
  }

  array_splice($crawler_queue, array_search($url, $crawler_queue), 1);
  array_push($crawler_visited, $url);

  file_put_contents('queues/crawler_queue.txt',implode(PHP_EOL,$crawler_queue));
  file_put_contents('queues/itx_queue.txt',implode(PHP_EOL,$itx_queue));
  file_put_contents('visited/crawler_visited.txt',implode(PHP_EOL,$crawler_visited));

  //print_r($links);
  //echo "<hr><br/><hr>";
  //print_r($itx_queue);

}while(sizeof($crawler_queue)!=0 && $limiter>0);
?>
