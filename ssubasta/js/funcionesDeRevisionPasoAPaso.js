	function revisarHayVendedorEscogido()
	{	
		var entro = false;
		var tablaVendedores = document.getElementById("vendedores");
		for (i=1;i<tablaVendedores.rows.length;i++)
		{
			var escogido = document.getElementById("ch_vendedor" + i);
			if(escogido!=null && escogido.type=="checkbox" && escogido.checked==true)
			{
				entro = true;
			} 
		}
		if(!entro)
		{
			alert("Por favor escoja al menos un vendedor para continuar.");
		}
		return entro;
	}
	
	function revisarInformacionCorrectaVendedores()
	{
		if (typeof String.prototype.startsWith != 'function') {
		// see below for better implementation!
			String.prototype.startsWith = function (str){
				return this.indexOf(str) == 0;
			};
		}
		
		var entro = true;
		var frm = document.getElementById("frm_iniesc");
		for (i=0;i<frm.elements.length;i++)
		{
			if(frm.elements[i].type=="text")
			{
				if(frm.elements[i].style.color == 'red')
				{
					entro = false;
					alert("Por favor revisar lo datos en rojo.");
					break;
				}				
			}
			else if(frm.elements[i].id.startsWith("ptdvf"))
			{
					if(!(frm.elements[i].value > 0))
					{
						entro = false;
						alert("Por favor ingresar cantidades a todos los vendedores.");
						break;
					}
			}
		}
		
		return entro;
		//Revisar que haya precios ingresados por producto > 0
		document.getElementById("fijo-" + clave);
		document.getElementById("condicional-" + clave);
		document.getElementById("opcional-" + clave);
		document.getElementById("fijoU-" + clave);
		document.getElementById("condicionalU-" + clave);
		document.getElementById("opcionalU-" + clave);		
	}
	
	// Revisa que los datos esten correctos
	function revisarInformacionCorrectaCompradores()
	{
		var entro = true;
		var hayUnoEscogido = false;
		var tablaCompradores = document.getElementById("compradores");
		for (j=1;j<tablaCompradores.rows.length;j++)
		{	
			var estaEscogido = document.getElementById( "ch_comprador" + j).checked;
			if(estaEscogido)
			{
				hayUnoEscogido = true;
				var demanda = document.getElementById( "demanda" + j);
				var demandaSumada = darValorDemanda(j);
				//alert(estaEscogido + "--" + i + "--" + demanda.value + "--" + demandaSumada);
				if(demanda.value == "" )
				{
					alert("Por favor revisar la demanda de los compradores. Mirar la fila " + j + ".");
					entro = false;
					break;
				}
				else if(darSiHayRojo())
				{
					alert("Por favor revisar la informaci�n que se encuentra en rojo.");
					entro = false;
					break;
				}
				else if(demanda.value != demandaSumada)
				{
					if(demandaSumada == 0) {
						alert("Mirar la fila " + j + ". No hay demanda ingresada en los productos.");
					}
					else {
						alert("La demanda debe ser igual a la suma de los productos. Mirar la fila " + j + ". El valor debe ser " + demandaSumada + ".");
					}
					entro = false;
					break;
				}
			}
		}
		
		if(!hayUnoEscogido)
		{
			entro = false;
			alert("Por favor escoger al menos un comprador");
		}
		
		return entro;
	}
	
	function darValorDemanda(fila) {
		var suma = 0;
		var frm = document.getElementById("frm_iniesc");
		var dato1 = document.getElementsByName("dFirmeC[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}
		

		var dato1 = document.getElementsByName( "dFirme[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}

		var dato1 = document.getElementsByName( "dOcgC[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}

		var dato1 = document.getElementsByName( "dOcgU[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}

		var dato1 = document.getElementsByName( "dCfcC[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}

		var dato1 = document.getElementsByName( "dCfcU[" + fila + "][]");
		for (i=0;i<dato1.length;i++)
		{
			if(parseInt(dato1[i].value)>0)
				suma += parseInt(dato1[i].value);
		}
		return suma;
	}
	
	function darSiHayRojo() {

		var frm = document.getElementById("frm_iniesc");
		for (i=0;i<frm.elements.length;i++)
		{
			if(frm.elements[i].type=="text")
			{
				if(frm.elements[i].style.color == 'red')
				{
					return true;
				}				
			}
		}
		return false;
	}


	
	// Revisa que los datos esten correctos
	function revisar()
	{	
		var entro = true;
		var frm = document.getElementById("frm_iniesc");
		for (i=0;i<frm.elements.length;i++)
		{
			if(frm.elements[i].type=="text")
			{
				if(frm.elements[i].style.color == 'red' || frm.elements[i].value=="")
				{
					entro = false;
					alert("Por favor revise las tarifas.");
					break;
				}
				
			}
		}
		return entro;
	}