<?php
/**
This script serach old static files, stored in pages/content/, open the files, parse the PHP Code and write the new content to database.
After that the script removes to old static files.
Needed by content management & dynamic menu function, from commit 3fc2682fbd2f7d7027f4172a80c75f05ed8cc38d and 39266893e79b1104f80bbe5f65508e3708ae138a. 
**/
header('Content-Type: text/html; charset=utf-8');
//Include some files
include_once("../config/path.conf.php");
include_once("../config/db.conf.php");
include_once("../core/class/impeesaDB.class.php");

$db	= impeesaDB::getConnection();
?>
Dieses Skript ist zum Update auf Impeesa2 v2.0.2 vorgesehen. Es werden alle statischen Seiten aus dem Ordner pages/content/ in die Datenbank geschrieben.<br/>
Der Dateiname ist hierbei der Titel, Menü Titel und Interner Name. Außer dem Internen Namen sollten alle Sachen angepasst werden (im Adminbereich).<br/>
Außerdem werden alle Seiten in das Hauptmenü geschrieben, dies ist ggf. auch anzupassen (hierzu gibt es eine Verwaltungsoberfläche).<br/>
Aus Sicherheitsgründen, sollte dieses Skript auf jeden Fall gelöscht werden, nachdem es ausgeführt wurde!!!!<br/>
Das Update beginnt jetzt.<br/>
<ul>
<?php
//Open pages/content/ & read content
$handle		= opendir(PATH_PAGES.'content/');
$x			= 0;
$sth		= $db->prepare("INSERT INTO ".MYSQL_PREFIX."content
							(name, title, menu_title, content, in_nav, nav_order, parent)
							VALUES
							(:filename, :filename, :filename, :content, 1, :nav_order, 0)");
while(false !== ($file	= readdir($handle)))
{
	if(!preg_match("/.php$/i", $file))
	{
		continue;
	}
	echo "<li>Datei ".$file." wird zur Datenbank hinzugefügt.</li>";
	$content	= file_get_contents(PATH_PAGES.'content/'.$file);
	$content	= preg_replace("/<\?php.*\?>/ms", '', $content);
	
	$filename	= str_replace(".php", "", $file);
	
	$sth->bindParam(":filename",	$filename);
	$sth->bindParam(":nav_order",	$x);
	$sth->bindParam(":content",		$content);
	$sth->execute();
	$x++;
	
	if(@unlink(PATH_PAGES.'content/'.$file))
		echo '<li style="color: green;">Datei '.$file.' wurde erfolgreich gelöscht.</li>';
	else
		echo '<li style="color: red;">Datei '.$file.' konnte nicht gelöscht werden, bitte manuell nachholen!</li>';
}
?>
</ul>
Nun werden zusätzlich die Seiten, welche einem Modul angehören in die Datenbank geschrieben.<br/>
<ul>
<?php
$sth		= $db->prepare("INSERT INTO ".MYSQL_PREFIX."content
							(name, title, menu_title, content, in_nav, nav_order, parent)
							VALUES
							(:filename, :title, :title, '', 1, :nav_order, 0)");
$modules	= array(
					array("calender", "Kalender"),
					array("contact", "Kontakt"),
					array("picture", "Bilder"),
					array("news", "Neuigkeiten"),
					array("admin", "Intern"),
				);
foreach($modules as $entry)
{
	$sth->bindParam(":filename",	$entry[0]);
	$sth->bindParam(":title",		$entry[1]);
	$sth->bindParam(":nav_order",	$x);
	$sth->execute();
	$x++;
	echo "<li>Modul ".$entry[1]." wurde hinzugefügt</li>";
}
?>
</ul>
Update Beendet!