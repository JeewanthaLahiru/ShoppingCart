function navBarFunction(){
    var navbar = document.getElementById("navBarId");
    if(navbar.className === "navBar"){
        navbar.className += " responsive";
    }else{
        navbar.className = "navBar";
    }
}