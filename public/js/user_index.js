function toggleMenu() {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

window.addEventListener("click", function (e) {
    const button = document.querySelector(".account-button");
    const menu = document.getElementById("dropdownMenu");
    if (!button.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = "none";
    }
});
