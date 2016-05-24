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
	var year  = date.getUTCFullYear();
  	var month = (date.getUTCMonth()+1).toString();
  	var day   = date.getUTCDate().toString();

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
  	
    //Ajax.open(method, "http://192.168.0.2:4343/EJNS/control.php?oParams="+JSON.stringify(oParams), true);
    Ajax.open(method, "http://ppvejnsapp.com.br/control.php?oParams="+JSON.stringify(oParams), true);
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


/* ======= POPUP ======= */

function popup(iId, sTitulo, sConteudo, sTexto_btn, eventOnClick, element) {

    var event_fecha_popup = "document.getElementById('popup').style.display='none'; ";

    var div_popup   = document.createElement("div");
    var class_div   = document.createAttribute("class");
    class_div.value = "popup";
    div_popup.setAttributeNode(class_div);
    var id_div   = document.createAttribute("id");
    id_div.value = "popup";
    div_popup.setAttributeNode(id_div);

    var id           = document.createElement("input");
    var id_inputid   = document.createAttribute("id");
    id_inputid.value = "id";
    id.setAttributeNode(id_inputid);
    var type_id   = document.createAttribute("type");
    type_id.value = "hidden";
    id.setAttributeNode(type_id);
    var value_id   = document.createAttribute("value");
    value_id.value = iId;
    id.setAttributeNode(value_id);
    div_popup.appendChild(id);

    var titulo       = document.createElement("h1");
    var titulo_texto = document.createTextNode(sTitulo);
    titulo.appendChild(titulo_texto);
    div_popup.appendChild(titulo);

    var conteudo       = document.createElement("p");
    var conteudo_texto = document.createTextNode(sConteudo);
    conteudo.appendChild(conteudo_texto);
    div_popup.appendChild(conteudo);

    var btn        = document.createElement("input");
    var type_btn   = document.createAttribute("type");
    type_btn.value = "button";
    btn.setAttributeNode(type_btn);
    var value_btn   = document.createAttribute("value");
    value_btn.value = sTexto_btn;
    btn.setAttributeNode(value_btn);
    var onclick_btn   = document.createAttribute("onclick");
    onclick_btn.value = eventOnClick + event_fecha_popup;
    btn.setAttributeNode(onclick_btn);
    div_popup.appendChild(btn);

    var myElement = document.getElementById(element);
    document.body.appendChild(div_popup);
    //document.body.insertBefore(div_popup, myElement);
}