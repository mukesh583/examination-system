import './bootstrap';
import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally
window.Chart = Chart;

// Utility functions
window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Format number to 2 decimal places
window.formatNumber = function(num) {
    return parseFloat(num).toFixed(2);
};

// Format date
window.formatDate = function(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};

// Offline mode handling
window.addEventListener('online', () => {
    const banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.add('hidden');
    }
    console.log('Connection restored');
});

window.addEventListener('offline', () => {
    const banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.remove('hidden');
    }
    console.log('Connection lost - using cached data');
});

// Check initial connection status
if (!navigator.onLine) {
    const banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.remove('hidden');
    }
}
