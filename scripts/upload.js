"use strict";

let imagePara = document.getElementById("imagePara");
let file = document.getElementById("file");
let form = document.getElementById("form");
let labelPourcent = document.createElement("label");
let spanStatus = document.createElement("span");

file.addEventListener("change", fileUpload);

function fileUpload(){
    imagePara.appendChild(labelPourcent);
    spanStatus.classList.add("error");

    let fileToUpload = file.files[0];

    if(fileToUpload.size <= 2000000){
        let formData = new FormData();
        formData.append("file", fileToUpload);
        
        let ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", functionProgress, false);
        ajax.addEventListener("load", functionComplete, false);
        ajax.addEventListener("abort", functionAbort, false);
        ajax.addEventListener("error", functionError, false);
        ajax.open("POST", "");
        ajax.send(formData);

        
    }else{
        imagePara.removeChild(labelPourcent);
        spanStatus.innerHTML = "Le fichier dépasse la limite autorisée.";
    }
    
    form.insertBefore(spanStatus, form.lastElementChild);
}

function functionProgress(e){
    let pourcent = (e.loaded / e.total) * 100;
    labelPourcent.innerHTML = Math.round(pourcent) + "%";
}

function functionComplete(){
    spanStatus.innerHTML = "Téléversement réussi.";
}

function functionAbort(){
    spanStatus.innerHTML = "Téléversement interrompu.";
}

function functionError(){
    spanStatus.innerHTML = "Téléversement échoué.";
}