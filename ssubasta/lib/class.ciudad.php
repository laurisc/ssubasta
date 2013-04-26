<?php
class ciudad {
	var $ciudad = array();
	
	function ciudad ($ciudad) {
		$this->ciudad = $ciudad;
//		return $this->validarUsuario ();		
		}	
		
	function creaOptionSelect ($id = 0) {
		$select = '';
		foreach ($this->ciudad as $key => $valor) {
//			echo $id ."==". $key . "<br />";
			$select .= ' <option value="' . $key . '"';
			$select .= ($id == $key ) ? ' selected="selected" ' : '';
			$select .= '>' . $valor . '</option>';
			
			}
		return $select;
		}
	
		
	function validarUsuario () {
		if (empty($this->ciudad)) return false;
		return true;
		}
	
	
	
	}
	
?>