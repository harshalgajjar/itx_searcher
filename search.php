<?php
  if(isset($_GET['livekeyword'])){
    $keyword=$_GET['livekeyword'];
    $current_words = file('data/itx_words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $all_words = implode(" ",$current_words);

    $search_regex= "/\b" . $keyword . "[a-zA-Z^]*\b/";

    preg_match_all($search_regex, $all_words, $suggested_words, PREG_OFFSET_CAPTURE);

    for($i=0; $i<sizeof($suggested_words[0]); $i++){
      if($i>5) break;
      echo "<span class='suggested-word' onclick='suggestionClicked(this.innerHTML)'>" . $suggested_words[0][$i][0] . "</span><br />";
    }
  }else if(isset($_GET['keyword'])){

    $output_html="";
    $exact_output_html="";

    $itx_data = file('data/itx_data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $keyword=$_GET['keyword'];

    $results_found=0;
    $exact_results_found=0;

    for($i=0; $i<sizeof($itx_data); $i++){
      $word=explode(" ",$itx_data[$i])[0];

      if($keyword==$word){
        $locations=array_slice(explode(" ",$itx_data[$i]),1);
        $exact_results_found+=sizeof($locations);

        for($j=0; $j<sizeof($locations); $j++){
          $exact_output_html.= "<div class='one-result' id='result" . "$j' onclick='oneResultClicked($locations[$j], $j)'><span class='word-found'>" . $word . "</span><br />" . $locations[$j] .
          "<br /><a href='$locations[$j]' target='_blank'>Visit</a> <a class='download-pdf-button' href='topdf.php?link=$locations[$j]' target='_blank'>Download</a>" .
          "</div>";
        }
      }

    }

    for($i=0; $i<sizeof($itx_data); $i++){
      $word=explode(" ",$itx_data[$i])[0];

      if($keyword!=$word && substr($word,0,strlen($keyword))==$keyword){
        $locations=array_slice(explode(" ",$itx_data[$i]),1);
        $results_found+=sizeof($locations);

        for($j=0; $j<sizeof($locations); $j++){
          $output_html.= "<div class='one-result' id='result" . "$j' onclick='oneResultClicked($locations[$j], $j)'><span class='word-found'>" . $word . "</span><br />" . $locations[$j] .
          "<br /><a href='$locations[$j]' target='_blank'>Visit</a> <a class='download-pdf-button' href='topdf.php?link=$locations[$j]' target='_blank'>Download</a>" .
          "</div>";
        }
      }
    }

    if($exact_results_found!=0 && $results_found!=0){
      echo $exact_results_found . " results found<br /><br />";
      echo $exact_output_html;

      echo "<br /><br />Searching something similar?<br /><br />";
      echo $output_html;

    }elseif($exact_results_found!=0 && $results_found==0){
      echo $exact_results_found . " results found<br /><br />";
      echo $exact_output_html;

    }else{
      echo "No results found<br /><br />";
    }
  }
?>
