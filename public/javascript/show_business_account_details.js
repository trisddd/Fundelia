const openModalBtn = document.getElementById('link');
const transferModal = document.getElementById('transferModal');
const closeTransferModalBtn = document.getElementById('closeTransferModal');

function updateModal() {
    transferModal.classList.toggle('disabled');
    document.body.classList.toggle('no-scroll'); 
}

openModalBtn.addEventListener('click', updateModal);
closeTransferModalBtn.addEventListener('click', updateModal);

transferModal.addEventListener('click', (e) => {
    if (e.target === transferModal) {
        updateModal();
    }
});