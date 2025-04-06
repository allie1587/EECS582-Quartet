/*
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 03/30/2025
Revisions:
    03/30/2025 -- Alexandra Stratton -- created validate.js
Purpose: Validation of field information
*/

const ValidationConfig = {
    requiredFields: [],
    optionalFields: []
};

// Helpper Functions

function isFieldRequired(fieldId) {
    return ValidationConfig.requiredFields.includes(fieldId);
}

function initFieldRequirements(requiredFields, optionalFields) {
    ValidationConfig.requiredFields = requiredFields;
    ValidationConfig.optionalFields = optionalFields;
}

function displayFileName(fileInput, displayElement) {
    if (fileInput.files.length > 0) {
        displayElement.textContent = fileInput.files[0].name;
    } else {
        displayElement.textContent = '';
    }
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function previewGalleryImage(input) {
    const galleryItem = input.closest('.gallery-item');
    if (!galleryItem) return;
    
    const preview = galleryItem.querySelector('.gallery-image-preview');
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function showError(input, errorElement, message) {
    errorElement.textContent = message;
    errorElement.style.display = 'inline';
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
}

function showSuccess(input, errorElement) {
    errorElement.style.display = 'none';
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

function scrollToFirstError() {
    const firstError = document.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
}

// Functions for validating field information in the forms

function validateFirstName(input, errorElement) {
    const value = input.value.trim();
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && !value) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    const regex = /^[A-Za-z\s'-]+$/;
    
    if (!value) {
        showError(input, errorElement, "First name cannot be empty");
        return false;
    }
    
    if (!regex.test(value)) {
        showError(input, errorElement, "Can only contain letters, spaces, hyphens, or apostrophes");
        return false;
    }
    
    if (value.length < 2) {
        showError(input, errorElement, "Must be at least 2 characters");
        return false;
    }
    
    if (value.length > 50) {
        showError(input, errorElement, "Must be less than 50 characters");
        return false;
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validateLastName(input, errorElement) {
    const value = input.value.trim();
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && !value) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    const regex = /^[A-Za-z\s'-]+$/;
    
    if (!value) {
        showError(input, errorElement, "Last name cannot be empty");
        return false;
    }
    
    if (!regex.test(value)) {
        showError(input, errorElement, "Can only contain letters, spaces, hyphens, or apostrophes");
        return false;
    }
    
    if (value.length < 2) {
        showError(input, errorElement, "Must be at least 2 characters");
        return false;
    }
    
    if (value.length > 50) {
        showError(input, errorElement, "Must be less than 50 characters");
        return false;
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validateEmail(input, errorElement) {
    const value = input.value.trim();
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && !value) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!value) {
        showError(input, errorElement, "Email cannot be empty");
        return false;
    }
    
    if (!regex.test(value)) {
        showError(input, errorElement, "Please enter a valid email (user@example.com)");
        return false;
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validatePhone(input, errorElement) {
    const value = input.value.trim();
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && !value) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    if (value || isRequired) {
        let numbers = value.replace(/\D/g, '');
        
        if (!numbers) {
            showError(input, errorElement, "Phone number cannot be empty");
            return false;
        }
        
        // Handle US numbers with country code
        if (numbers.length === 11 && numbers.startsWith('1')) {
            numbers = numbers.substring(1);
        }
        
        if (numbers.length !== 10) {
            showError(input, errorElement, "Please enter a valid 10-digit phone number");
            return false;
        }
        input.value =  numbers;
        
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validateSocialMedia(input, errorElement, platform) {
    const value = input.value.trim();
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && !value) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    const regex = /^[a-zA-Z0-9_.-]*$/;
    
    if (value && !regex.test(value)) {
        showError(input, errorElement, `Invalid ${platform} username`);
        return false;
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validateImage(input, errorElement, allowedTypes, maxSize = 10 * 1024 * 1024) {
    const isRequired = isFieldRequired(input.id);
    
    if (!isRequired && (!input.files || input.files.length === 0)) {
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    
    if (isRequired && (!input.files || input.files.length === 0)) {
        showError(input, errorElement, "Please upload an image");
        return false;
    }
    
    if (input.files.length > 0) {
        const file = input.files[0];
        
        if (!allowedTypes.includes(file.type)) {
            const allowedExtensions = allowedTypes.map(t => t.split('/')[1]).join(', ');
            showError(input, errorElement, `Allowed types: ${allowedExtensions}`);
            return false;
        }
        
        if (file.size > maxSize) {
            showError(input, errorElement, `File must be under ${maxSize/1024/1024}MB`);
            return false;
        }
    }
    
    showSuccess(input, errorElement);
    return true;
}

function validateGalleryImage(input, errorElement) {
    const allowedTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
        'image/webp', 'image/svg+xml', 'image/tiff',
        'image/heif', 'image/heic'
    ];
    return validateImage(input, errorElement, allowedTypes);
}

// Validating a certain form

function validateBarberProfileForm(event) {
    event.preventDefault(); 
    let isValid = true;
    
    // Validate required fields
    isValid = isValid && validateFirstName(
        document.getElementById('first_name'),
        document.getElementById('first_name-error')
    );
    
    isValid = isValid && validateLastName(
        document.getElementById('last_name'),
        document.getElementById('last_name-error')
    );
    
    isValid = isValid && validateEmail(
        document.getElementById('email'),
        document.getElementById('email-error')
    );
    
    // Phone is optional but must be valid if provided
    const phoneInput = document.getElementById('phone');
    if (phoneInput.value.trim()) {
        isValid = isValid && validatePhone(
            phoneInput,
            document.getElementById('phone-error')
        );
    }
    
    // Validate social media if provided
    const instagramInput = document.getElementById('instagram');
    if (instagramInput.value.trim()) {
        isValid = isValid && validateSocialMedia(
            instagramInput,
            document.getElementById('instagram-error'),
            'Instagram'
        );
    }
    
    const facebookInput = document.getElementById('facebook');
    if (facebookInput.value.trim()) {
        isValid = isValid && validateSocialMedia(
            facebookInput,
            document.getElementById('facebook-error'),
            'Facebook'
        );
    }
    
    const tiktokInput = document.getElementById('tiktok');
    if (tiktokInput.value.trim()) {
        isValid = isValid && validateSocialMedia(
            tiktokInput,
            document.getElementById('tiktok-error'),
            'TikTok'
        );
    }
    
    // Validate profile photo if changed
    const photoInput = document.getElementById('photo_image');
    if (photoInput.files.length > 0) {
        isValid = isValid && validateImage(
            photoInput,
            document.getElementById('photo_image-error'),
            [
                'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
                'image/webp', 'image/svg+xml', 'image/tiff',
                'image/heif', 'image/heic'
            ]
        );
    }
    
    // Validate gallery images
    document.querySelectorAll('.gallery-image-input').forEach(input => {
        if (input.files.length > 0) {
            const errorElement = input.closest('.gallery-item')?.querySelector('.gallery-error');
            if (errorElement) {
                isValid = isValid && validateImage(
                    input,
                    errorElement,
                    [
                        'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
                        'image/webp', 'image/svg+xml', 'image/tiff',
                        'image/heif', 'image/heic'
                    ]
                );
            }
        }
    });
    
    if (isValid) {
        // If validation passed, submit the form
        event.target.submit();
    } else {
        scrollToFirstError();
    }
    return false;
}

// initializating the forms

function initBarberProfileValidation() {
    const requiredFields = ['first_name', 'last_name', 'email'];
    const optionalFields = ['phone', 'photo_image', 'instagram', 'facebook', 'tiktok', 'new_gallery_images'];
    
    initFieldRequirements(requiredFields, optionalFields);
    
    // Set up event listeners for individual fields
    document.getElementById('first_name')?.addEventListener('blur', function() {
        validateFirstName(this, document.getElementById('first_name-error'));
    });
    
    document.getElementById('last_name')?.addEventListener('blur', function() {
        validateLastName(this, document.getElementById('last_name-error'));
    });
    
    document.getElementById('email')?.addEventListener('blur', function() {
        validateEmail(this, document.getElementById('email-error'));
    });
    
    document.getElementById('phone')?.addEventListener('blur', function() {
        validatePhone(this, document.getElementById('phone-error'));
    });
    
    document.getElementById('instagram')?.addEventListener('blur', function() {
        validateSocialMedia(this, document.getElementById('instagram-error'), 'Instagram');
    });
    
    document.getElementById('facebook')?.addEventListener('blur', function() {
        validateSocialMedia(this, document.getElementById('facebook-error'), 'Facebook');
    });
    
    document.getElementById('tiktok')?.addEventListener('blur', function() {
        validateSocialMedia(this, document.getElementById('tiktok-error'), 'TikTok');
    });
    
    // Profile photo validation and preview
    const photoInput = document.getElementById('photo_image');
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            validateImage(
                this,
                document.getElementById('photo_image-error'),
                [
                    'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
                    'image/webp', 'image/svg+xml', 'image/tiff',
                    'image/heif', 'image/heic'
                ]
            );
            previewImage(this, 'preview-image');
        });
    }
    
    // Gallery management
    const galleryContainer = document.getElementById('galleryContainer');
    const addGalleryBtn = document.getElementById('addGalleryBtn');
    
    if (addGalleryBtn && galleryContainer) {
        // Handle new gallery items
        addGalleryBtn.addEventListener('click', function() {
            const galleryItemTemplate = document.getElementById('galleryItemTemplate');
            if (galleryItemTemplate) {
                const newItem = galleryItemTemplate.content.cloneNode(true);
                const fileInput = newItem.querySelector('.gallery-image-input');
                const errorElement = newItem.querySelector('.gallery-error');
                
                fileInput.addEventListener('change', function() {
                    validateGalleryImage(this, errorElement);
                    previewGalleryImage(this);
                });
                
                galleryContainer.appendChild(newItem);
            }
        });
        
        // Validate existing gallery items on page load
        document.querySelectorAll('.gallery-image-input').forEach(input => {
            const errorElement = input.closest('.gallery-item')?.querySelector('.gallery-error');
            if (errorElement) {
                input.addEventListener('change', function() {
                    validateGalleryImage(this, errorElement);
                    previewGalleryImage(this);
                });
            }
        });
    }
    const form = document.getElementById('barber-profile-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            validateBarberProfileForm(e);
        });
    }
}

// Initialize appropriate validation based on page
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('barber-profile-form')) {
        initBarberProfileValidation();
    }
});