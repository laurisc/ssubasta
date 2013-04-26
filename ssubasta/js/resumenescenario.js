/* Actualiza resumen del escenario */

function resumenVendedor () {
	tVendedor = document.getElementsByName('ch_vendedor[]');
	totalVendedores = 0;
	totalCampos = 0;
	arrayCampos = new Array ();
	rec = 0;

	for (i = 0 ; i < tVendedor.length ; i++ ) {
		if (tVendedor[i].checked == true) {
			totalVendedores++;
			tCamp = tVendedor[i].value.split('|');
			arrayCampos[rec] = tCamp[0];
			rec++;
		}
	}
	
	arrayCampos.sort();
	for (i = 0 ; i < arrayCampos.length ; i++ ) {
			if (arrayCampos[i] != arrayCampos[i + 1 ] )
				totalCampos++;
		}	
	document.getElementById('tVendedores').innerHTML = totalVendedores;
	document.getElementById('tCampos').innerHTML = totalCampos;		
	document.getElementById('tVendedoresH').value = totalVendedores;
	document.getElementById('tCamposH').value = totalCampos;		
}

function resumenEscenario () {
	tComprador = document.getElementsByName('ch_comprador[]');
	totalCompradores = 0;
	for (i = 0 ; i < tComprador.length ; i++ ) {
		if (tComprador[i].checked == true)
			totalCompradores++;
	}
	document.getElementById('tCompradores').innerHTML = totalCompradores;
	document.getElementById('tCompradores').value = totalCompradores;
}