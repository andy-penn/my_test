<?php 

require("constants.php");

//Check to see if submit has been pressed
if(!empty($_POST)){
	//check to see if all fields were filled out
	if(!isset($_POST['myCat']) || !isset($_POST['myMonth'])){
		//if not, set error
		$_SESSION['error'] = "Please fill out all fields!";	
	}else{
		$_SESSION['myCat'] = mysqli_real_escape_string($conn, $_POST['myCat']);
		$_SESSION['myMonth'] = mysqli_real_escape_string($conn, $_POST['myMonth']);
		//items per page
		$_SESSION['ipp'] = mysqli_real_escape_string($conn, $_POST['myNum']);
		//convert the date string into datetime
		$_SESSION['date'] = strtotime($_SESSION['myMonth']);
		$_SESSION['date'] = date('Y-m-d H:i:s', $_SESSION['date']);
		//make an end date to search by month
		$_SESSION['enddate'] = strtotime('+1 month', strtotime($_SESSION['date']));
		$_SESSION['enddate'] = date('Y-m-d H:i:s', $_SESSION['enddate']);
	}
}

//set variables
$news_table = "news_item";
$cat_table = "category";
$pivot_table = "category_news_item";


//check what page we're or else set to 1
if (isset($_GET["page"])){
	$myP = mysqli_real_escape_string($conn, $_GET["page"]);
	if($myP == "prev"){
		if($_SESSION['page'] > 1){
				$_SESSION['page']--;
		}
	}else if($myP == "next"){
		if($_SESSION['tPages'] > $_SESSION['page']){
				$_SESSION['page']= $_SESSION['page']+1;
		}
	}else{
		$_SESSION['page'] = $myP;
	}
}else{
	$_SESSION['page']=1; 
};

if(isset($_SESSION['ipp'])){
	$start_from = ($_SESSION['page']-1) * $_SESSION['ipp'];
}else{
	$start_from = 0;
}

//change query if tag was clicked
if(isset($_GET['tag'])){
	$tag = mysqli_real_escape_string($conn, $_GET['tag']);
	$_SESSION['myCat'] = $tag;
}
		

//search query
if( isset($_SESSION['myCat']) && isset($_SESSION['myMonth'])){
	$sql = "SELECT $news_table.ID, $news_table.title, $news_table.content, $news_table.date
	FROM $news_table
	INNER JOIN $pivot_table
	ON $pivot_table.news_item_id = $news_table.ID
	INNER JOIN $cat_table
	ON $cat_table.ID = $pivot_table.category_id
	AND $cat_table.title = '$_SESSION[myCat]'
	WHERE date >= '$_SESSION[date]'
	AND date <= '$_SESSION[enddate]'
	ORDER BY date ASC
	LIMIT $start_from, ".$_SESSION['ipp']."";
	$result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
	
	//now count results to split into pages
	$sqlC = "SELECT count($news_table.ID), $news_table.ID, $news_table.title, $news_table.content, $news_table.date
	FROM $news_table
	INNER JOIN $pivot_table
	ON $pivot_table.news_item_id = $news_table.ID
	INNER JOIN $cat_table
	ON $cat_table.ID = $pivot_table.category_id
	AND $cat_table.title = '$_SESSION[myCat]'
	WHERE date >= '$_SESSION[date]'
	AND date <= '$_SESSION[enddate]'";
	$rs_result = mysqli_query($conn,$sqlC) or die (mysqli_error($conn));
	$rowC = mysqli_fetch_row($rs_result);
	$total_records = $rowC[0];
	$total_pages = ceil($total_records / $_SESSION['ipp']);
	$_SESSION['tPages'] = $total_pages;
}

//check for tag names
$sql3 = "SELECT * FROM $cat_table ORDER BY title ASC";
$result3 = mysqli_query($conn, $sql3) or die (mysqli_error($conn));

