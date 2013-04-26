function cambiaClaseborrarCh (obj) {
//	alert (obj.className);
	if (obj.className == 'ch_borrar')
		obj.className = 'ch_borrarOn';
	else
		obj.className = 'ch_borrar';	
	}
	
	
function noValorEnOptimo (id) {
	obj = document.getElementById('tipoincremento' + id ) ;
	if (obj.options[obj.selectedIndex].value == 4 ) {
		document.getElementById('fila' + id ).value = '';
		document.getElementById('fila' + id ).disabled = true;
		}
	else 
		document.getElementById('fila' + id ).disabled = false;
	}