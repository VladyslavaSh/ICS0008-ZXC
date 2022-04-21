function setLeftValue(event) {
  let _this = event.currentTarget;
  let inputRight = _this.parentElement.getElementsByClassName("inputRight")[0],
  rangeElem = _this.parentElement.getElementsByClassName("range")[0],
  thumbLeft = _this.parentElement.getElementsByClassName("thumbLeft")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  _this.value = Math.min(value, parseInt(inputRight.value));
  let percent = ((_this.value - min) / (max - min)) * 100;

  thumbLeft.style.left = percent + "%";
  rangeElem.style.left = percent + "%";
}

function setRightValue(event) {
  let _this = event.currentTarget;
  let inputLeft = _this.parentElement.getElementsByClassName("inputLeft")[0],
  rangeElem = _this.parentElement.getElementsByClassName("range")[0],
  thumbRight = _this.parentElement.getElementsByClassName("thumbRight")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  _this.value = Math.max(value, parseInt(inputLeft.value));
  let percent = ((_this.value - min) / (max - min)) * 100;

  thumbRight.style.right = (100 - percent) + "%";
  rangeElem.style.right = (100 - percent) + "%";
}

let multiRangeSliders = document.getElementsByClassName("multiRangeSlider");

for (slider of multiRangeSliders) {
  console.log(slider);
  let x = slider.getElementsByClassName("inputLeft");
  console.log(x);
  let inputLeft = slider.getElementsByClassName("inputLeft")[0];
  let inputRight = slider.getElementsByClassName("inputRight")[0];
  inputLeft.addEventListener("input",setLeftValue);
  inputRight.addEventListener("input",setRightValue);

  inputLeft.addEventListener("mouseover", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbLeft")[0].classList.add("thumbHover");
  });
  inputLeft.addEventListener("mouseout", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbLeft")[0].classList.remove("thumbHover");
  });
  inputLeft.addEventListener("mousedown", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbLeft")[0].classList.add("thumbActive");
  });
  inputLeft.addEventListener("mouseup", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbLeft")[0].classList.remove("thumbActive");
  });

  inputRight.addEventListener("mouseover", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbRight")[0].classList.add("thumbHover");
  });
  inputRight.addEventListener("mouseout", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbRight")[0].classList.remove("thumbHover");
  });
  inputRight.addEventListener("mousedown", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbRight")[0].classList.add("thumbActive");
  });
  inputRight.addEventListener("mouseup", function(event) {
    event.currentTarget.parentElement.getElementsByClassName("thumbRight")[0].classList.remove("thumbActive");
  });
}
