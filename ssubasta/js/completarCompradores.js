	function agregarCiudad(fila, contador) {
		//Actualiza la cantidad de filas
		document.getElementById("cantidadFilas" + fila).value++;
		var cantidadFilas =	document.getElementById("cantidadFilas" + fila).value;

		//Crea la lista de ciudades
		selectInicial = document.getElementById("ciudad"+contador);		
		selectTemp = document.createElement("select");
		selectTemp.name = selectInicial.name;
		selectTemp.id = selectInicial.id + cantidadFilas;
		
		for (i=0;i<selectInicial.length;i++)
		{
			opcion = document.createElement("option");
			opcion.value = selectInicial[i].value;
			opcion.innerHTML = selectInicial[i].innerHTML;
			selectTemp.appendChild(opcion);
		}
		
		//Crea los campos para ingresar los diferentes tipos de contratos
		var campoActual1 = document.getElementById( "dFirmeU"  + fila);
		var campoActual2 = document.getElementById( "dCfcU" + fila);
		var campoActual3 = document.getElementById( "dOcgU" + fila);
		var campoActual4 = document.getElementById( "dFirme" + fila);
		if(campoActual4 == null)
			var campoActual4 = document.getElementById( "dFirmeC" + fila);
		
		var campoActual5 = document.getElementById( "dCfc"  + fila);
		var campoActual6 = document.getElementById( "dOcg" + fila);
		
		var campoTemp1 = document.createElement("input");
		var campoTemp2 = document.createElement("input");
		var campoTemp3 = document.createElement("input");
		var campoTemp4 = document.createElement("input");
		var campoTemp5 = document.createElement("input");
		var campoTemp6 = document.createElement("input");
		
		campoTemp1.type = campoActual1.type;
		campoTemp1.id = campoActual1.id + cantidadFilas;
		campoTemp1.name = campoActual1.name;
		campoTemp1.onkeyup = campoActual1.onkeyup;
		campoTemp1.className = campoActual1.className;
		campoTemp1.disabled = campoActual1.disabled;
		
		campoTemp2.type = campoActual2.type;
		campoTemp2.id = campoActual2.id + cantidadFilas;
		campoTemp2.name = campoActual2.name;
		campoTemp2.onkeyup = campoActual2.onkeyup;
		campoTemp2.className = campoActual2.className;
		campoTemp2.disabled = campoActual2.disabled;

		campoTemp3.type = campoActual3.type;
		campoTemp3.id = campoActual3.id + cantidadFilas;
		campoTemp3.name = campoActual3.name;
		campoTemp3.onkeyup = campoActual3.onkeyup;
		campoTemp3.className = campoActual3.className;
		campoTemp3.disabled = campoActual3.disabled;

		campoTemp4.type = campoActual4.type;
		campoTemp4.id = campoActual4.id + cantidadFilas;
		campoTemp4.name = campoActual4.name;
		campoTemp4.onkeyup = campoActual4.onkeyup;
		campoTemp4.className = campoActual4.className;
		campoTemp4.disabled = campoActual4.disabled;

		campoTemp5.type = campoActual5.type;
		campoTemp5.id = campoActual5.id + cantidadFilas;
		campoTemp5.name = campoActual5.name;
		campoTemp5.onkeyup = campoActual5.onkeyup;
		campoTemp5.className = campoActual5.className;
		campoTemp5.disabled = campoActual5.disabled;

		campoTemp6.type = campoActual6.type;
		campoTemp6.id = campoActual6.id + cantidadFilas;
		campoTemp6.name = campoActual6.name;
		campoTemp6.onkeyup = campoActual6.onkeyup;
		campoTemp6.className = campoActual6.className;
		campoTemp6.disabled = campoActual6.disabled;

		dondePegar = document.getElementById("compradores");
		contador++;
		dondePegar.rows[contador].cells[5].appendChild(selectTemp);
		
		var vacio = document.createElement("span");
		vacio.setAttribute("style","height:20px;margin:10px;display:block");
		vacio.setAttribute("id","span"+fila+cantidadFilas);
		dondePegar.rows[contador].cells[5].appendChild(vacio);
		
		dondePegar.rows[contador].cells[7].appendChild(campoTemp1);
		dondePegar.rows[contador].cells[8].appendChild(campoTemp2);
		dondePegar.rows[contador].cells[9].appendChild(campoTemp3);
		dondePegar.rows[contador].cells[10].appendChild(campoTemp4);
		dondePegar.rows[contador].cells[11].appendChild(campoTemp5);
		dondePegar.rows[contador].cells[12].appendChild(campoTemp6);
		
		//Crear los campos para las elasticidades
		var elastActual1 = document.getElementById( "elasticidadFirme"  + fila);
		var elastActual2 = document.getElementById( "elasticidadDcfc"  + fila);
		var elastActual3 = document.getElementById( "elasticidadDocg"  + fila);
		var elastActual4 = document.getElementById( "elasticidadDfirmeC"  + fila);
		var elastActual5 = document.getElementById( "elasticidadDcfcC"  + fila);
		var elastActual6 = document.getElementById( "elasticidadDocgC"  + fila);
		
		var elastTemp1 = creaSelectElasticidad(elastActual1.name, elastActual1.id + cantidadFilas, elastActual1.disabled);
		var elastTemp2 = creaSelectElasticidad(elastActual2.name, elastActual2.id + cantidadFilas, elastActual2.disabled);
		var elastTemp3 = creaSelectElasticidad(elastActual3.name, elastActual3.id + cantidadFilas, elastActual3.disabled);
		var elastTemp4 = creaSelectElasticidad(elastActual4.name, elastActual4.id + cantidadFilas, elastActual4.disabled);
		var elastTemp5 = creaSelectElasticidad(elastActual5.name, elastActual5.id + cantidadFilas, elastActual5.disabled);
		var elastTemp6 = creaSelectElasticidad(elastActual6.name, elastActual6.id + cantidadFilas, elastActual6.disabled);
	
		dondePegar.rows[contador].cells[7].appendChild(elastTemp1);
		dondePegar.rows[contador].cells[8].appendChild(elastTemp2);
		dondePegar.rows[contador].cells[9].appendChild(elastTemp3);
		dondePegar.rows[contador].cells[10].appendChild(elastTemp4);
		dondePegar.rows[contador].cells[11].appendChild(elastTemp5);
		dondePegar.rows[contador].cells[12].appendChild(elastTemp6);
	}
	
	function eliminarCiudad(fila, contador) {

		//Busca la cantidad de filas actual
		var cantidadFilas =	document.getElementById("cantidadFilas" + fila).value;
		if(cantidadFilas>1)
		{
			//Busca la tabla en donde se encuentran los campos
			var dondeEliminar = document.getElementById("compradores");
			
			//Buscar el �ltimo select con la lista de ciudades
			var selectAEliminar = document.getElementById("ciudad" + contador + cantidadFilas);

			//Busca los campos de ingreso de datos
			var campoActual1 = document.getElementById( "dFirmeU"  + fila + cantidadFilas);
			var campoActual2 = document.getElementById( "dCfcU" + fila + cantidadFilas);
			var campoActual3 = document.getElementById( "dOcgU" + fila + cantidadFilas);
			var campoActual4 = document.getElementById( "dFirme" + fila + cantidadFilas);
			if(campoActual4 == null)
				var campoActual4 = document.getElementById( "dFirmeC" + fila + cantidadFilas);
			var campoActual5 = document.getElementById( "dCfc"  + fila + cantidadFilas);
			var campoActual6 = document.getElementById( "dOcg" + fila + cantidadFilas);
		
			var elastActual1 = document.getElementById( "elasticidadFirme"  + fila + cantidadFilas);
			var elastActual2 = document.getElementById( "elasticidadDcfc"  + fila + cantidadFilas);
			var elastActual3 = document.getElementById( "elasticidadDocg"  + fila + cantidadFilas);
			var elastActual4 = document.getElementById( "elasticidadDfirmeC"  + fila + cantidadFilas);
			var elastActual5 = document.getElementById( "elasticidadDcfcC"  + fila + cantidadFilas);
			var elastActual6 = document.getElementById( "elasticidadDocgC"  + fila + cantidadFilas);
			
			var span = document.getElementById("span"  + fila + cantidadFilas);

			//Elimina los campos
			contador++;
			dondeEliminar.rows[contador].cells[5].removeChild(selectAEliminar);
			dondeEliminar.rows[contador].cells[5].removeChild(span);
			dondeEliminar.rows[contador].cells[7].removeChild(campoActual1);
			dondeEliminar.rows[contador].cells[8].removeChild(campoActual2);
			dondeEliminar.rows[contador].cells[9].removeChild(campoActual3);
			dondeEliminar.rows[contador].cells[10].removeChild(campoActual4);
			dondeEliminar.rows[contador].cells[11].removeChild(campoActual5);
			dondeEliminar.rows[contador].cells[12].removeChild(campoActual6);
			dondeEliminar.rows[contador].cells[7].removeChild(elastActual1);
			dondeEliminar.rows[contador].cells[8].removeChild(elastActual2);
			dondeEliminar.rows[contador].cells[9].removeChild(elastActual3);
			dondeEliminar.rows[contador].cells[10].removeChild(elastActual4);
			dondeEliminar.rows[contador].cells[11].removeChild(elastActual5);
			dondeEliminar.rows[contador].cells[12].removeChild(elastActual6);

			document.getElementById("cantidadFilas" + fila).value--;
		}
	}
	
	function actualizar(objeto)
	{
		
		var id = objeto.id.split('-');
		var cantidad = document.getElementById("cantidadFilas" + id[1]).value;
		
		for(i=0; i<cantidad; i++)
		{
			var complemento = i==0 ? "": i+1;
			var dato1 = document.getElementById( "dCfcU" + id[1] + complemento);
			var dato2 = document.getElementById( "dCfc"  + id[1] + complemento);
			var dato3 = document.getElementById( "dOcgU" + id[1] + complemento);
			var dato4 = document.getElementById( "dOcg" + id[1] + complemento);
			var dato5 = document.getElementById( "elasticidadDcfc" + id[1] + complemento);
			var dato6 = document.getElementById( "elasticidadDocg" + id[1] + complemento);
			var dato7 = document.getElementById( "elasticidadDcfcC" + id[1] + complemento);
			var dato8 = document.getElementById( "elasticidadDocgC" + id[1] + complemento);
			
//			var tmptTexto = new String (objeto.value);
//			alert (tmptTexto.replace(" ","")) ;
			if(objeto.value.trim() == 'B')
			{
				dato1.disabled = true;
				dato2.disabled = true;
				dato1.value = "";
				dato2.value = "";
				dato3.disabled = false;
				dato4.disabled = false;
				dato5.disabled = true;
				dato6.disabled = false;
				dato7.disabled = true;
				dato8.disabled = false;
			}
			else if(objeto.value.trim() == 'A')
			{		
				dato1.disabled = false;
				dato2.disabled = false;
				dato3.disabled = true;
				dato4.disabled = true;
				dato3.value = "";
				dato4.value = "";
				dato5.disabled = false;
				dato6.disabled = true;
				dato7.disabled = false;
				dato8.disabled = true;
			}
		}
	}

	function creaSelectElasticidad (nombre, id, habilitado) {
		
		selectTemp = document.createElement("select");
		selectTemp.name = nombre;
		selectTemp.id = id;
		selectTemp.disabled = habilitado;
		selectTemp.setAttribute("style","width:75px");
		
		//Crear 5 tipos de elasticidad
		for (i=0;i<=1;i+=0.25)
		{
			opcion = document.createElement("option");
			opcion.value = i;
			opcion.innerHTML = i;
			selectTemp.appendChild(opcion);
		}
		return selectTemp;
	}
