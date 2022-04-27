function setLeftValue(event) {
  let _this = event.currentTarget;
  let inputRight = _this.parentElement.getElementsByClassName("inputRight")[0],
  rangeElem = _this.parentElement.getElementsByClassName("range")[0],
  thumbLeft = _this.parentElement.getElementsByClassName("thumbLeft")[0],
  priceMin = _this.parentElement.getElementsByClassName("priceMin")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  _this.value = Math.min(value, parseInt(inputRight.value));
  priceMin.value = Math.min(value,parseInt(inputRight.value));
  let percent = ((_this.value - min) / (max - min)) * 100;

  thumbLeft.style.left = percent + "%";
  rangeElem.style.left = percent + "%";
}

function setRightValue(event) {
  let _this = event.currentTarget;
  let inputLeft = _this.parentElement.getElementsByClassName("inputLeft")[0],
  rangeElem = _this.parentElement.getElementsByClassName("range")[0],
  thumbRight = _this.parentElement.getElementsByClassName("thumbRight")[0],
  priceMax = _this.parentElement.getElementsByClassName("priceMax")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  _this.value = Math.max(value, parseInt(inputLeft.value));
  priceMax.value = Math.max(value, parseInt(inputLeft.value));
  let percent = ((_this.value - min) / (max - min)) * 100;

  thumbRight.style.right = (100 - percent) + "%";
  rangeElem.style.right = (100 - percent) + "%";
}

function setLeftPrice(event) {
  let _this = event.currentTarget,
  _parent = _this.parentElement.parentElement.parentElement.parentElement;
  let inputLeft = _parent.getElementsByClassName("inputLeft")[0],
  priceMax = _parent.getElementsByClassName("priceMax")[0],
  rangeElem = _parent.getElementsByClassName("range")[0],
  thumbLeft = _parent.getElementsByClassName("thumbLeft")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  if (!isNaN(value)) {
    _this.value = Math.max(Math.min(value, parseInt(priceMax.value)),min);
    inputLeft.value = Math.max(Math.min(value, parseInt(priceMax.value)),min);
    let percent = ((_this.value - min) / (max - min)) * 100;

    thumbLeft.style.left = percent + "%";
    rangeElem.style.left = percent + "%";
  }
}

function setRightPrice(event) {
  let _this = event.currentTarget,
  _parent = _this.parentElement.parentElement.parentElement.parentElement;
  let inputRight = _parent.getElementsByClassName("inputRight")[0],
  priceMin = _parent.getElementsByClassName("priceMin")[0],
  rangeElem = _parent.getElementsByClassName("range")[0],
  thumbRight = _parent.getElementsByClassName("thumbRight")[0];

  let min = parseInt(_this.min), max = parseInt(_this.max), value = parseInt(_this.value);
  if (!isNaN(value)) {
    _this.value = Math.min(Math.max(value, parseInt(priceMin.value)),max);
    inputRight.value = Math.min(Math.max(value, parseInt(priceMin.value)),max);
    let percent = ((_this.value - min) / (max - min)) * 100;

    thumbRight.style.right = (100 - percent) + "%";
    rangeElem.style.right = (100 - percent) + "%";
  }
}

function updateSlider(slider) {
  let thumbLeft = slider.getElementsByClassName("thumbLeft")[0],
  thumbRight = slider.getElementsByClassName("thumbRight")[0],
  inputLeft = slider.getElementsByClassName("inputLeft")[0],
  inputRight = slider.getElementsByClassName("inputRight")[0],
  rangeElem = slider.getElementsByClassName("range")[0];
  let min = parseInt(inputLeft.min), max = parseInt(inputRight.max), value1 = parseInt(inputLeft.value), value2 = parseInt(inputRight.value);
  let percent1 = ((value1 - min) / (max - min)) * 100,
  percent2 = ((value2 - min) / (max - min)) * 100;

  thumbLeft.style.left = percent1 + "%";
  rangeElem.style.left = percent1 + "%";
  thumbRight.style.right = (100 - percent2) + "%";
  rangeElem.style.right = (100 - percent2) + "%";
}

let multiRangeSliders = document.getElementsByClassName("multiRangeSlider");

for (slider of multiRangeSliders) {
  let x = slider.getElementsByClassName("inputLeft");
  let inputLeft = slider.getElementsByClassName("inputLeft")[0];
  let inputRight = slider.getElementsByClassName("inputRight")[0];
  let priceMin = slider.getElementsByClassName("priceMin")[0];
  let priceMax = slider.getElementsByClassName("priceMax")[0];
  inputLeft.addEventListener("input",setLeftValue);
  inputRight.addEventListener("input",setRightValue);
  priceMin.addEventListener("input",setLeftPrice);
  priceMax.addEventListener("input",setRightPrice);

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
  updateSlider(slider);
}
