const sirenInput = document.getElementById("siren");
const switchToNewButton = document.getElementById("switch-to-new");
const switchToRealButton = document.getElementById("switch-to-real");
const realBusinessForm = document.getElementById("real-business");
const newBusinessForm = document.getElementById("new-business");

sirenInput.addEventListener("keydown", (event)=>{
    event.preventDefault();
    if(/[0-9]/.test(event.key) && sirenInput.value.length < 11) { // 11 because of the two space chars
        let content = sirenInput.value+event.key;
        sirenInput.value = content.replaceAll(" ", "").match(/.{1,3}/g).join(" ");
    } else if (event.key === "Backspace") {
        let content = sirenInput.value.substr(0, sirenInput.value.length-1);
        sirenInput.value = content.trim();
    }
    // TODO : Ajouter l'event tab + coller
})

switchToNewButton.addEventListener("click", (event) => {
    realBusinessForm.classList.toggle("disabled");
    newBusinessForm.classList.toggle("disabled");
})

switchToRealButton.addEventListener("click", (event) => {
    realBusinessForm.classList.toggle("disabled");
    newBusinessForm.classList.toggle("disabled");
})