//check for dates of news articles
$sql4 = "SELECT distinct month(date) as months, year(date) as years FROM $news_table ORDER BY date ASC";
$result4 = mysqli_query($conn, $sql4) or die (mysqli_error($conn));

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
<!-- Search Form ============================================================-->
		<form action="index.php" method="post" class="form-inline">
		<fieldset>
		<legend><h1>News</h1></legend>
			<label for="myCat">Category: </label>
			<select name="myCat" class="form-control"><!--PHP TO REMEMBER ORIGINAL SELECTION-->
				<option selected disabled>Please Select</option>
				';
				while ($row3 = mysqli_fetch_array($result3)) {	
					echo '<option value="'.$row3['title'].'"';if(isset($_SESSION['myCat'])){if($_SESSION['myCat']==$row3['title']){echo' selected';}}echo'>'.$row3['title'].'</option>';
				}
				echo'
			</select>
			
			<label for="myMonth">Month: </label>
			<select name="myMonth" class="form-control"><!--PHP TO REMEMBER ORIGINAL SELECTION-->
				<option selected disabled>Please Select</option>
				';
				//display only the dates that posts have been created
				while ($row4 = mysqli_fetch_array($result4)) {
					if(isset($oldYear)){
						if($row4['years'] == $oldYear){
							echo'<option value="'.date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years'].'"';if(isset($_SESSION['myMonth'])){if($_SESSION['myMonth']==date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years']){echo' selected';}}echo'>'.date('F', mktime(0, 0, 0, $row4['months'], 10)).'</option>';
						}else{
							echo '<optgroup Label="'.$row4['years'].'">';
							echo'<option value="'.date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years'].'"';if(isset($_SESSION['myMonth'])){if($_SESSION['myMonth']==date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years']){echo' selected';}}echo'>'.date('F', mktime(0, 0, 0, $row4['months'], 10)).'</option>';
						}
					}else{
						echo '<optgroup Label="'.$row4['years'].'">';
						echo'<option value="'.date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years'].'"';if(isset($_SESSION['myMonth'])){if($_SESSION['myMonth']==date('F', mktime(0, 0, 0, $row4['months'], 10)).$row4['years']){echo' selected';}}echo'>'.date('F', mktime(0, 0, 0, $row4['months'], 10)).'</option>';
					}
					$oldYear = $row4['years'];
				}
				echo'
			</select>
			
			<label for="myNum">Show: </label>
			<select name="myNum" class="form-control"><!--PHP TO REMEMBER ORIGINAL SELECTION-->
					<option value="5"';if(isset($_SESSION['ipp'])){if($_SESSION['ipp']=="5"){echo' selected';}}echo'>5</option>
					<option value="10"';if(isset($_SESSION['ipp'])){if($_SESSION['ipp']=="10"){echo' selected';}}echo'>10</option>
					<option value="25"';if(isset($_SESSION['ipp'])){if($_SESSION['ipp']=="25"){echo' selected';}}echo'>25</option>
			</select>
			
			<label for="submit" class="sublabel">Items Per Page &emsp;</label>
			<input type="submit" name="submit" value="Filter" class="btn btn-default">
		</fieldset>
		';

if(isset($_SESSION['error'])){
	echo '<p style="color:#f00;">'.$_SESSION['error'].'</p>';
	unset($_SESSION['error']);
}

echo'
		</form>

<!-- Search Results ==============================================================-->
';
	//check if a search string has been sent
	if(isset($_SESSION['myCat']) && isset($_SESSION['myMonth'])){
		//if so, show results
		while ($row = mysqli_fetch_array($result)) {	
			echo'
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="news.php?id='.$row['ID'].'"><h3>'.$row['title'].'</h3></a>
				</div>
				<div class="panel-body">
					<p class="date">'.date('jS M Y',strtotime($row['date'])).'</p>
					<p>'.substr($row['content'],0,200).'...... <a href="news.php?id='.$row['ID'].'">Read More</a></p>
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
echo'
<!-- Pagination =================================================================-->
';
	if(isset($total_records) && $total_records > 0){
	echo'
		<ul class="pagination">';
			echo'<li><a href="index.php?page=prev" aria-label="Previous">&laquo;</a></li>';
			for ($i=1; $i<=$total_pages; $i++) {
				echo'<li'; if(isset($_SESSION['page']) && $_SESSION['page'] == $i){echo ' class="active"';} echo'><a href="index.php?page='.$i.'">'.$i.'</a></li>';
			}
			echo'<li><a href="index.php?page=next" aria-label="Next">&raquo;</a></li>';
		echo'
		</ul>
	';
	}else{
		echo '<p style="color:#f00">No Results Found.</p>';	
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