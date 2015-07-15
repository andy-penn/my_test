<?php 


echo'

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=no">
    <!-- The above 3 meta tags stay at top -->
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black>
    <meta name="description" content="Test example for Clever Cherry">
    <meta name="author" content="Andrew Penn">

    <title>News Results</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="navbar-fixed-top.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
		<h1>News</h1>
<!-- Search Form ================================================-->
		<form class="form-inline">
			<label for="myCat">Category: </label>
			<select name="myCat" class="form-control">
				<option selected disabled>Please Select</option>
				<option>Lorem</option>
				<option>Ipsum</option>
				<option>Accumsan</option>
				<option>Varius</option>
				<option>Habitasse</option>
				<option>Elementum</option>
				<option>Sed</option>
			</select>
			
			<label for="myMonth">Month: </label>
			<select name="myMonth" class="form-control">
				<option selected disabled>Please Select</option>
				<optgroup Label="2014">
					<option value="01/11/2014">November</option>
					<option value="01/12/2014">December</option>
				<optgroup Label="2015">
					<option value="01/01/2015">January</option>
					<option value="01/02/2015">February</option>
					<option value="01/03/2015">March</option>
			</select>
			
			<label for="myNum">Show: </label>
			<select name="myNum" class="form-control">
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="25">25</option>
			</select>
			
			<label for="submit">Items Per Page &emsp;</label>
			<input type="submit" name="submit" value="Filter" class="btn btn-default">
		</form>

    </div><!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
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

?>