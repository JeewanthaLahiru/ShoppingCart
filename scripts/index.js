function responsiveNavBar(){
    var x = document.getElementById("navBarId");
    if(x.className === "navBar"){
        x.className += " responsive";
    }else{
        x.className = "navBar";
    }
}

