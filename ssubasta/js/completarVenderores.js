/* Actualiza la suma de PTDVF */

function sumaTotales (clave) {
	
	total = 	parseFloat (document.getElementById("fijo-" + clave).value) + 
				parseFloat (document.getElementById("condicional-" + clave).value) + 
				parseFloat (document.getElementById("opcional-" + clave).value) + 
				parseFloat (document.getElementById("fijoU-" + clave).value) + 
				parseFloat (document.getElementById("condicionalU-" + clave).value) + 
				parseFloat (document.getElementById("opcionalU-" + clave).value);
	
		document.getElementById("ptdvf-" + clave).value = total.toString();
		document.getElementById("sPtdvf-" + clave).innerHTML = total.toString();
}