$(document).ready(function(){
    $("#resetDynamicColor").click(function (e) {
        e.preventDefault();
        if ($("#addEditForm").valid()) {
            $("#reset_default_value").val("Yes");
            $("#addEditForm").trigger("submit");
        } else {
            return false;
        }
    });
    
    

    $('input[name="background_type"]').change(function () {
        let selectedValue = $(this).val();
        if (selectedValue === "Color") {
            $('.background_pattern').hide();
            $('.background_color').show();
        }else{
            $('.background_pattern').show();
            $('.background_color').hide();
        }
    });
    $('input[name="background_type"]:checked').change();

});


// DOM Elements :
const gradientBox = document.querySelector('.gradient-color-box');
const selectMenu = document.querySelector('.select-box');
const patternSelect = document.querySelector('.select-pattern');
const patternSizeInput = document.querySelector('.pattern-size');
const patternOpacityInput = document.querySelector('.pattern-opacity');
const colorInputs = document.querySelectorAll(".colors input[type='color']");
const opacityInputs = document.querySelectorAll(".colors input[type='range']");
const refreshBtn = document.querySelector('.refresh');
const saveBtn = document.querySelector('.save');
const gradientInput = document.getElementById('background_color');
const gradientModal = new bootstrap.Modal(document.getElementById('gradientModal'));
const patternBgColorInput = document.querySelector('.pattern-bg-color');
const patternColorInput = document.querySelector('.pattern-color');

// Generate random colors
const getRandomColor = () => {
    const randomHex = Math.floor(Math.random() * 0xffffff).toString(16).padStart(6, '0');
    return `#${randomHex}`;
};

// Convert HEX to RGBA
const hexToRgba = (hex, opacity) => {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
};

// Convert RGB to HEX
const rgbToHex = (r, g, b) => {
    const toHex = (num) => Number(num).toString(16).padStart(2, '0');
    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
};

// Generate background (either gradient or pattern)
// Generate background (either gradient, pattern, or heart shape)
const generateBackground = (isRandom = false) => {
    if (isRandom) {
        colorInputs[0].value = getRandomColor();
        colorInputs[1].value = getRandomColor();
        opacityInputs[0].value = Math.random().toFixed(2);
        opacityInputs[1].value = Math.random().toFixed(2);
    }

    const pattern = patternSelect.value;
    const patternSize = patternSizeInput.value;
    const patternOpacity = patternOpacityInput.value;
    const color1 = hexToRgba(colorInputs[0].value, opacityInputs[0].value);
    const color2 = hexToRgba(colorInputs[1].value, opacityInputs[1].value);
    const patternBgColor = patternBgColorInput.value;

    if (pattern !== 'none') {
        switch (pattern) {
            case 'dots':
                gradientBox.style.background = `radial-gradient(circle, rgba(51, 51, 51, ${patternOpacity}) 1px, transparent 1px) 0 0`;
                gradientBox.style.backgroundColor = patternBgColor;
                gradientBox.style.backgroundSize = `${patternSize}px ${patternSize}px`;
                break;
            case 'overlapping-circles':
                gradientBox.style.background = `radial-gradient(circle, rgba(51, 51, 51, ${patternOpacity}) 20%, transparent 20%)`;
                gradientBox.style.backgroundColor = patternBgColor;
                gradientBox.style.backgroundSize = `${patternSize * 1.5}px ${patternSize * 1.5}px`;
                break;
            default:
                gradientBox.style.background = 'none';
                break;
        }
    } else {
        const gradient = `linear-gradient(${selectMenu.value}, ${color1} 0%, ${color2} 100%)`;
        gradientBox.style.background = gradient;
    }
};

// Event Listeners
gradientInput.addEventListener('click', () => {
    gradientModal.show();
});
refreshBtn.addEventListener('click', () => generateBackground(true));
colorInputs.forEach((input) => input.addEventListener('input', () => generateBackground(false)));
opacityInputs.forEach((input) => input.addEventListener('input', () => generateBackground(false)));
selectMenu.addEventListener('change', () => generateBackground(false));
patternSelect.addEventListener('change', () => generateBackground(false));
patternSizeInput.addEventListener('input', () => generateBackground(false));
patternOpacityInput.addEventListener('input', () => generateBackground(false));

// Save background (either pattern or gradient)
saveBtn.addEventListener('click', () => {
    const pattern = patternSelect.value;
    let backgroundValue = `background: ${gradientBox.style.background};`;
    gradientInput.value = backgroundValue;
    gradientModal.hide();
});
