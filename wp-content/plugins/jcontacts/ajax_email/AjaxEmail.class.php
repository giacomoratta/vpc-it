<?php

class AjaxEmail
{
	private $myEmail = "visitportocesareo@gmail.com";
	
	public $error_empty = false;
	
	/*
	
	- header javascript
	
	- form email .html
		-> javascript disabled => link form + msg j_enabled
	
	- file .php
	
	- check empty input
	- check error input
	- input prepare
	- funzione mail
	
	*/
	

	
	function check_empty_fields($array,$fields)
	{
		$keys_a = array_keys($array);
		$keys_f = array_keys($fields);
		$empty = array();
		
		foreach($keys_a as $k)
		{
			if(!in_array($k,$keys_f)) continue;
			$empty[$k]=0;
			if(strlen($array[$k])<=0) $empty[$k]=1;
		}
		
		foreach($keys_f as $k)
		{
			if(isset($empty[$k]) && $empty[$k]==1 && $fields[$k]==1)
			{
				$this->error_empty=true;
				break;
			}
		}
		return $empty;
	}
	
	
	function is_email($string)
	{
		return preg_match('/^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/i',$string);
	}
	
	
	function send_email($to, $name, $email, $subject, $message)
	{
		$headers = "";
		$headers .= 'Content-Type: text/plain; charset="utf-8"'."\r\n";
		$headers .= 'To: '.$to."\r\n";
		$headers .= 'From: '.$name.' <'.$email.'>'."\r\n";
		
		if(@mail($to, $subject, $message, $headers))
		{
			/*copia*/ @mail($this->myEmail, $subject, $message, $headers);
			return true;
		}
		
		return false;
	}
}

?>