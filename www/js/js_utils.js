/* ========== GERAL ==========  */

//formata de forma genérica os campos
function formataCampo(campo, Mascara, evento) { 
    
    var indMascara; 
	var tecla = evento.keyCode;
    campoSoNumeros = campo.value.toString().replace(/\-|\.|\/|\(|\)| /g, "" ); 

    var posicaoCampo   = 0;    
    var novoValorCampo = "";
    var tamanhoMascara = campoSoNumeros.length;; 

    if (tecla != 8) { // backspace 
        for(i=0; i<= tamanhoMascara; i++) { 
            indMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".") || (Mascara.charAt(i) == "/"));
            indMascara  = indMascara || ((Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " "));
            if (indMascara) { 
                novoValorCampo += Mascara.charAt(i); 
                tamanhoMascara++;
            }else { 
                novoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
                posicaoCampo++; 
            }              
   	    }      

    	campo.value = novoValorCampo;
        return true; 
    } else {

        return true; 
    }
}

/* ========== DATAS ==========  */

function getToday() {
	var today = new Date();
	var day   = today.getDate();
	var month = today.getMonth()+1; //January is 0!
	var year  = today.getFullYear();

	if(day < 10) {
	    day = '0'+day
	} 

	if(month < 10) {
	    month ='0'+month
	} 

	today = year+'-'+month+'-'+day;
	return new Date(today);
}

//recebe data no formato que vem do banco e retorna uma string no formato BR
function dateToBR(date) {
	var year  = date.getFullYear();
  	var month = (1 + date.getMonth()).toString();
  	var day   = date.getDate().toString();

  	month     = month.length > 1 ? month : '0'+month;
  	day       = day.length > 1 ? day : '0'+day;
  	return day + '/' + month + '/' + year;
}

//recebe uma data no formato BR e transforma para o formato EN (para inserir no banco)
function dateToEN(date) {

    var parts = date.split("/");
    return parts[2] + "-" + parts[1] + "-" + parts[0];
}

/* ========== AJAX ==========  */ 

/*
 * Recebe o método (GET ou POST), o objeto dos parâmetros, e a função que será chamada ao concluir
 * A função ao concluir recebe o objeto JSON decodificado
 */
function AJAXRequest(method, oParams, onComplete){

  	var Ajax = new XMLHttpRequest();
  	
    //Ajax.open(method, "http://localhost:4343/EJNS/index.php?oParams="+JSON.stringify(oParams), true);
    Ajax.open(method, "http://192.168.0.5:4343/EJNS/index.php?oParams="+JSON.stringify(oParams), true);
    //Ajax.open(method, "http://ejns-app.esy.es/index.php?oParams="+JSON.stringify(oParams), true);
    Ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    Ajax.onreadystatechange = function () {
        
        if (Ajax.readyState == 4) {
            
            if (Ajax.status == 200) {
            
            	var oAjax = eval('('+Ajax.responseText+')');
                window[onComplete](oAjax);

            } else {
                
                alert("Ocorreu algum erro. Contate o administrador!");
            }
        }
    }
    //If an error occur during the Ajax call.
    if (Ajax.readyState == 4 && Ajax.status == 404) {
        alert("Erro na requisição ao servidor. Verifique a conexão com a internet ou contate o administrador.");
    }

    Ajax.send(JSON.stringify(oParams));
}