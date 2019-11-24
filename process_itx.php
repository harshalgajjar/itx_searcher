<?php
$limiter=5;
do{
  $limiter-=1;
$itx_queue = file('queues/itx_queue.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$itx_visited = file('visited/itx_visited.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$itx_data = file('data/itx_data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$itx_words = fopen('data/itx_words.txt','a');

$old_words=Array();

for($i=0; $i<sizeof($itx_data); $i++){
  $word=explode(" ",$itx_data[$i])[0];
  $locations=array_slice(explode(" ",$itx_data[$i]),1);
  $old_words+=[$word=>$locations];
}

//print_r($old_words); //$old_words['word'] = [loc1, loc2, loc3, ...];

$url=$itx_queue[0];

echo "<hr>Scanning link:" . $url . "<br />";

$html=file_get_contents($itx_queue[0]);
$html=str_replace("\.",".",str_replace("\'","",str_replace('\"',"",str_replace("\`","",$html)))); //str_replace("","",

echo $html . "<hr>";
preg_match_all('/[a-zA-Z^]+/', $html, $new_words, PREG_OFFSET_CAPTURE);

//print_r($new_words);

for($i=0; $i<sizeof($new_words[0]); $i++){
  //new words referred by $words[0][$i][0]
  //print $new_words[0][$i][0];
  $found=0;
  foreach ($old_words as $word => $locations){
    if($new_words[0][$i][0]==$word){
      $found=1;
      if(!in_array($url, $locations)){
        array_push($old_words[$word],$url);
      }
      echo "repeated word:" . $word . ", location array: " . implode(",",$old_words[$word]) . "<br />";
      break;
    }
  }
  if(!$found){
    echo "new word:" . $new_words[0][$i][0] . "<br />";
    fwrite($itx_words, $new_words[0][$i][0] . PHP_EOL);
    $old_words+=[$new_words[0][$i][0]=>Array("$url")];
  }
}

$new_itx_data="";

foreach($old_words as $word => $locations){
  $new_itx_data= $new_itx_data . $word . " " . implode(" ",$locations) . PHP_EOL;
}

file_put_contents('data/itx_data.txt', $new_itx_data);

array_splice($itx_queue, array_search($url, $itx_queue), 1);
file_put_contents('queues/itx_queue.txt',implode(PHP_EOL,$itx_queue));
array_push($itx_visited, $url);
file_put_contents('visited/itx_visited.txt',implode(PHP_EOL,$itx_visited));
fclose($itx_words);

// for($i=0;$i<sizeof($links[0]);$i++){
//
// }

// array_splice($queue, array_search($url, $queue), 1);
// array_push($crawler_visited, $url);

// file_put_contents('queues/crawler_queue.txt',implode(PHP_EOL,$queue));
// file_put_contents('queues/itx_queue.txt',implode(PHP_EOL,$itx_queue));
// file_put_contents('visited/crawler_visited.txt',implode(PHP_EOL,$crawler_visited));

//print_r($links);
//echo "<hr><br/><hr>";
//print_r($itx_queue);

}while(sizeof($itx_queue)!=0 && $limiter>0);
?>
