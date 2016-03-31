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
	return today;
}

/*
 * Recebe o método (GET ou POST), o objeto dos parâmetros, e a função que será chamada ao concluir
 * A função ao concluir recebe o objeto JSON decodificado
 */
function AJAXRequest(method, oParams, onComplete){

  	var Ajax = new XMLHttpRequest();
  	
    Ajax.open(method, "http://localhost:4343/EJNS/index.php?oParams="+JSON.stringify(oParams), true);
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