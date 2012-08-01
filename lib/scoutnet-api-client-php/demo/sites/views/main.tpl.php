<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>ScoutNet Navigator - ScoutNet - Das Angebebot von Pfadfindern für Pfadfinder</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="Shortcut Icon" type="image/ico" href="http://www.scoutnet.de/favicon.ico" />
	    <link href="css/bootstrap.css" rel="stylesheet">
<?/*	    <link href="css/bootstrap-responsive.css" rel="stylesheet">*/?>
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
			.groupdata dt, .groupdata dd{
				text-align:left;
				padding:5px 0;
			}
			.groupdata ul{
				list-style: none inside none;
				margin-left: 0;
			}
			.container-fluid, footer{
				width: 940px;
				margin-left: auto;
				margin-right: auto;
			}
			footer{
				text-align: right;
			}
			[class*="span"] {
			    margin-left: 0;
			}
			</style>
		
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="../assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="?">
					<img src="img/scoutnet-logo.png" style="position:absolute;margin: 60px 0 0 -80px;"/>
					ScoutNet Navigator</a>
					<form class="navbar-search pull-left input-append">
						<input id="search" name="q" type="text" class="search-query span6" placeholder="Name, Ort, PLZ oder Stadtteil" /><input type="submit" value="Gruppe suchen" class="btn btn-primary" style="margin:0" />
				    </form>
					<div class="nav-collapse">
						<ul class="nav">
							<?/*<li class="active"><a href="#">Über den ScoutNet Navigator</a></li>*/?>
							<li><a href="http://www.scoutnet.de/">by www.scoutnet.de</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		
		
		
		<div class="container-fluid">

			<?=$body?>

			<hr>
			<footer>
				<p>ScoutNet - Von Pfadfindern für Pfadfinder<br /><a href="http://www.scoutnet.de">www.scoutnet.de</a></p>
			</footer>

		</div><!--/.fluid-container-->
		

		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
<?if($_SERVER['SERVER_NAME'] === 'localhost'):?>
		<script src="js/jquery-1.7.2-min.js"></script>
<?else:?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?endif?>
		<script src="js/bootstrap-collapse.js"></script>
<?/*
		<script src="js/bootstrap-transition.js"></script>
		<script src="js/bootstrap-alert.js"></script>
		<script src="js/bootstrap-modal.js"></script>
		<script src="js/bootstrap-dropdown.js"></script>
		<script src="js/bootstrap-scrollspy.js"></script>
		<script src="js/bootstrap-tab.js"></script>
		<script src="js/bootstrap-tooltip.js"></script>
		<script src="js/bootstrap-popover.js"></script>
		<script src="js/bootstrap-button.js"></script>
		<script src="js/bootstrap-carousel.js"></script>
		<script src="js/bootstrap-typeahead.js"></script>
*/?>
		<script>
			$(".collapse").collapse();
		</script>
	</body>
</html>
