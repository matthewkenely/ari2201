function fadeIn() {
    var main = document.getElementsByTagName("main")[0];
    main.animate({
        opacity: 100
    }, 400, function () {
        $(document).css("display", "none");
    });

    setTimeout(function () {
        main.style.display = "block";
    }, 300);
}

function fadeOut() {
    var main = document.getElementsByTagName("main")[0];
    main.animate({
        opacity: 0
    }, 400, function () {
        $(document).css("display", "none");
    });

    setTimeout(function () {
        main.style.display = "none";
    }, 300);
}

var links = document.getElementsByClassName("navanimate");

for (let link of links) {
    link.addEventListener("mouseenter", function () {
        link.classList.add("hovered");
    });

}
