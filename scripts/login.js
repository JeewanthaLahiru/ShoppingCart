function navBarFunction(){
    var nav = document.getElementById("navBarId");
    if(nav.className === "navBar"){
        nav.className += " responsive";
    }else{
        nav.className = "navBar";
    }
}