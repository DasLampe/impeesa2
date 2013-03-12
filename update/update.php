<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once("impeesaUpdate.class.php");
?>
<html>
<head>
	<title>Update - Impeesa2 CMS for Scouts</title>
	<link href="<?= LINK_TPL; ?>css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Impeesa2 - Update</h1>
<p>
	Mit diesem Updateskript wird die Datenbank auf die neuste Version aktuallisiert, die neuen Dateien müssen jedoch selbstständig hinzugefügt werden!
</p>
<?php
if(!file_exists(PATH_MAIN."config/db.conf.php"))
{
	echo '<p class="box error">Die Datei '.PATH_MAIN.'config/db.conf.php existiert nicht.<br/>Bitte erstelle diese!<br/><a href="<?= LINK_MAIN; ?>update/install.php">Ich habe Impeesa2 noch gar nicht installiert!</a>';
} else {
	$update	= new impeesaUpdate("2.0.4a", "update");
	
	if(!isset($_GET['step'])) {
		$update->UpdateDatabase();
	}
	else
	{
		$update->CheckPermissions();
?>
<p class="box success">Das Update ist beendet. Weiterhin viel Spaß mit Impeesa2. Für Anregungen einfach ein Ticket auf <a href="http://github.com/DasLampe/impeesa2">http://GitHub.com/DasLampe/impeesa2</a> erstellen.<br/><br/>Gut Pfad,<br/>DasLampe<br/><br/>Wie jedes mal nicht vergessen den Ordner <?= PATH_MAIN; ?>update/ wieder zu entfernen!</p>
<?php
	}
}
?>
</body>
</html>