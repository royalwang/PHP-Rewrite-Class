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
*	@version	0.7
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
	*	Parse URL and extract "REWRITE"
	*
	*	@method	array	rewrite( [ string $segments = null ] )
	*	@param	string	$segments
	*/
	public function rewrite( $segments = null )
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
	*	Parse URL and extract "$_GET"
	*
	*	@method	array	_get()
	*/
	public function get()
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
	*	Return "$_REQUEST"
	*
	*	@method	array	requestVarSimulator()
	*/
	public function requestVarSimulator( $requestOrder = null , $defaul = null )
	{
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
			 if( $request== 'p' )
			 {
			 	$requests = array_merge( $_POST , $requests );
			 }
			 else if( $request == 'g' )
			 {
			 	$requests = array_merge( $this->getMethod() , $requests );
			 }
			 else if( $request== 'c' )
			 {
			 	$requests = array_merge( $_COOKIE , $requests );
			 }
			 else if( $request== 's' )
			 {
			 	$requests = array_merge( $_SERVER ,$requests );
			 }
			 else if($request=='e')
			 {
			 	$requests = array_merge( $_ENV, $requests );
			 }
		};
		return $requests;
	}
}
?>
