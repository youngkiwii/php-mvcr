"use strict";

let hamburger = document.getElementById("hamburger");
let menu = document.getElementById("menu").firstElementChild;

hamburger.addEventListener("click", () =>{
    hamburger.classList.toggle("active");
    menu.classList.toggle("active");
});