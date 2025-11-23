<!-- Toast Notification Component -->
<?php
/**
 * Toast Notification Component
 * 
 * Usage in PHP:
 * Session::setFlash('success', 'Data berhasil disimpan!');
 * Session::setFlash('error', 'Terjadi kesalahan!');
 * Session::setFlash('warning', 'Perhatian!');
 * Session::setFlash('info', 'Informasi penting');
 */
?>

<div id="toast-container" class="toast-container"></div>

<script>
// Toast notification system
const Toast = {
    show(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icons[type] || icons.info}"></i>
            </div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    },
    
    success(message, duration = 3000) {
        this.show(message, 'success', duration);
    },
    
    error(message, duration = 4000) {
        this.show(message, 'error', duration);
    },
    
    warning(message, duration = 3500) {
        this.show(message, 'warning', duration);
    },
    
    info(message, duration = 3000) {
        this.show(message, 'info', duration);
    }
};

// Check for flash messages from PHP session
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['flash_success'])): ?>
        Toast.success('<?= addslashes($_SESSION['flash_success']) ?>');
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash_error'])): ?>
        Toast.error('<?= addslashes($_SESSION['flash_error']) ?>');
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash_warning'])): ?>
        Toast.warning('<?= addslashes($_SESSION['flash_warning']) ?>');
        <?php unset($_SESSION['flash_warning']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash_info'])): ?>
        Toast.info('<?= addslashes($_SESSION['flash_info']) ?>');
        <?php unset($_SESSION['flash_info']); ?>
    <?php endif; ?>
});

// Make Toast available globally
window.Toast = Toast;
</script>
