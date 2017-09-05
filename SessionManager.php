<?php

class SessionManager
{
	static function sessionStart($name = null, $limit = 0, $path = '/', $domain = null, $secure = null)
	{
		
		if( isset($name) )
			session_name($name . '_Session');
		
		$domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);
		$https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);
		
		
		//session_set_cookie_params($limit, $path, $domain, $secure, true);
		session_start();
		
		if( self::validateSession() )
		{
			if( !self::preventHijacking() )
			{
				$_SESSION = array();
				$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
				self::regenerateSession();
			}
		}
		
		elseif( rand(1, 100) <= 5 )
		{
			self::regenerateSession();
		}
		
		else
		{
			$_SESSION = array();
			session_destroy();
			session_start();
		}
	}
	
	static function regenerateSession()
	{
		if( isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true )
			return;
		
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + 10;
		
		session_regenerate_id(false);
		
		$newSession = session_id();
		session_write_close();
		
		session_id($newSession);
		session_start();
		
		unset($_SESSION['OBSOLETE']);
		unset($_SESSION['EXPIRES']);
	}
	
	static protected function validateSession()
	{
		if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
			return false;
		
		if( isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time() )
			return false;
		
		return true;
	}
	
	static protected function preventHijacking()
	{
		if( !isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']) )
			return false;
		
		if( $_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'] )
			return false;
		
		if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'] )
			return false;
		
		return true;
	}
}



