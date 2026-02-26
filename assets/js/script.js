document.addEventListener("DOMContentLoaded", function() {
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");

    if (toggleButton) {
        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    }

    // Simple fade-in animation on load
    window.addEventListener('load', () => {
        document.body.classList.add('loaded');
    });
});
