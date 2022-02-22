document.addEventListener('DOMContentLoaded', function() {
    let slide = document.getElementsByClassName("slide")
    let slide_num = 0;

    for(let i = 0; i < slide.length; i++){
        slide[i].style.display = "none";
    }

    slide[slide_num].style.display = "block";

    function next(){
        slide[slide_num].style.display = "none";
        if (slide_num === slide.length-1) {
            slide_num = -1;
        }
        slide_num++;
        slide[slide_num].style.display = "block";
        slide[slide_num].style.opacity = 0.5;

        let y = 0.5;
        let tempY = setInterval(function(){
            y+=0.05;
            slide[slide_num].style.opacity = y;
            if(y >= 1){
                clearInterval(tempY);
                y = 0.5;
            }
        }, 25)
    }

    function previous(){
        slide[slide_num].style.display = "none";
        if (slide_num === 0) {
            slide_num = slide.length;
        }
        slide_num--;
        slide[slide_num].style.display = "block";
        slide[slide_num].style.opacity = 0.5;

        let y = 0.5;
        let tempY = setInterval(function(){
            y+=0.05;
            slide[slide_num].style.opacity = y;
            if(y >= 1){
                clearInterval(tempY);
                y = 0.5;
            }
        }, 25)
    }

    document.getElementsByClassName("pointer_right")[0].addEventListener('click', next);
    document.getElementsByClassName("pointer_left")[0].addEventListener('click', previous);

});