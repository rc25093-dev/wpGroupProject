document.querySelectorAll('[data-protect="true"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
        if (!window.eventManagementLoggedIn) {
            e.preventDefault();
            alert('Please log in first to access this page.');
            window.location.href = 'login.php';
        }
    });
});

const overlay = document.getElementById('eventModalOverlay');
const openBtn = document.getElementById('openCreateModalBtn');
const closeBtn = document.getElementById('closeModalBtn');
const cancelBtn = document.getElementById('cancelModalBtn');
const imageInput = document.getElementById('eventimage');
const imagePreview = document.getElementById('eventImagePreview');
const imageLabel = document.getElementById('imagePreviewLabel');

function openModal() {
    overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    setTimeout(function () {
        const modalCard = document.querySelector('.evt-modal-card');
        if (modalCard) {
            modalCard.style.transform = 'translateY(0) scale(1)';
        }
    }, 10);
}

function closeModal() {
    overlay.classList.remove('is-open');
    document.body.style.overflow = '';
    if (imageInput) {
        imageInput.value = '';
    }
    if (imagePreview) {
        const currentSrc = imagePreview.getAttribute('data-original-src');
        imagePreview.src = currentSrc || '';
        imagePreview.style.display = currentSrc ? 'block' : 'none';
    }
    if (imageLabel) {
        imageLabel.textContent = 'Selected image will appear here.';
    }
}

if (openBtn) {
    openBtn.addEventListener('click', openModal);
}

if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
}

if (cancelBtn) {
    cancelBtn.addEventListener('click', closeModal);
}

if (overlay) {
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closeModal();
        }
    });
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay && overlay.classList.contains('is-open')) {
        closeModal();
    }
});

if (imageInput && imagePreview) {
    imagePreview.setAttribute('data-original-src', imagePreview.getAttribute('src') || '');
    imageInput.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            const originalSrc = imagePreview.getAttribute('data-original-src') || '';
            imagePreview.src = originalSrc;
            imagePreview.style.display = originalSrc ? 'block' : 'none';
            imageLabel.textContent = 'Selected image will appear here.';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            imageLabel.textContent = file.name;
        };
        reader.readAsDataURL(file);
    });
}
