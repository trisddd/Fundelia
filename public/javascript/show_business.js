const iframe = document.getElementById("iframe-window");

function switchFrame(url) {
    iframe.setAttribute("src", url+"?iframe='true'");
}