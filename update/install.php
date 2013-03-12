<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once(dirname(__FILE__)."/impeesaInstall.class.php");
?>
<html>
<head>
	<title>Installation - Impeesa2 CMS for Scouts</title>
	<link href="<?= LINK_TPL; ?>css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Impeesa2 - Installation</h1>

<?php
$install	= new impeesaInstall("2.0.4a", "install");
if(!isset($_GET['step']))
{
	$install->DatabaseConnectionForm($_POST);
}
elseif($_GET['step'] == "1" && $install->CanConnectMysql($_POST['host'], $_POST['db'], $_POST['user'], $_POST['pass']))
{
	$install->CreateDatabaseConfigFile($_POST['host'], $_POST['db'], $_POST['user'], $_POST['pass'], $_POST['prefix']);
}
elseif($_GET['step'] == "1" && $install->CanConnectMysql($_POST['host'], $_POST['db'], $_POST['user'], $_POST['pass']) == false)
{
	$install->DatabaseConnectionForm($_POST);
}
elseif($_GET['step'] == "2") {
	$install->CreateTable();
}
elseif($_GET['step'] == "3") {
	$install->CheckPermissions();
	?>
	<p class="box success">Die Installation ist jetzt abgeschlossen. Bitte loggen Sie sich mit den Benutzerdaten<br/>
		DasLampe<br/>
		Test123<br/>
		ein und erstellen Sie einen neuen Benutzer.<br/>
		Nicht vergessen den Default Benutzer & dieses Verzeichnis (<?= PATH_MAIN; ?>update/) zu löschen!<br/><br/>
		Viel Spaß mit Impeesa2.<br/>
		Gut Pfad,<br/>
		DasLampe
	</p>
<?php
}
?>
</body>
</html>