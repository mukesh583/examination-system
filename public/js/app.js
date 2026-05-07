if (window.axios) {
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}

if (window.Alpine && typeof window.Alpine.start === 'function') {
    window.Alpine.start();
}

if (window.Chart) {
    window.Chart = window.Chart;
}

window.debounce = function (func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = function () {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

window.formatNumber = function (num) {
    return parseFloat(num).toFixed(2);
};

window.formatDate = function (dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

window.addEventListener('online', function () {
    var banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.add('hidden');
    }
    console.log('Connection restored');
});

window.addEventListener('offline', function () {
    var banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.remove('hidden');
    }
    console.log('Connection lost - using cached data');
});

if (!navigator.onLine) {
    var banner = document.getElementById('offline-banner');
    if (banner) {
        banner.classList.remove('hidden');
    }
}
