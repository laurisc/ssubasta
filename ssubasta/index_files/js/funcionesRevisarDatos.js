
//Desactiva el campo de representante legal cuando se escoge C.C no NIT
function desactivarRepresentanteLegal()
{
	var indice = document.frm_registro.tipoDocumento.selectedIndex;
	var valor = document.frm_registro.tipoDocumento.options[indice].value;
	if(valor==1)
	{
		document.frm_registro.representanteLegal.disabled = true;
		document.frm_registro.representanteLegal.value = "";
	}
	else if(valor==2)
	{
		document.frm_registro.representanteLegal.disabled = false;
	}
}

// Revisa que el objeto text tenga s�lo d�gitos o este vac�o
function esCorrectoDigitos(objeto)
{
	var valorActual = objeto.value;
	if(esDigitos(valorActual) || valorActual == "")
	{
		objeto.style.color = "black";
	}
	else
	{
		objeto.style.color = "red";
	}
}

// Revisa que el objeto text tenga s�lo d�gitos o este vac�o
function esCorrectoDoubleHastaTres(objeto)
{
	var valorActual = objeto.value;
	if(esDigitos(valorActual) || esDoubleTres(valorActual) || valorActual <= 3 || valorActual >= 0 || valorActual == "")
	{
		objeto.style.color = "black";
	}
	else
	{
		objeto.style.color = "red";
	}
}

// Revisa que el objeto text tenga s�lo d�gitos o doubles o este vac�o
function esCorrectoDouble(objeto)
{
	var valorActual = objeto.value;
	if(esDigitos(valorActual) || esDouble(valorActual) || valorActual == "")
	{
		objeto.style.color = "black";
	}
	else
	{
		objeto.style.color = "red";
	}
}

// Revisa que el objeto text sea un Nit
function esCorrectoNit(objeto)
{
	var valorActual = objeto.value;
	if(!!esNit(valorActual))
	{
		objeto.style.color = "black";
	}
	else
	{
		objeto.style.color = "red";
	}
}

// Revisa que el objeto text no tenga n�mero
function esCorrectoAlfa(objeto)
{
	var valorActual = objeto.value;
	if(!tieneDigitos(valorActual))
	{
		objeto.style.color = "black";
	}
	else
	{
		objeto.style.color = "red";
	}
}