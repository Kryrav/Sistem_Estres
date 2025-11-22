<?php 
	
	//define("BASE_URL", "http://localhost/SistemaCarpeta/");
	const BASE_URL = "http://localhost/gestion_estres";

	//Zona horaria Bolivia
	date_default_timezone_set('America/La_Paz');


	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "gestion_estres"; //Creal la base de datos pruebausuarios para ejecutar el sistema
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "Bs";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Sistema";
	const EMAIL_REMITENTE = "no-reply@rene.com";
	const NOMBRE_EMPESA = "NaraServices";
	const WEB_EMPRESA = "www.rene@gmail.com";
	



	


 ?>