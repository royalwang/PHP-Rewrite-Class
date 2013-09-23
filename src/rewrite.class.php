<?php
/**
*
*	Rewrite Class
*
*	@author		Olaf Erlandsen C. [Olaf Erlandsen]
*	@author		olaftriskel@gmail.com
*
*	@package	Rewrite
*	@copyright	Copyright 2013, Olaf Erlandsen
*	@copyright	Dual licensed under the MIT or GPL Version 2 licenses.
*	@version	1.0
*
*/
class Rewrite
{
	/**
	*	Emulate SERVER variable
	*
	*	@param	string	$requestOrder
	*	@param	string	$defaul
	*/
	static public function requestVarSimulator( $requestOrder = null , $defaul = null )
	{
		$isStatic = !(isset($this) && get_class($this) == __CLASS__);
		$requests = array();
		if( empty($requestOrder) )
		{
			$getRequestOrder = str_split(ini_get ('request_order'));
		}else{
			$getRequestOrder = str_split($requestOrder);
		}
		krsort( $getRequestOrder );
		foreach ( $getRequestOrder AS $request )
		{
			$request = trim( strtolower( $request ) );
			switch ( $request )
			{
				case 'p':
					$requests = array_merge( $_POST , $requests );
				break;
				
				case 'g':
					if( $isStatic === true )
				 	{
				 		$requests = array_merge( self::getMethod() , $requests );
					}else{
						$requests = array_merge( $this->getMethod() , $requests );
					}
				break;
				
				case 'c':
					$requests = array_merge( $_COOKIE , $requests );
				break;
				
				case 's':
					$requests = array_merge( $_SERVER ,$requests );
				break;
				
				case 'e':
					$requests = array_merge( $_ENV, $requests );
				break;
			}
		};
		return $requests;
	}
	/**
	*	Parse url and extract GET vars
	*
	*/
	static public function getMethod( )
	{
		$stringQueryToArray = array();
		$match = array();
		preg_match( '/\?([\w\d\D\W]*)/i' ,$_SERVER['REQUEST_URI'],$match);
		if( array_key_exists(1,$match) )
		{
			parse_str( $match[1] , $stringQueryToArray);
		}
		return $stringQueryToArray;
	}
	/**
	*	Get var by key
	*
	*	@param	string	$key
	*	@param	mixed	$default
	*/
	public static function get( $key , $default = null )
	{
		$get = self::getMethod();
		if( array_key_exists( $key , $get ) )
		{
			return $get[ $key ];
		}
		return $default;
	}
	/**
	*	Parse url segments
	*
	*	@param	string	$segments
	*/
	static public function segments( $segments = null )
	{
		$segmentsToArray = array();
		if( !empty( $segments ) )
		{
			$segments = iconv( "UTF-8",'ISO-8859-1//TRANSLIT',urldecode($segments));
		}else{
			$segments = iconv( "UTF-8",'ISO-8859-1//TRANSLIT',urldecode($_SERVER['REQUEST_URI']));
		}
		$segments = preg_replace( '/(\.(s)?htm(l)?)?(\?)+(.*?)$/i' , '' ,$segments);
		$segments = explode( '/' , $segments );
		if( count( $segments ) > 0 )
		{
			foreach( $segments AS $segment )
			{
				$segment = preg_replace('/(\.(s)?htm(l)?|\.php)$/','',$segment);
				if( !empty( $segment ) )
				{
					if( strlen(trim($segment)) > 0 )
					{
						$segmentsToArray[] = urldecode($segment);
					}
				}
			};
		};
		return $segmentsToArray;
	}
	/**
	*	Create URL from segments string
	*
	*	@param	string	$segments
	*/
	public static function createUrl( $segments )
	{
		$uri = 'http';
		if ( array_key_exists('HTTPS' , $_SERVER ) AND $_SERVER["HTTPS"] == "on")
		{
			$uri .= "s";
		}
		$uri .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$uri .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		} else {
			$uri .= $_SERVER["SERVER_NAME"];
		}
		$uri .=  '/' . $segments ;
		return preg_replace( '/(\/+)/' , '/' , $uri );
	}
}
