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
		var a = parseFloat(document.getElementById('progress1').value);
		var b = parseFloat(document.getElementById('progress2').value);
		var c = parseFloat(document.getElementById('progress3').value);
		var d = parseFloat(document.getElementById('progress4').value);
		var n = (a+b+c+d)/4 ;
    
		var an = document.querySelector( '.' + "la-anim-1" );
    alert(n);
		if (n!=0) {
			an.style="-webkit-transition: -webkit-transform 5s ease-in, opacity 0.5s 5s; transition: transform 0.5s ease-in, opacity 1s 5s;  -webkit-transform: translate3d("+parseFloat(n-100)+"%, 0, 0); 	transform: translate3d("+parseFloat(n-100)+"%, 0, 0);";
			$("#bar").addClass("la-animate");
		}
		
		if (n==100) {
		setTimeout( function() {
			an.style="";
			$("#bar").removeClass("la-animate");	
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