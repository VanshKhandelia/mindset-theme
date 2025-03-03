document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("scroll-top");

    if (button) {
        button.style.color = "#3498db"; 
    } else {
        console.error("Scroll To Top button not found!");
    }
});