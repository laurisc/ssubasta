//Esta funci�n retorna verdadero si la cadena indicada en valor est� compuesta de solo espacios en blanco
  function esBlancos(valor) {
    var myRegExp = /^\s*$/;
	return  (myRegExp.test(valor)); 
  }


  //Esta funci�n retorna verdadero si la cadena indicada en valor est� compuesta de solo digitos
  function esDigitos(valor) {
    var myRegExp = /^[0-9]+$/;
	return  (myRegExp.test(valor)); 
  }

  //Esta funci�n retorna verdadero si la cadena indicada en valor est� compuesta de solo digitos
  function esDouble(valor) {
    var myRegExp = /^([0-9])*\.(([0-9]{2})|[0-9])$/;
	return  (myRegExp.test(valor)); 
  }
  
  function esDoubleTres(valor) {
    var myRegExp = /^([0-9])*\.(([0-9]{3})|([0-9]{2})|[0-9])$/;
	return  (myRegExp.test(valor)); 
  }

  //Esta funci�n retorna verdadero si la cadena indicada en valor es NIT
  function esNit(valor) {
    var myRegExp = /^([0-9]|-|\.)+$/;
	return  (myRegExp.test(valor)); 
  }

 //Esta funci�n retorna verdadero si la cadena indicada en valor incluye al menos un espacio en blanco
  function tieneBlancos(valor) {
    var myRegExp = /.*\s.*/;
	return  (myRegExp.test(valor)); 
  }

 //Esta funci�n retorna verdadero si la cadena indicada en valor incluye al menos un digito
 function tieneDigitos(valor) {
    var myRegExp = /.*[0-9]+.*/;
	return  (myRegExp.test(valor)); 
  }

//Esta funci�n retorna verdadero si la cadena indicada en valor incluye al menos un alfa
 function tieneAlfa(valor) {
    var myRegExp = /.*([A-Z]|[a-z])+.*/;
	return  (myRegExp.test(valor)); 
  }
  
 //Esta funci�n retorna verdadero si la cadena indicada en valor es alfa num�rica (compuesta de digitos, letras
 //  mezcla de digitos y letras
  function esAlfaNum(valor,longitud) {
    var myRegExp = new RegExp("^([0-9]|[A-Z]|[a-z]){" + longitud + ",}$");
	return  (myRegExp.test(valor)); 
  }

  //Esta funci�n retorna verdadero si la cadena indicada en valor corresponde a un e-mail
  function esEmail (emailStr) {
  /* Patron para verificar que el email siga el formato user@domain */
   var emailPat=/^(.+)@(.+)$/
/* Patron para reconocer caracteres especiales validos en un email ( ) < > @ , ; : \ " . [ ]    */
   var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
/* Caracteres validos en un username o dominio. Representa los que no son permitidos */
   var validChars="\[^\\s" + specialChars + "\]"
/* El siguiente patron es para los casos en que el usuario va entre comillas.
   En ese caso no hay restricciones de comillas dobles. */
   var quotedUser="(\"[^\"]*\")"
/* El siguiente patron es para dominios que son direcciones IP.
   Por ejemplo: joe@[123.124.233.4] incluyendo los [] */
   var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
/* El siguiente patron representa un atomo i.e una serie de caracteres no especiales. */
    var atom=validChars + '+'
/* El siguiente patron representa una palabra en un user name.
   Por ejemplo en in john.doe@somewhere.com, john y  doe son palabras.
   Basicamente, una palabra es un atomo o una cadena entre comillas. */
   var word="(" + atom + "|" + quotedUser + ")"
// El siguiente patron describe la estructura de un usuario
   var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
/* El siguiente patron describe la estructura de un dominio simbolico normal
    (no un dominio basado en direccion IP. */
   var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")


/* Inician  las validaciones */

/* Se parte el email en sus diferentes partes para facilitar el analisis */
var matchArray=emailStr.match(emailPat)

if (matchArray==null) {
  /* Muchas o pocas @'s or algo; Basicamente esta direccion no llena los requisitos de un email en general. */
	alert("Email  incorrecto (verifique @  y puntos )")
	return false
}
var user=matchArray[1]
var domain=matchArray[2]

// Verificar si el usuario es valido
if (user.match(userPat)==null) {
    // usuario invalido
    alert("El user name no es valido.")
    return false
}

/* Si el dominio es una IP, verificar que el IP tenga una estructura valida. */
var IPArray=domain.match(ipDomainPat)
if (IPArray!=null) {
    // this is an IP address
	  for (var i=1;i<=4;i++) {
	    if (IPArray[i]>255) {
	        alert("Direcci�n IP destino invalida")
		return false
	    }
    }
    return true
}

// El Domainio es un nombre symbolico
var domainArray=domain.match(domainPat)
if (domainArray==null) {
	alert("El domino del email es invaldo")
    return false
}

/* El dominio en aparencia es correcto, pero debe terminar en una plabra de tres letras (como com, edu, gov)
   o en una palabra de dos letras de pais (co, uk), y debe haber un nombre de host que precede el pais o dominio */

/* Se parte el dominio para saber cuantos atomos contiene */
var atomPat=new RegExp(atom,"g")
var domArr=domain.match(atomPat)
var len=domArr.length
if (domArr[domArr.length-1].length<2 || 
    domArr[domArr.length-1].length>3) {
   // La dirrecion no termina en una palabra de 2 o 3 letras.
   alert("La direcion de email debe terminar en dos letras de pais (co, uk, ...) o en tres de dominio (edu, com, org...)")
   return false
}

// verifcar que haya un nombre de host precediendo el dominio.
if (len<2) {
   alert("El email no incluye el nombre del host")
   return false
}

// La direccion es correcta
return true;
}