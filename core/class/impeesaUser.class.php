<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaUser {
	private $session;
	private $user_id;
	
	public function __construct(&$session)
	{
		$this->session	=& $session;
		if(isset($this->session['user_id']))
		{
			$this->user_id	= $this->session['user_id'];
		}
		
		$this->db		= impeesaDb::getConnection();
	}
	
	public function IsLogin()
	{
		if(isset($this->session['user_id']) && md5($_SERVER['HTTP_USER_AGENT'].$this->GetUserSessionKey($this->session['user_id'])) == $this->GetUserSessionFingerprint($this->session['user_id']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function GetUserSessionKey($user_id)
	{
		$sth	= $this->db->prepare("SELECT session_key
									FROM ".MYSQL_PREFIX."users
									WHERE id = ?");
		$sth->execute(array($user_id));
		$row	= $sth->fetch();
		return $row['session_key'];
	}
	
	private function GetUserSessionFingerprint($user_id)
	{
		$sth	= $this->db->prepare("SELECT session_fingerprint
				FROM ".MYSQL_PREFIX."users
				WHERE id = ?");
		$sth->execute(array($user_id));
		$row	= $sth->fetch();
		return $row['session_fingerprint'];
	}
	
	private function SetSessionFingerprint($user_id)
	{
		$fingerprint = md5($_SERVER['HTTP_USER_AGENT'].$this->GetUserSessionKey($user_id));
		$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."users SET
									session_fingerprint = :fingerprint
									WHERE id = :user_id");
		$sth->bindParam(":fingerprint",	$fingerprint);
		$sth->bindParam(":user_id",		$user_id);
		$sth->execute();
		return true;
	}
	
	function SetUserId($userinfo)
	{
		if(!is_numeric($userinfo))
		{
			$sth	= $this->db->prepare("SELECT id
						FROM ".MYSQL_PREFIX."users
						WHERE username LIKE :username");
			$sth->execute(array(":username" => $userinfo));
			$row	= $sth->fetch();
			
			if(!$row)
			{
				return false;
			}
			
			$this->user_id = $row['id'];
			
		}
		else
		{
			$this->user_id = $userinfo;
		}
	}
	
	public function SetUserIdByUsername($username)
	{
		if($this->SetUserId($username) === false)
		{
			return false;
		}
		return true;
	}
	
	public function GetPassword()
	{
		$sth	= $this->db->prepare("SELECT password
					FROM ".MYSQL_PREFIX."users
					WHERE id = :userID");
		$sth->execute(array(":userID" => $this->user_id));
		$row	= $sth->fetch();
		return $row['password'];
	}
	
	public function GetSalt()
	{
		$sth	= $this->db->prepare("SELECT salt
					FROM ".MYSQL_PREFIX."users
					WHERE id = :userID");
		$sth->execute(array(":userID" => $this->user_id));
		$row	= $sth->fetch();
		return $row['salt'];
	}
	
	public function CreateUser($username, $password, $email)
	{
		$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."users
									(username, password, salt, email, session_key)
									VALUES
									(:username, :password, :salt, :email, :session_key)");
		
		$created_password	= $this->CreatePasswordHash($password);
		$session_key		= substr(md5(time()), 0,6);
		
		$sth->bindParam(":username",		$username);
		$sth->bindParam(":password",		$created_password[0]);
		$sth->bindParam(":salt",			$created_password[1]);
		$sth->bindParam(":email",			$email);
		$sth->bindParam(":session_key",		$session_key);
		
		if($sth->execute())
		{
			return true;
		}
		return false;
	}
	
	public function ExistsUsername($username)
	{
		$sth	= $this->db->prepare("SELECT username FROM ".MYSQL_PREFIX."users
									WHERE username LIKE :username");
		$sth->execute(array("username" => $username));
		
		if($sth->fetcHAll())
		{
			return true;
		}
		return false;
	}
	
	public function GetNewPasswordHash($password, $username)
	{
		$this->SetUserIdByUsername($username);
		$hash	= $this->CreatePasswordHash($password, $this->GetSalt());
		
		return $hash[0];
	}
	
	/**
	 * 
	 * @param string $password
	 * @return array (hash, salt)
	 */
	private function CreatePasswordHash($password, $salt="")
	{
		if(empty($salt))
		{
			$salt = md5(mt_rand());
		}
		$hash = hash('sha512', $password);
		
		if ( preg_match('#[0-4]#i', $password) )
		{
			crypt($hash, substr($salt, 0, 2));
		}
		if ( preg_match('#[5-9]#i', $password) )
		{
			crypt($hash, substr($salt, 0, 9));
		}
		if ( preg_match('#[bcfgk]#i', $password) )
		{
			crypt($hash, substr($salt, 0, 12));
		}
		if ( preg_match('#[lmowz]#i', $password) ) 
		{
			crypt($hash, substr($salt, 0, 16));
		}
		$len = strlen($password);
		for ( $i=0; $i < $len; $i++ )
		{
			$hash = hash('sha512', $hash . $salt);
		}
		
		return array($hash, $salt);
	}
	
	public function SetLogin()
	{
		$this->session['user_id'] = $this->user_id;
		$this->SetSessionFingerprint($this->user_id);
		return true;
	}
	
	public function SetLogout()
	{
		$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."users SET
									session_fingerprint	= '',
									session_key			= :session_key
									WHERE id			= :user_id");
		$sth->bindParam(":session_key",			$session_key);
		$sth->bindParam(":user_id",				$this->session['user_id']);
		
		if($sth->execute())
		{
			unset($this->session['user_id']);
			return true;
		}
		return false;
	}
	
	public function GetUserId()
	{
		return $this->user_id;
	}
	
	public function GetAllUserIds()
	{
		$sth	= $this->db->query("SELECT id
									FROM ".MYSQL_PREFIX."users");
		return $sth->fetchAll();
	}
	
	public function GetUsernameById($user_id)
	{
		$sth	= $this->db->prepare("SELECT username
									FROM ".MYSQL_PREFIX."users
									WHERE id = :user_id");
		$sth->execute(array(":user_id" => $user_id));
		$row	= $sth->fetch();
		return $row['username'];
	}
	
	public function GetEmail($user_id)
	{
		$sth	= $this->db->prepare("SELECT email
				FROM ".MYSQL_PREFIX."users
				WHERE id = :user_id");
		$sth->execute(array(":user_id" => $user_id));
		$row	= $sth->fetch();
		return $row['email'];
	}
}