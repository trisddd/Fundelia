/**
 * Displays a popup with transaction details.
 * @param {HTMLElement} element - The transaction line element that was clicked.
 */

let selectedTransactionId = null;

function showPopup(element) {
    selectedTransactionId = element.dataset.id;
    document.getElementById('popupName').innerText = element.dataset.name;
    document.getElementById('popupTime').innerText = element.dataset.time;

    const labelContainer = document.getElementById("popupLabelsSection");
    labelContainer.querySelectorAll("button.label-btn-popup").forEach(btn => btn.remove());

    let labels = [];
    try {
        labels = JSON.parse(element.dataset.labels);
    } catch(e) {
        console.error("Erreur", e);
    }

    labels.forEach(label => {
        const btn = document.createElement("button");
        btn.textContent = label.name;
        btn.classList.add("label-btn-popup");
        btn.onclick = () => removeLabelToTransaction(label.id);
        labelContainer.appendChild(btn);
    });

    let amount = parseFloat(element.dataset.amount.replace(',', '.'));
    const isExpense = element.dataset.amount.startsWith('-');
    if (isExpense) {
        amount = -Math.abs(amount); // Ensure it's negative for expenses
    } else {
        amount = Math.abs(amount); // Ensure it's positive for incomes
    }
    document.getElementById('popupAmount').innerText = amount.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('popupWording').innerText = element.dataset.wording;
    document.getElementById('transactionPopup').style.display = 'flex';

    
}

function closePopup() {
    document.getElementById('transactionPopup').style.display = 'none';
}

function openLabelPopup() {
    closePopup(); // Ferme le popup principal
    document.getElementById('labelSelectorPopup').style.display = 'flex';
}

function closeLabelSelectorPopup() {
    document.getElementById('labelSelectorPopup').style.display = 'none';
}

function addLabelToTransaction(labelId) {
    if (!selectedTransactionId) {
        alert("Aucune transaction sélectionnée !");
        return;
    }

    window.location.href = `/add_labels_to_transactions?tx_id=${selectedTransactionId}&label_id=${labelId}`;
}

function removeLabelToTransaction(labelId) {
    if (!selectedTransactionId) {
        alert("Aucune transaction sélectionnée !");
        return;
    }

    window.location.href = `/remove_labels_to_transactions?tx_id=${selectedTransactionId}&label_id=${labelId}`;
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort') || 'date-desc';

    const sortButtons = document.querySelectorAll('.sort-button');
    sortButtons.forEach(button => {
        if (button.dataset.sort === currentSort) {
            button.classList.add('active');
        }
    });

    // Tab functionality
    const expenses_button = document.getElementById('expenses-button');
    const incomes_button = document.getElementById('incomes-button');
    const expenses_tab = document.getElementById('expenses-tab');
    const incomes_tab = document.getElementById('incomes-tab');

    /**
     * Shows the selected tab content and updates active button state.
     * @param {string} tabName - The ID of the tab to show ('expenses' or 'incomes').
     */
    function showTab(tabName) {
        if (tabName === 'expenses') {
            expenses_tab.classList.remove('hidden');
            incomes_tab.classList.add('hidden');
            expenses_button.classList.add('active');
            incomes_button.classList.remove('active');
        } else if (tabName === 'incomes') {
            incomes_tab.classList.remove('hidden');
            expenses_tab.classList.add('hidden');
            incomes_button.classList.add('active');
            expenses_button.classList.remove('active');
        }
    }

    // Default to expenses tab when the page loads
    showTab('expenses');

    // Add event listeners to tab buttons
    expenses_button.addEventListener('click', function () {
        showTab('expenses');
    });

    incomes_button.addEventListener('click', function () {
        showTab('incomes');
    });
});