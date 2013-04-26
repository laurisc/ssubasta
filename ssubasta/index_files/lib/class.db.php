<?php 
class db {
	var $cDB = '';
	var $sDB = '';
	
	# se inicia la conexion al servidor	
	function conectarDB () {
		$this->cDB = mysql_connect('localhost', 'essimgas_simulac', '5ub45t4');
		}
	
	# se conectar a la base de datos
	function seleccionDB () {
		$this->sDB = mysql_select_db('essimgas_ssubastas', $this->cDB);
		}	
	
	# se cierra la conexion a la base de datos
	function desconectar () {
		mysql_close ($this->cDB);
		}
			
	# @sql: script sql a ejecutar en la base de datos	
	function SQL ($sql) {
//		echo $sql . "<br /><br />";
		return mysql_query ($sql);
		}
		
	function darArray ($res) {
		}
	
	}

	
?>