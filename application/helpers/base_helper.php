<?php
if (!defined('BASEPATH')) exit('Sin permisos para acceder al script');
	
	function obtenerConexion($conexion)
	{
		$db['remota'] = array
		(
			'dsn'			=> '',
			
			'hostname' 		=> $conexion->servidor,
			'username'		=> $conexion->usuario,
			'password' 		=> $conexion->pass,
			'database' 		=> $conexion->base,
			'dbdriver' 		=> 'mysqli',
			'dbprefix' 		=> '',
			'pconnect' 		=> TRUE,
			'db_debug' 		=> (ENVIRONMENT !== 'production'),
			'cache_on' 		=> FALSE,
			'cachedir' 		=> '',
			'char_set' 		=> 'utf8',
			'dbcollat' 		=> 'utf8_general_ci',
			'swap_pre' 		=> '',
			'encrypt' 		=> FALSE,
			'compress'	 	=> FALSE,
			'stricton' 		=> FALSE,
			'failover' 		=> array(),
			'save_queries' 	=> TRUE
		);
		
		return $db['remota'];
	}
?>
