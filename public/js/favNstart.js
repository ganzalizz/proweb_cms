var bookmarkurl="http://www.ladoni.proweb.by"
var bookmarktitle="Минск на ладонях"

function getBrowserInfo() {
 var t,v = undefined;
 if (window.opera) t = 'Opera';
 else if (document.all) {
  t = 'IE';
  var nv = navigator.appVersion;
  var s = nv.indexOf('MSIE')+5;
  v = nv.substring(s,s+1);
 }
 else if (navigator.appName) t = 'Netscape';
 return {type:t,version:v};
}

function bookmark(a){
 var url = window.document.location;
 var title = window.document.title;
 var b = getBrowserInfo();
 if (b.type == 'IE' && 7 > b.version && b.version >= 4) window.external.AddFavorite(url,title);
 else if (b.type == 'Opera') {
  a.href = url;
  a.rel = "sidebar";
  a.title = url+','+title;
  return true;
 }
 else if (b.type == "Netscape") window.sidebar.addPanel(title,url,"");
 else alert("Нажмите CTRL-D, чтобы добавить страницу в закладки.");
 return false;
}


function setHome() {
     var b = getBrowserInfo();     
     if (b.type == 'IE' && 7 > b.version && b.version >= 4) {
         this.style.behavior='url(#default#homepage)'; this.setHomePage('bookmarkurl');
     }
     else
         alert('Чтобы сделать данную страничку стартовой войдите в Инструменты->настройки->основные, в и в поле домашней страницы напишите '+bookmarkurl);
    return false;
} 


