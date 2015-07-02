<?php
  
  session_start();

  if(!isset($_SESSION['username'])){

    header('Location:index.php');  	 
  	 
  	}


?>

<!DOCTYPE html>
<html lang="en">


<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<style>
#result ul {
    list-style-type:none;
    margin:10px 0 0 110px;
    padding:0;
}
</style>
<!-- TRIE SEARCHING-->

<style>
#Sresult ul {
  list-style-type:none;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  width: 360px;
}
</style>
<script src="http://yui.yahooapis.com/3.2.0/build/yui/yui-min.js"></script>
<script src="trie.js"></script>

<?php
include 'dbconnection.php';

$userid=$_SESSION['uid'];
$str=$_SERVER['QUERY_STRING'];
$query="select question_text from question";

$result=mysqli_query($conn,$query);

$storeQues = Array();
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeQues[] =  $row['question_text'];  
}

 $countQues = count($storeQues);
   /* Checking the array of question
    for($x = 0; $x < $countQues; $x++) {
    echo $storeQues[$x];
    echo "<br>";} */

// $s = $_GET["w1"];
// echo $s;

?>
<script>
(function() {
//var arrayStates = [
//        "raman","chaman","shaman","Home," "Inbox," "Matthias Hager," "Settings," "Logout","Year","Make","Model","Description","Price"
 //   ];
   var SearchQues = <?php echo json_encode( $storeQues ) ?>;
    var T = new Trie();
    var i;
    for(i = 0; i < SearchQues.length; i++) {
        T.insert(SearchQues[i].toLowerCase());
    }
    
    YUI().use('node', 'event', function(Y) {
        var handleKey = Y.on('keyup', function(e) {
            var textInput,
                suggestions,
                html,
                i;
                
            textInput = Y.one("#search").get("value");
            stateList = T.autoComplete(textInput.toLowerCase());
            
            html = "<ul>";
            for(i = 0; i < stateList.length; i++) {
                html += "<li>" + stateList[i] + "</li>";
            }
            html += "</ul>";
            
            Y.one("#Sresult").set('innerHTML', html);
        }, '#search');
    });
})();
</script><!--
<script type="text/javascript">
   var s="";
  function searchQ(x){
  s=x;
  
  if(s!=""){
   // alert(s);
  // window.location.href = "search_quest.php?w1=" + s;

  }
  }
</script> -->
<!-- TRIE SEARCHING ENDS-->
<style type="text/css">

#nbar{
width: 80%;
position: relative;
margin-left: auto;
margin-right: auto;
//border:solid;	
}

#search{
width: 360px;	
}

#addqbutton{
width:150px;	
}	

body{
overflow-x:hidden;
overflow-y:scroll; 	
}


</style>

<script src="script/resetmodaldata.js"></script>

<script>

f=0;
function submitQues(){


//store the question in a global variable

question=document.getElementById("q_text").value;

if(question[question.length-1]!='?'){
	
	//question should end with ?
	
	document.getElementById("next").href="#";
	$("#message").remove();
	$('#ta').prepend('<p id="message">Question should end with ?</p>');
	
}

//alert(question);

else{

document.getElementById("next").href="#myModal2";

$('#myModal').modal('hide'); }
	
}



function final_submit_ques(){
	
	alert(question);
	
	
	var t=document.getElementsByClassName("category");
	
	var str="";
	
	//alert(t.length);
	
	//alert(str);
	
	var count=0;
	
	for(i=0;i<t.length;i++){
	
	   if(t[i].checked){
         
         n=(t[i].value).toString();
         
         //alert(n);
         
         str=str+n+",";	   	
	   	
	   	}	
		
	}
	
	//alert(str);
	   
 $.post("add_ques_details.php",{ques:question,category:str},function(data){
   	 
   	 window.location="display_submitted_question.php?"+question+data;

   
   	
   	});  
   	
		
	
}

</script>

</head>


<body>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" id="f">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               Add Question
            </h4>
         </div>
         <div class="modal-body" id="ta">
            <textarea placeholder="What is your question?" id="q_text" cols="60" rows="5"></textarea>
         </div>
         <div class="modal-footer">
            <a href="#myModal2" class="btn btn-primary" role="button" data-toggle="modal" onclick="submitQues()" id="next">Next</a>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>


<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               Select Category
            </h4>
         </div>
         <div class="modal-body">
            <?php
               
               //some algorithm goes here which will suggest user some categories//
               
             //  session_start();     
               
               include 'dbconnection.php';
               
               $query="select * from category";
               
               $result=mysqli_query($conn,$query);
                              
               while($row=mysqli_fetch_assoc($result)){
               
               //value and name added later if needed
               echo '<input type="checkbox" class="category" value="'.$row['category_id'].'">'.ucfirst($row['category_name']).'<br><br>';
               
               }
                          
            ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="final_submit_ques()">
               Add Question
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>








<nav class="navbar navbar-default" id="nbar">
  <div class="container-fluid" id="cont">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php" id="hbutton">Q&A</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
      <form class="navbar-form navbar-left" role="search"><!-- method="POST" action="search_quest.php"-->
        <div class="form-group">
          
          <input type="text" class="form-control" placeholder="Search" id="search" class="list-group"><!-- onkeypress="searchQ(this.value)"> -->
          <span id="Sresult" class="list-group">
          </span> 
          
          <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>&nbsp;Search</button>
          <a href="#myModal" class="btn btn-primary" role="button" data-toggle="modal"><span class="glyphicon glyphicon-question-sign"></span>&nbsp;Add Question</a>  

        </div> 
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-bell"></span>&nbsp;Notifications<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php  echo $_SESSION['username']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>       
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  

