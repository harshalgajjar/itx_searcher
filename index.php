<html>
<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/style_index.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <title>ITRANSE</title>
</head>
<body>
  <div class="container" id="main-container">
    <div id="input-container">
      .ITX search
      <div id="search-form">
        <input type="text" autocomplete="off" id="search-input" oninput="searchSuggest(this.value)" name="search-input" style="border-radius:10px; border:1px solid #cfcfcf; padding:3 5 3 5; width:250px;" placeholder="namaste"/>
        <button onclick="searchSubmit()" id="go-button" style="background-color:#838383; border-radius:25px; border:1px solid #838383; color:#fff">→</button>
        <div id="liveresults"></div>
      </div>
    </div>
  </div>

  <div id="results" class="container-fluid" style="background-color:#ababab;">
    <div class="container" id="results-box" style="max-width:50%;">
    </div>
  </div>

<script>

var firstsearch=1;

document.getElementById("input-container").style.top=window.innerHeight/2-document.getElementById("input-container").offsetHeight/2;
document.getElementById("input-container").style.left=window.innerWidth/2-document.getElementById("input-container").offsetWidth/2;

function suggestionClicked(str){
  document.getElementById('search-input').value=str;
  searchSuggest(str);
}

function searchSuggest(str) {
  document.getElementById("liveresults").style.opacity="1"; //re showing the suggestions
  if (str.length==0) {
    document.getElementById("liveresults").innerHTML="";
    document.getElementById("liveresults").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      if(this.responseText==""){
        document.getElementById("liveresults").style.border="0px";
        document.getElementById("liveresults").style.opacity="0";

        setTimeout(function(){
          document.getElementById("liveresults").innerHTML="";
        }, 250);
      }
      else{
        document.getElementById("liveresults").innerHTML=this.responseText;
      }
    }
  }
  xmlhttp.open("GET","search.php?livekeyword="+str,true);
  xmlhttp.send();
}

function searchSubmit(){

  document.getElementById("liveresults").style.opacity="0";
  setTimeout(function(){
    document.getElementById("liveresults").innerHTML="";
  }, 200);

  if(firstsearch==1){
    firstsearch=0;
    document.getElementById("input-container").style.top="50px";
    setTimeout(function(){
      document.getElementById("results").style.top=document.getElementById("input-container").offsetHeight+50+"px";
      document.getElementById("results").style.minHeight=window.innerHeight-document.getElementById("input-container").offsetHeight-50+"px";
    }, 250);
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttpclick=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttpclick=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttpclick.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("results-box").innerHTML=this.responseText;
    }
  }
  xmlhttpclick.open("GET","search.php?keyword="+document.getElementById("search-input").value,true);
  xmlhttpclick.send();

  return false;
}

</script>
<footer>
&copy; <a href="http://fromabctill.xyz/" target="_blank">Harshal Gajjar</a>
</footer>
</body>
</html>
