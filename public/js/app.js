/**
 * Main JavaScript for Sistem Kost - Phase 10
 * Enhanced UI/UX Features
 */

// ============================================
// FORM VALIDATION
// ============================================

const FormValidator = {
    /**
     * Validate a form
     * @param {string} formId - Form element ID
     * @param {object} rules - Validation rules
     */
    validate(formId, rules) {
        const form = document.getElementById(formId);
        if (!form) return false;
        
        let isValid = true;
        
        for (const [fieldName, fieldRules] of Object.entries(rules)) {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (!field) continue;
            
            const value = field.value.trim();
            let error = '';
            
            // Required validation
            if (fieldRules.required && !value) {
                error = fieldRules.messages?.required || 'Field ini wajib diisi';
            }
            
            // Min length validation
            if (!error && fieldRules.minLength && value.length < fieldRules.minLength) {
                error = fieldRules.messages?.minLength || `Minimal ${fieldRules.minLength} karakter`;
            }
            
            // Max length validation
            if (!error && fieldRules.maxLength && value.length > fieldRules.maxLength) {
                error = fieldRules.messages?.maxLength || `Maksimal ${fieldRules.maxLength} karakter`;
            }
            
            // Email validation
            if (!error && fieldRules.email && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    error = fieldRules.messages?.email || 'Format email tidak valid';
                }
            }
            
            // Number validation
            if (!error && fieldRules.number && value) {
                if (isNaN(value)) {
                    error = fieldRules.messages?.number || 'Harus berupa angka';
                }
            }
            
            // Min value validation
            if (!error && fieldRules.min && parseFloat(value) < fieldRules.min) {
                error = fieldRules.messages?.min || `Nilai minimal ${fieldRules.min}`;
            }
            
            // Max value validation
            if (!error && fieldRules.max && parseFloat(value) > fieldRules.max) {
                error = fieldRules.messages?.max || `Nilai maksimal ${fieldRules.max}`;
            }
            
            // Pattern validation
            if (!error && fieldRules.pattern && value) {
                const regex = new RegExp(fieldRules.pattern);
                if (!regex.test(value)) {
                    error = fieldRules.messages?.pattern || 'Format tidak valid';
                }
            }
            
            // Custom validation
            if (!error && fieldRules.custom && typeof fieldRules.custom === 'function') {
                const customError = fieldRules.custom(value, form);
                if (customError) {
                    error = customError;
                }
            }
            
            // Display error or clear it
            this.setFieldError(field, error);
            
            if (error) {
                isValid = false;
            }
        }
        
        return isValid;
    },
    
    /**
     * Set error message for a field
     */
    setFieldError(field, error) {
        // Remove existing error
        const existingError = field.parentElement.querySelector('.form-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Remove error styling
        field.classList.remove('border-red-500');
        
        if (error) {
            // Add error styling
            field.classList.add('border-red-500');
            
            // Add error message
            const errorElement = document.createElement('div');
            errorElement.className = 'form-error';
            errorElement.textContent = error;
            field.parentElement.appendChild(errorElement);
        }
    },
    
    /**
     * Clear all errors in a form
     */
    clearErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const errors = form.querySelectorAll('.form-error');
        errors.forEach(error => error.remove());
        
        const fields = form.querySelectorAll('.border-red-500');
        fields.forEach(field => field.classList.remove('border-red-500'));
    }
};

// ============================================
// AJAX FORM SUBMISSION
// ============================================

const AjaxForm = {
    /**
     * Submit form via AJAX
     */
    submit(formId, options = {}) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const submitBtn = form.querySelector('[type="submit"]');
        const originalBtnText = submitBtn?.innerHTML;
        
        // Disable submit button and show loading
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            submitBtn.innerHTML = '<span class="spinner spinner-sm"></span> ' + (options.loadingText || 'Memproses...');
        }
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method || 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (options.onSuccess) {
                    options.onSuccess(data);
                } else {
                    Toast.success(data.message || 'Berhasil!');
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                }
            } else {
                if (options.onError) {
                    options.onError(data);
                } else {
                    Toast.error(data.message || 'Terjadi kesalahan!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Toast.error('Terjadi kesalahan koneksi!');
            if (options.onError) {
                options.onError(error);
            }
        })
        .finally(() => {
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
};

// ============================================
// IMAGE LAZY LOADING
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Lazy load images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        lazyImages.forEach(img => {
            img.src = img.dataset.src || img.src;
            img.classList.add('loaded');
        });
    }
});

// ============================================
// CONFIRM DELETE
// ============================================

function confirmDelete(url, itemName = 'item ini') {
    if (confirm(`Apakah Anda yakin ingin menghapus ${itemName}?`)) {
        window.location.href = url;
    }
}

// ============================================
// COPY TO CLIPBOARD
// ============================================

function copyToClipboard(text, successMessage = 'Berhasil disalin!') {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            Toast.success(successMessage);
        }).catch(err => {
            console.error('Failed to copy:', err);
            Toast.error('Gagal menyalin!');
        });
    } else {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            Toast.success(successMessage);
        } catch (err) {
            console.error('Failed to copy:', err);
            Toast.error('Gagal menyalin!');
        }
        document.body.removeChild(textarea);
    }
}

// ============================================
// PRICE FORMATTER
// ============================================

function formatPrice(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    input.value = 'Rp ' + Number(value).toLocaleString('id-ID');
}

function unformatPrice(value) {
    return value.replace(/[^0-9]/g, '');
}

// ============================================
// SCROLL TO TOP
// ============================================

// Show/hide scroll to top button
window.addEventListener('scroll', function() {
    const scrollBtn = document.getElementById('scroll-to-top');
    if (scrollBtn) {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    }
});

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// ============================================
// DROPDOWN TOGGLE
// ============================================

function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
    
    // Close other dropdowns
    document.querySelectorAll('.dropdown.active').forEach(d => {
        if (d.id !== dropdownId) {
            d.classList.remove('active');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.active').forEach(d => {
            d.classList.remove('active');
        });
    }
});

// ============================================
// FILE UPLOAD PREVIEW
// ============================================

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="max-w-full h-auto rounded-lg">`;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// ============================================
// LOADING OVERLAY
// ============================================

const LoadingOverlay = {
    show(message = 'Memuat...') {
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10000]';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex flex-col items-center">
                <div class="spinner spinner-lg mb-4"></div>
                <p class="text-gray-700 font-medium">${message}</p>
            </div>
        `;
        document.body.appendChild(overlay);
    },
    
    hide() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
};

// ============================================
// INITIALIZE
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistem Kost - UI Enhanced v1.0');
    
    // Add fade-in animation to elements
    const fadeElements = document.querySelectorAll('.fade-in-on-load');
    fadeElements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('animate-fade-in');
        }, index * 100);
    });
});

// Make utilities available globally
window.FormValidator = FormValidator;
window.AjaxForm = AjaxForm;
window.LoadingOverlay = LoadingOverlay;
window.confirmDelete = confirmDelete;
window.copyToClipboard = copyToClipboard;
window.formatPrice = formatPrice;
window.unformatPrice = unformatPrice;
window.scrollToTop = scrollToTop;
window.toggleDropdown = toggleDropdown;
window.previewImage = previewImage;
