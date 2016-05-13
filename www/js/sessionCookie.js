/* SESSION COM COOKIES */

function writeCookie(name,value,days) {
    var date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires=" + date.toGMTString();
            }else{
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var i, c, ca, nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
}

/* SESSION COM LOCALSTORAGE */

function writeSession(name, value) {
    window.localStorage.setItem(name, value);
}

function readSession(name) {
    var item = window.localStorage.getItem(name);
    if (item !== 0) {
        return item;
    }
    return '';
}

function removeSession(name) {
    var item = window.localStorage.getItem(name);
    if (item !== 0) {
        window.localStorage.removeItem(name);
        return true;
    }
    return false;
}

function clearSession() {
    window.localStorage.clear();
    return false;
}

//sair e encessar a sessão
function js_sair() {

    clearSession();
    window.location.href = "index.html";
}