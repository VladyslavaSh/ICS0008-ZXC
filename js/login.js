document.addEventListener('DOMContentLoaded', function() {

function tab_switch(e) {
    formResult.innerHTML = "";
    let prsBtn = e.path[0]; // pressed button
    let btns = e.path[1]; // all buttons in .regLogBtn div

    for(let btn of btns.children) {
        btn.classList.remove("active");
    }
    prsBtn.classList.add("active");

    for(let form of forms.children) {
        if(prsBtn.innerHTML === form.getAttribute("id")) {
            form.style.display = "block";
        }
        else {
            form.style.display = "none";
        }
    }
}

let formResult = document.getElementById("formResult");
let forms = document.getElementById("forms");
document.getElementsByClassName("regLogBtn")[0].addEventListener("click", tab_switch);
});