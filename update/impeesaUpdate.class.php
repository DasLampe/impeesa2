<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once("../config/path.conf.php");
include_once("../config/core.inc.php");
header('Content-Type: text/html; charset=utf-8');

class impeesaUpdate {
	public $version;
	protected $form;
	protected $self;
	
	public function __construct($version, $self) {
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		
		$this->version	= $version;
		$this->form 	= new impeesaForm();
		$this->self		= LINK_MAIN."update/".$self.".php";
	}
	
	public function CanConnectMysql($db, $host, $user, $password)
	{
		try {
			$db		= new PDO("mysql:host=".$_POST['host'].";dbname=".$_POST['db'], $_POST['user'], $_POST['pass']);
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}
	
	public function CheckPermissions() {
		$files_to_check	= array(PATH_UPLOAD."news/", PATH_UPLOAD."picture/" ,PATH_MAIN."tmp/");
		foreach($files_to_check as $check) {
			if(is_writable($check)) {
				echo '<p class="box success">'.$check.' ist beschreibbar.</p>';
			} elseif(chmod($check, 766)) {
				echo '<p class="box success">'.$check.' war nicht beschreibbar. Impeesa2 konnte dieses Problem jedoch beheben.</p>';
			} else {
				echo '<p class="box error">'.$check.' ist nicht beschreibar & rechte können nicht gesetzt werden. Bitte entsprechende manuell Rechte setzen!</p>';
			}
		}
	}
	
	public function UpdateDatabase() {
		include_once(PATH_MAIN."config/db.conf.php");
		
		$db		= impeesaDb::getConnection();
		
		$sqldump	= $this->ParseMysqlDump(PATH_MAIN."db_migration/".$this->version.".sql");
		try {
			$db->exec($sqldump);
			echo '<p class="box success">Datenbank wurde aktuallisiert!<p>';
		}
		catch(PDOException $e) {
			echo '<p class="box error">Die Datenbank konnte nicht aktuallisiert werden.<br/>Bitte führen Sie den SQL aus der Datei '.PATH_MAIN."db_migration/".$this->version.".sql manuell aus.<p>";
		}
		echo '<p><a href="'.$this->self.'?step=1">Datei- & Ordnerberechtiung checken</a></p>';
	}
	
	/**
	* Code based on http://de3.php.net/manual/de/function.mysql-query.php#56636
	**/
	protected function ParseMysqlDump($url, $ignoreerrors = false) {
		$file_content = file($url);
		$query = "";
		$result	= "";
		foreach($file_content as $sql_line) {
			$tsl = trim($sql_line);
			if (($sql_line != "") && (substr($sql_line, 0, 2) != "--") && (substr($sql_line, 0, 1) != "#") && !preg_match("/^\/\*.*\*\/;$/", $sql_line)) {
				$query .= $sql_line;
				if(preg_match("/;\s*$/", $sql_line)) {
					$result .= $query;
					if (!$result && !$ignoreerrors) {
						return false;
					}
					$query = "";
				}
			}
		}
		return $result;
	}
}