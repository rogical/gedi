<!--
// domaine exploitation des cookies
var domain = ".ln.cit.alcatel.fr";
//var domain = ".fr.alcatel-lucent.com";

  //  Function GetCookie
  //  lit le cookie dont le nom est passé dans le parametre name
  function GetCookie (name) {
        var search = name + "="
//alert(" ds GetCookie  du module.js ligne 9 : var search = "+search+"")
        if (document.cookie.length>0) { //S'il y a un cookie
//alert(" ds GetCookie ligne 11 : var document.cookie.length = "+document.cookie.length+"")
                offset = document.cookie.indexOf(search)
//alert(" ds GetCookie  du module.js ligne 13 : var offset = "+offset+"")
                if (offset != -1) { // si le cookie existe
                        offset += search.length
                        //fixe l'index du début de la valeur du cookie
                        end = document.cookie.indexOf(";", offset)
                        //fixe l'index de la fin de la valeur du cookie
                        if (end == -1)
                                end = document.cookie.length
                        var cook = document.cookie.substring(offset, end)
//alert(" ds GetCookie du module.js ligne 25 : var cook = "+cook+"")
                        if (cook == '-') cook=""
							return unescape(cook);
                }
        }
		
  }


  //  Function DateExpiration
  //  fixe la date d'expiration
  function DateExpiration (DateExp) {
    var aujourdhui = new Date(0);
	
    var aujourdhuidate = aujourdhui.getTime();
//alert(" ds DateExpiration du module.js ligne 31 : date aujourdhui = "+aujourdhuidate+"")
    if (aujourdhuidate > 0)
        DateExp.setTime (DateExp.getTime() - aujourdhuidate);

  }

  
  		//  Supprime un Cookie

		function SupprCookie (nom) {

				var date = new Date(1999,0,1);

				document.cookie = nom + ";expires=" + date.toGMTString(); 

				document.cookie = "Pass=" + ";expires=" + date.toGMTString(); 

		}	
  
  
  function prepa_cookie() {
    // Définition de la date d'expiration
    var expdate = new Date ();
	// nouveau pour test
	SupprCookie ();
	//fin
//alert(" ds prepa_cookie du module.js ligne 38 : appel DateExpiration avec expdate = "+expdate+"")
    DateExpiration (expdate);
	//alert(" ds prepa_cookie du module.js ligne 40 : sorti de DateExpiration ")
    expdate.setTime (expdate.getTime() + (24 * 60 * 60 * 1000 * 365 * 5)); // expire dans 5 ans 
	//expdate.setTime (expdate.getTime() + (24 * 60 * 60 * 1000 * 365 * 10)); // expire dans 10 ans 
	//alert(" ds prepa_cookie du module.js ligne 42 : expdate.setTime = "++"")
//alert(" ds prepa_cookie du module.js ligne 49 : appel FixeCookie avec parent.location.href = "+parent.location.href+"")
    FixeCookie("URL_BASE",parent.location.href,"","/",domain);
    //FixeCookie("URL_BASE",parent.location.href,"","/",".ln.cit.alcatel.fr");
    // pour faire un cookie temporaire à une session => pas de date d'expiration
    // FixeCookie(document.formulaire.name_cook.value,document.formulaire.val_cook.value) 
  }
		
  //  Function FixeCookie
  //  pour créer ou modifier un cookie
  function FixeCookie (nom,valeur,expire,path,domaine,securise) {
//alert(" Dans FixeCookie du module.js : nom = "+nom+",parent.location.href ="+valeur+",expire="+expire+",path="+path+",domaine="+domaine+"")
    document.cookie = nom + "=" + escape (valeur) + ( (expire) ? "; expires=" + expire.toGMTString() : "") + ((path) ? "; path=" + path : "") + ((domaine) ? "; domain=" + domaine : "") + ((securise) ? "; secure" : "");
  }


  // Function Init
  // Utiliser pour authentifier
  // Si cookie USERPASS undefinied alors apparition frame Authentification  
  function Init() {
  
//alert(" ds Init du module.js ligne 64 : Appel  GetCookie valeur PassOk = "+PassOk+"")
  
    var PassOk=GetCookie('USERPASS');
    // document.write('USERPASS dans cookie='+PassOk);
	//alert(" ds Init du module.js ligne 65 : valeur PassOk = "+PassOk+"")
    if (PassOk != "OK") {
        prepa_cookie();
        top.location.replace("./formulaire.html");
    }
    else
        top.location.replace("../index.html");
  }
  
//-->
