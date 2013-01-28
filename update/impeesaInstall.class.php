<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once(dirname(__FILE__)."/impeesaUpdate.class.php");

class impeesaInstall extends impeesaUpdate {
	
	public function __construct($version, $self)
	{
		parent::__construct($version, $self);
	}
	
	public function DatabaseConnectionForm($data) {
		$host		= (!isset($data['host'])) ? "localhost" : $data['host'];
		$db			= (!isset($data['db'])) ? "" : $data['db'];
		$user		= (!isset($data['user'])) ? "root" : $data['user'];

		$db_form 	= array(
				array("fieldset", "Datenbank Zugangsdaten", array(
						array("text", "Host", "host", $host, True),
						array("text", "Datenbank", "db", $db, True),
						array("text", "User", "user", $user, True),
						array("password", "Passwort", "pass", "", True),
						array("static", "Tabellenprefix", "prefix", "impeesa2_", True),
					),
				),
				array("fieldset", "", array(
						array("submit", "Erstellen", "submit"),
					),
				),
			);
		
		if(!isset($_POST['submit']) || $this->form->Validation($db_form, $data) == false)
		{
			echo $this->form->GetForm($db_form, $this->self."?step=1");
			return false;
		}
		elseif($this->CanConnectMysql($data['host'], $data['db'], $data['user'], $data['pass']) == false)
		{
			$this->form->SetErrorMsg("Datenbankverbindung nicht möglich!");
			echo $this->form->GetForm($db_form, $this->self."?step=1");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function CreateDatabaseConfigFile($host, $db, $user, $password, $prefix) {
		$db_config	= "<?php
						/** Generated file of impeesa2 install script.
						*DON'T TOUCH IT!!! (if the system runs wihtout problems)
						**/
						define('MYSQL_HOST',		'".$host."');
						define('MYSQL_DB',			'".$db."');
						define('MYSQL_USER',		'".$user."');
						define('MYSQL_PASS',		'".$password."');
						define('MYSQL_PREFIX',		'".$prefix."');
						define('MYSQL_FETCH_MODE',	PDO::FETCH_ASSOC);
						?>";

		try {
			$file	= @fopen(PATH_MAIN."config/db.conf.php", "c+");
			if($file == false)
			{
				throw new Exception("Can't open file");
			}
			fwrite($db_config, $file);
			fclose($file);
			
			echo '<p class="success">Datei '.PATH_MAIN.'config/db.conf.php wurde erfolgreich erstellt.</p>';
			
			$this->CreateTableForm();
			
			return true;
		}
		catch(Exception $e)
		{
			echo '<p class="box error">Datei '.PATH_MAIN.'config/db.conf.php konnte nicht erstellt werden. Bitte vor dem fortfahren manuell nachholen!</p>';
			echo '<p>Inhalt der Datei:</p>';
			echo '<code>';
			echo nl2br(htmlentities($db_config));
			echo '</code>';
			
			echo '<br/><a href="'.$this->self.'?step=2">Erledigt. Tabellen erstellen</a>';
			return false;
		}
	}
	
	public function CreateTable() {
		if(!file_exists(PATH_MAIN."config/db.conf.php")) {
			echo "<p>Bitte erst ".PATH_MAIN."config/db.conf.php erstellen!<br/>";
			echo '<a href="'.$this->self.'?step=2">Erledigt. Weiter gehts</a>';
		} else {
			include_once(PATH_MAIN."config/db.conf.php");
			
			$db			= impeesaDB::getConnection();
			
			$sqldump	= $this->ParseMysqlDump(PATH_MAIN."sqldump/mysqldump_v".$this->version.".sql");
			try{
				$db->exec($sqldump);
				echo '<p class="box success">Tabellen wurden erfolgreich erstellt.<br/>';
			}
			catch(PDOException $e) {
				echo $e->getMessage();
				echo '<p class="box error">Leider ist etwas schief gelaufen!<br/>Bitte importieren Sie die Datei '.PATH_MAIN.'sqldump/mysqldump_v'.$this->version.'.sql selbstständig in ihre MySQL Tabelle.<br/>';
			}
			echo '<a href="'.$this->self.'?step=3">Datei- & Ordnerrechte überprüfen</a></p>';
		}
	}
}