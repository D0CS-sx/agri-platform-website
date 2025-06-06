// Toggle Navbar on Mobile
document.addEventListener('DOMContentLoaded', () => {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('#navbarNav');

    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', () => {
            navbarCollapse.classList.toggle('show');
        });
    }
});

// Form Validation
const validateForm = (formId) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', (event) => {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;

        inputs.forEach((input) => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Please fill out all required fields.');
        }
    });
};

// Initialize Form Validation
validateForm('addProductForm');

// Scroll to Top Button
const scrollToTopButton = document.getElementById('scrollToTop');
if (scrollToTopButton) {
    scrollToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Dynamic Alerts
const showAlert = (message, type = 'success') => {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;

    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
};

// Toast Notification
const showToast = (message, type = 'success') => {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
    toast.style.color = '#ffffff';
    toast.style.padding = '10px 20px';
    toast.style.borderRadius = '5px';
    toast.style.zIndex = '9999';
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
};

// Show Loading Indicator
const showLoading = () => {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.style.position = 'fixed';
    loadingOverlay.style.top = '0';
    loadingOverlay.style.left = '0';
    loadingOverlay.style.width = '100%';
    loadingOverlay.style.height = '100%';
    loadingOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    loadingOverlay.style.zIndex = '9999';
    loadingOverlay.style.display = 'flex';
    loadingOverlay.style.justifyContent = 'center';
    loadingOverlay.style.alignItems = 'center';
    loadingOverlay.innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    document.body.appendChild(loadingOverlay);
};

// Hide Loading Indicator
const hideLoading = () => {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
};

// Example Usage
document.querySelector('form').addEventListener('submit', (event) => {
    showLoading();
    setTimeout(hideLoading, 3000); // Simulate loading for 3 seconds
});

// Smooth Scroll for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (event) => {
        event.preventDefault();
        const target = document.querySelector(anchor.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Confirmation Dialog
document.querySelectorAll('.confirm-action').forEach((button) => {
    button.addEventListener('click', (event) => {
        const confirmed = confirm('Are you sure you want to proceed?');
        if (!confirmed) {
            event.preventDefault();
        }
    });
});

// Example AJAX Request with Error Handling
const fetchData = async (url) => {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error fetching data:', error);
        showAlert('Failed to fetch data. Please try again.', 'danger');
    }
};

// Example Usage
fetchData('/api/products');

// Toggle Dark Mode
const toggleDarkMode = () => {
    const isDarkMode = document.body.classList.toggle('dark-mode');
    document.querySelectorAll('.navbar, .navbar-brand, .nav-link, .card, .card-title, .card-text, .panel, footer, footer h5, footer p, footer a, .btn').forEach((element) => {
        element.classList.toggle('dark-mode');
    });

    // Save the user's preference in localStorage
    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
};

// Apply Dark Mode Based on User Preference
const applyDarkModePreference = () => {
    const darkModePreference = localStorage.getItem('darkMode');
    if (darkModePreference === 'enabled') {
        document.body.classList.add('dark-mode');
        document.querySelectorAll('.navbar, .navbar-brand, .nav-link, .card, .card-title, .card-text, .panel, footer, footer h5, footer p, footer a, .btn').forEach((element) => {
            element.classList.add('dark-mode');
        });
    }
};

// Add Event Listener to Dark Mode Toggle Button
document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

// Apply the preference on page load
document.addEventListener('DOMContentLoaded', applyDarkModePreference);

// Numeric Input Validation
document.querySelectorAll('input[type="number"]').forEach((input) => {
    input.addEventListener('input', () => {
        if (input.value < 0) {
            input.value = 0; // Prevent negative values
        }
    });
});

