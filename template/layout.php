<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<!-- Mobile viewport optimisation -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>»‹ Impeesa2</title>
	<link href="<?= LINK_TPL; ?>css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= LINK_MAIN; ?>core/lib/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?= LINK_MAIN; ?>core/lib/js/webtoolkit.base64.js"></script>
	<script type="text/javascript" src="<?= LINK_MAIN; ?>core/lib/js/email_protection.js"></script>
	{js_files}
	{css_files}
</head>
<body>
<ul class="ym-skiplinks">
	<a class="skip" title="ym-skip" href="#navigation">Skip to the navigation</a><span class="hideme">.</span>
	<a class="skip" title="ym-skip" href="#content">Skip to the content</a><span class="hideme">.</span>
</ul>

{if} {infobar} != ""{/if}
<div id="infobar">
	<div class="ym-wrapper">
		{infobar}
	</div>
</div>
{/endif}

	<header>
		<div class="ym-wrapper">
			<div class="ym-wbox">
				<h1>»‹ Impeesa2 - CMS for Scouts</h1>
			</div>
		</div>
	</header>
	<nav id="nav">
		<div class="ym-wrapper">
			<a id="navigation" name="navigation"></a>
			<div class="ym-hlist">
				<?php
					include_once(PATH_CONTROLLER."menu.php");
				?>
			</div>
		</div>
	</nav>
	<div id="main" class="ym-grid ym-equalize">
		<div class="ym-g20 ym-gl sidebar">
			<div class="sidebar-top"></div>
				{sidebar}
		</div>
		<div class="ym-g80 ym-gr">
			<div class="ym-gbox">
				<div class="content">
					{page_content}
				</div>
			</div>
		</div>
	</div>
	<footer>
		Powered by <a href="http://daslampe.lano-crew.org">DasLampe</a>
	</footer>
</body>
</html>
