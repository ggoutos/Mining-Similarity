function changePage(_link) {
	
		history.pushState(null, null, _link);
        $("#page").load(_link);
        return false;
	
}

function getVar(variable) {
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}


function getFile() {

var l = String(window.location);
var i = l.length;
while (l[i]!='/' && i>=0 )
{
	i--;
}
var file = l.substr(i+1);
return(file);

}


function progress() {
    var a = parseFloat(document.getElementById('progress1').getAttribute("data-sent")) / parseFloat(document.getElementById('progress1').getAttribute("data-all"));
    var b = parseFloat(document.getElementById('progress2').getAttribute("data-sent")) / parseFloat(document.getElementById('progress2').getAttribute("data-all"));
    var c = parseFloat(document.getElementById('progress3').getAttribute("data-sent")) / parseFloat(document.getElementById('progress3').getAttribute("data-all"));
    var d = parseFloat(document.getElementById('progress4').getAttribute("data-sent")) / parseFloat(document.getElementById('progress4').getAttribute("data-all"));
    
	//var n = (a+b+c+d)*25 ;
    
    var n = ( parseFloat(document.getElementById('progress1').getAttribute("data-sent")) + parseFloat(document.getElementById('progress2').getAttribute("data-sent")) + parseFloat(document.getElementById('progress3').getAttribute("data-sent")) + parseFloat(document.getElementById('progress4').getAttribute("data-sent")) ) / parseFloat(document.getElementById('progress').getAttribute("data-all"));
    
    n = n*100;
    
		var an = document.getElementById('progress-bar');
		if (n!=0) {
			an.style.width = parseFloat(n) + "%";
		}
		
		if (n==100) {
		setTimeout( function() {
			an.style.width = "0%";
		}, 1000 );
		}
		
}

function deleteProg() {
		document.getElementById('progress1').value = 100;
		document.getElementById('progress2').value = 100;
		document.getElementById('progress3').value = 100;
		document.getElementById('progress4').value = 100;
		
		var an = document.querySelector( '.' + "la-anim-1" );
		an.style="";
		classie.remove( an, 'la-animate' );	
}