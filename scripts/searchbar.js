"use strict";

let searchbar = document.getElementById("search");

searchbar.addEventListener("keyup", research)

function research(){
    let labels = document.getElementsByClassName("pokemons");
    let searchtext = searchbar.value.toLowerCase();
    
    for(let i=0; i < labels.length; i++){
        if(!labels[i].innerHTML.toLowerCase().includes(searchtext))
            labels[i].parentElement.classList.add("hidden");
        else
            labels[i].parentElement.classList.remove("hidden");
    }
}
