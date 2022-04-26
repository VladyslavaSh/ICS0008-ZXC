document.addEventListener('DOMContentLoaded', function() {

function tab_switch(e) {
    if(formResult) {
        formResult.innerHTML = "";
    }
    let prsBtn = e.target; // pressed button
    let btns = e.target.parentNode; // all buttons in .regLogBtn div

    for(let btn of btns.children) {
        btn.classList.remove("active");
    }
    prsBtn.classList.add("active");

    for(let form of forms.children) {
        if(prsBtn.getAttribute("for") === form.getAttribute("id")) {
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
