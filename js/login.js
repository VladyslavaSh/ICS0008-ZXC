document.addEventListener('DOMContentLoaded', function() {

function check(){
    setTimeout(()=>{urlCheck(window.location.hash)},0);
}

function urlCheck(hash) {
    href = hash.slice(1);
    for(let form of forms.children) {
        id = form.getAttribute("id")
        if(id === href) {
            let btns = document.getElementsByClassName("button");
            for(let btn of btns) {
                btn.classList.remove("active");
                if(btn.firstChild.getAttribute("href") === hash) {
                    btn.classList.add("active");
                }
            }
            form.style.display = "block";
        }
        else {
            form.style.display = "none";
        }
    }
}

let forms = document.getElementById("forms");
if (window.location.hash) urlCheck(window.location.hash);
document.getElementsByClassName("regLogBtn")[0].addEventListener("click", check);


});