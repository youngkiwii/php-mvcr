"use strict";

let submit = document.getElementById("submit");
let password = document.getElementById("password");
let confirmpass = document.getElementById("confirmPass");

password.addEventListener("keyup", checkSame);
confirmpass.addEventListener("keyup", checkSame);

function checkSame(e){
    if(password.value === confirmpass.value){
        confirmpass.classList.remove("highlight");
        submit.disabled = false;
    }else{
        confirmpass.classList.add("highlight");
        submit.disabled = true;
    }
}