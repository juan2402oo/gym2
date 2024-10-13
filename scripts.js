function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var banner = document.querySelector(".banner");
    var subBanner = document.getElementById("subBanner");
    var content = document.getElementById("content");

    if (sidebar.style.left === "0px") {
        sidebar.style.left = "-200px";
        banner.style.marginLeft = "0";
        subBanner.style.marginLeft = "0";
        content.style.marginLeft = "0";
    } else {
        sidebar.style.left = "0px";
        banner.style.marginLeft = "200px";
        subBanner.style.marginLeft = "200px";
        content.style.marginLeft = "200px";
    }
}

function toggleLoginMenu() {
    var loginMenu = document.getElementById("loginMenu");
    if (loginMenu.style.display === "block") {
        loginMenu.style.display = "none";
    } else {
        loginMenu.style.display = "block";
    }
}

window.onclick = function(event) {
    if (!event.target.matches('.login-button')) {
        var loginMenus = document.getElementsByClassName("login-menu");
        for (var i = 0; i < loginMenus.length; i++) {
            var openLoginMenu = loginMenus[i];
            if (openLoginMenu.style.display === "block") {
                openLoginMenu.style.display = "none";
            }
        }
    }
}

let slideIndex = 0;
showSlides(slideIndex);

function moveSlide(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("carousel-slide");
    if (n >= slides.length) {slideIndex = 0}
    if (n < 0) {slideIndex = slides.length - 1}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex].style.display = "block";
}