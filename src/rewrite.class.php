<?php
/**
*	cURL
*
*	rewrite CLASS
*
*	@author		Olaf Erlandsen C. [Olaf Erlandsen]
*	@author		http://www.arcanussystems.com
*
*	@package	cURL
*	@copyright	Copyright 2012, Olaf Erlandsen
*	@copyright	Dual licensed under the MIT or GPL Version 2 licenses.
*	@copyright	http://www.arcanussystems.com/license
*	@version	0.1
*
*/
class rewrite
{
	private $get = array();
	private $rewrite = array();
	private $root = null;
	
	public function __construct( $root = null )
	{
		if( !empty($root) )
		{
			$this->root = $root;
		}else{
			$this->root = null;
		}
		
		$this->_rewrite();
		$this->_get();
	}
	/**
	*	Return "GET" array
	*
	*	@method	array	get( )
	*/
	public function get()
	{
		return $this->get;
	}
	/**
	*	Return "REWRITE" array
	*
	*	@method	array	rewrite( )
	*/
	public function rewrite()
	{
		return $this->rewrite;
	}
	/**
	*	Parse URL and extract "REWRITE"
	*
	*	@method	array	_rewrite( [ string SERVER_REQUEST_URI = null ] )
	*	@param	string	$SERVER_REQUEST_URI
	*/
	private function _rewrite( $SERVER_REQUEST_URI = null )
	{
		if( empty( $SERVER_REQUEST_URI ) )
		{
			$SERVER_REQUEST_URI = $_SERVER['REQUEST_URI'];
		}
		$__path__ = parse_url($this->root,PHP_URL_PATH);
		if( strlen( $__path__ ) > 0)
		{
			$__path__ = preg_quote($__path__);
			$__path__ = preg_replace('/^(\/)/i', '(/)?', $__path__);
			$__path__ = preg_replace('/(\/)/i','\\/', $__path__);
			$_REQUEST_URI = preg_replace( "/^(".$__path__.")/i" , '' , $SERVER_REQUEST_URI );
		}
		else
		{
			$_REQUEST_URI = $SERVER_REQUEST_URI;
		}
		$uri = preg_replace( '/(\.(s)?htm(l)?)?(\?)+(.*?)$/i' , '' ,$_REQUEST_URI);
		$segments = explode('/',$uri);
		if( count( $segments ) > 0 )
		{
			foreach( explode('/',$uri) as $rewrite )
			{
				$rewrite = preg_replace('/(\.(s)?htm(l)?|\.php)$/','',$rewrite);
				if( !is_null($rewrite) AND is_string($rewrite) )
				{
					if( strlen(trim($rewrite)) > 0 )
					{
						$this->rewrite[] = urldecode($rewrite);
					}
				}
			}
		}
	}
	/**
	*	Parse URL and extract "$_GET"
	*
	*	@method	array	_get()
	*/
	public function _get()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$match = array();
		preg_match( '/(\.html)?\?([\w\d\D\W]*)/i' , $uri,$match );
		if( array_key_exists(2,$match) )
		{
			foreach( explode( '&' , $match[2] ) as $get )
			{
				if( !is_null($get) AND strlen($get) > 0 )
				{
					if( strpos( $get , '=') != false )
					{
						$position	= strpos( $get , '=');
						$key 		= urldecode(substr( $get, 0 , $position ));
						$value 		= urldecode(substr( $get, $position+1 ));
					}
					else
					{
						$key 		= urldecode(substr( $get, 0 ));
						$value 		= null;
					}
					if( preg_match( '/([a-z0-9-_\.]\[(.*?)\])/i' , $key ) )
					{
						$array = str_replace(array('[',']'),'',substr($key,strpos($key,'[')));
						$key = preg_replace('/\[(.*?)\]/i','',$key);
						if( array_key_exists( $key , $this->get ) )
						{
							if( !is_array( $this->get[$key] ) )
							{
								$this->get[$key] = array();
							}
						}
						else
						{
							$this->get[$key] = array();
						}
						if( strlen( $array ) == 0 )
						{
							$this->get[$key][] = $value;
						}
						else
						{
							$this->get[$key][$array] = $value;
						}
					}
					else
					{
						if( is_array( $this->get ) AND array_key_exists( $key , $this->get ) )
						{
							if( !is_array( $this->get[$key] ) )
							{
								$this->get[$key] = $value;
							}
						}
						else
						{
							$this->get[$key] = $value;
						}
					}
				}
			}
		}
	}
}
?>