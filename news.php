<?php 

require("constants.php");

//set variables
$news_table = "news_item";
$cat_table = "category";
$pivot_table = "category_news_item";


//check what page we're or else set to 1
if (isset($_GET["id"])){
	$oldpage = mysqli_real_escape_string($conn, $_GET['p']);
	$id =  mysqli_real_escape_string($conn, $_GET['id']);
	$sql = "SELECT * FROM $news_table WHERE ID = '$id'";
	$result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
}else{
	header("location:index.php");	
}


echo'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=no">
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black>
    <meta name="description" content="Test example for Clever Cherry">
    <meta name="author" content="Andrew Penn">

    <title>News Results</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="navbar-fixed-top.css" rel="stylesheet">
	
	<!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
	<a href="index.php?page='.$oldpage.'" class="btn btn-default backbtn">Back</a>
<!-- Search Results ==============================================================-->
';
	//check if a search string has been sent
	if(isset($_GET['id'])){
		//if so, show results
		while ($row = mysqli_fetch_array($result)) {	
			echo'
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3>'.$row['title'].'</h3>
				</div>
				<div class="panel-body">
					<p class="date">Posted at '.date("H:i",strtotime($row['date'])).' on '.date('jS M Y',strtotime($row['date'])).'</p>
					<p>'.$row['content'].'</p>
					';
						//get the news tags for each result
						$sql2 = "SELECT $cat_table.title
						FROM $cat_table
						INNER JOIN $pivot_table
						ON $pivot_table.news_item_id = '$row[ID]'
						AND $cat_table.ID = $pivot_table.category_id
						ORDER BY title ASC";
						$result2 = mysqli_query($conn, $sql2) or die (mysqli_error($conn));
					while ($row2 = mysqli_fetch_array($result2)) {
						echo '<a href="index.php?tag='.$row2['title'].'" class="label label-info">'.$row2['title'].'</a>';
					}
					echo'
				</div>
			</div>
			';
		}
	}
echo'		
    </div><!-- /container -->


<!-- Bootstrap core JavaScript ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
  </body>
</html>

';
mysqli_close($conn);
?>