<?php
	
	function conexion(){
		// Establecer los datos de conexión
		$host = "sql307.infinityfree.com"; // Cambia esto por tu host si es diferente
		$usuario = "if0_36432036";
		$contraseña = "YzpKOMt5JVO";
		$base_de_datos = "if0_36432036_nopaltrace";
	
		// Crear una nueva instancia de conexión a la base de datos
		$mysqli = new mysqli($host, $usuario, $contraseña, $base_de_datos);
	
		// Verificar si hay errores de conexión
		if ($mysqli->connect_errno) {
			// Si hay un error, imprimirlo y salir del script
			echo "Error al conectar a MySQL: " . $mysqli->connect_error;
			exit();
		}
	
		// Si la conexión es exitosa, imprimir mensaje de éxito y devolver la instancia de conexión
		
		return $mysqli;
	}
	


