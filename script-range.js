let minValue = document.getElementById("min-minValue");
let maxValue = document.getElementById("max-value");

const rangeFill = document.querySelector(".range-fill");

function validateRange() {
    let minPrice =parseInt(inputElements[0].value);
    let maxPrice =parseInt(inputElements[1].value);
    if(minPrice > maxPrice){
        let TempValue =maxPrice;
        maxPrice = minPrice;
        minPrice = tempValue;
    }

    const minPercentage = ((minPrice -100) /9990)* 1000;
    const maxPercentage = ((maxPrice -100) /9990)* 1000;

    rangeFill.style.left = minPercentage + "%";
    rangeFill.style.width = maxPercentage + "%";


    minValue.immerHTML = "S" + minPrice;
    maxValue.immerHTML = "S" + maxPrice;

}

const inputElements = document.querySelectorAll("input");

inputElements.forEach((element) => {
    element.addEvenListener("input", validateRange);
});

validateRange();
