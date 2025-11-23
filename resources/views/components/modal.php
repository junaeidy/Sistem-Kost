<!-- Modal Component -->
<?php
/**
 * Modal Component
 * 
 * Usage:
 * Create a modal trigger button:
 * <button onclick="Modal.open('myModal')">Open Modal</button>
 * 
 * Create modal HTML with id="myModal"
 */
?>

<script>
// Modal management system
const Modal = {
    open(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    },
    
    close(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    },
    
    closeAll() {
        const modals = document.querySelectorAll('.modal.active');
        modals.forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    },
    
    confirm(title, message, onConfirm) {
        const modalId = 'confirm-modal-' + Date.now();
        const modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'modal active';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">${title}</h3>
                </div>
                <div class="modal-body">
                    <p>${message}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="Modal.close('${modalId}'); this.closest('.modal').remove();">
                        Batal
                    </button>
                    <button class="btn btn-danger" onclick="Modal.close('${modalId}'); this.closest('.modal').remove(); (${onConfirm})();">
                        Konfirmasi
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.close(modalId);
                modal.remove();
            }
        });
    }
};

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        Modal.closeAll();
    }
});

// Close modal on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        Modal.closeAll();
    }
});

// Make Modal available globally
window.Modal = Modal;
</script>
