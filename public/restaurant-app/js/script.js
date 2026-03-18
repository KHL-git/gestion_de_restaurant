// Gestion des modals
const modals = document.querySelectorAll('.modal');
const modalTriggers = document.querySelectorAll('[data-modal]');
const closeButtons = document.querySelectorAll('.close');

modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = trigger.dataset.modal;
        const modal = document.getElementById(modalId);
        modal.style.display = 'block';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
});

closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.modal').style.display = 'none';
        btn.closest('.modal').classList.remove('active');
        document.body.style.overflow = 'auto';
    });
});

window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
        e.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// Formulaire dynamique
function handleFormSubmit(form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        // Simulation d'appel API
        try {
            showNotification('Opération réussie !', 'success');
            form.reset();
            closeModal(form.closest('.modal'));

            // Rafraîchir le tableau
            if (form.closest('.modal').dataset.refresh) {
                loadTable(form.dataset.table);
            }
        } catch (error) {
            showNotification('Erreur lors de l\'opération', 'error');
        }
    });
}

// Notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        ${message}
        <button class="close-notification">&times;</button>
    `;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 3000;
        background: ${type === 'success' ? '#56ab2f' : type === 'error' ? '#ff416c' : '#667eea'};
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 4000);

    notification.querySelector('.close-notification').addEventListener('click', () => {
        notification.remove();
    });
}

// Recherche en temps réel
function setupSearch(tableId) {
    const searchInput = document.getElementById(`${tableId}-search`);
    const table = document.getElementById(tableId);

    if (searchInput && table) {
        searchInput.addEventListener('input', (e) => {
            const rows = table.querySelectorAll('tbody tr');
            const term = e.target.value.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    // Initialiser tous les formulaires
    document.querySelectorAll('form[data-action]').forEach(form => {
        handleFormSubmit(form);
    });

    // Initialiser les recherches
    document.querySelectorAll('[data-table]').forEach(table => {
        setupSearch(table.dataset.table);
    });
});
