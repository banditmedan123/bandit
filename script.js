// DOM Elements
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');
const navLinks = document.querySelectorAll('.nav-link');

// Toggle Sidebar
menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 1024) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// Navigation active state
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        // Remove active class from all links
        navLinks.forEach(l => l.parentElement.classList.remove('active'));
        // Add active class to clicked link
        link.parentElement.classList.add('active');
    });
});

// Logout function
function handleLogout() {
    if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
        // Determine the correct path to logout.php based on current page
        let logoutPath = 'api/logout.php';
        if (window.location.pathname.includes('/pages/')) {
            logoutPath = '../api/logout.php';
        }
        
        fetch(logoutPath, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Determine the correct path to login.html based on current page
                let loginPath = 'login.html';
                if (window.location.pathname.includes('/pages/')) {
                    loginPath = '../login.html';
                }
                window.location.href = loginPath;
            } else {
                alert('Gagal logout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: redirect to login page
            let loginPath = 'login.html';
            if (window.location.pathname.includes('/pages/')) {
                loginPath = '../login.html';
            }
            window.location.href = loginPath;
        });
    }
}

// Add logout event listener to logout button
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleLogout();
        });
    }
});

// Chart.js Configuration
const chartColors = {
    primary: '#667eea',
    secondary: '#764ba2',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#3b82f6',
    light: '#f1f5f9',
    dark: '#1e293b'
};

// Faculty Chart
const facultyCtx = document.getElementById('facultyChart');
if (facultyCtx) {
    new Chart(facultyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Teknik', 'Ekonomi', 'Hukum', 'Kedokteran', 'MIPA', 'Sastra'],
            datasets: [{
                data: [650, 520, 380, 420, 480, 397],
                backgroundColor: [
                    chartColors.primary,
                    chartColors.secondary,
                    chartColors.success,
                    chartColors.warning,
                    chartColors.danger,
                    chartColors.info
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            }
        }
    });
}

// Enrollment Chart
const enrollmentCtx = document.getElementById('enrollmentChart');
if (enrollmentCtx) {
    new Chart(enrollmentCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendaftaran 2023',
                data: [120, 135, 110, 95, 85, 75, 90, 105, 125, 140, 130, 115],
                borderColor: chartColors.primary,
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Pendaftaran 2024',
                data: [130, 145, 125, 110, 100, 90, 105, 120, 140, 155, 145, 130],
                borderColor: chartColors.secondary,
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                y: {
                    grid: {
                        color: '#e2e8f0'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });
}

// Animate stats on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe stat cards for animation
document.querySelectorAll('.stat-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Search functionality
const searchInput = document.querySelector('.search-box input');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        // Add search logic here
        console.log('Searching for:', searchTerm);
    });
}

// Quick action buttons
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const action = e.currentTarget.querySelector('span').textContent;
        console.log('Action clicked:', action);
        
        // Add action logic here
        showNotification(`${action} sedang diproses...`);
    });
});

// Notification function
function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-info-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-left: 4px solid ${chartColors.primary};
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        padding: 1rem 1.5rem;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    `;
    
    notification.querySelector('.notification-content').style.cssText = `
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #1e293b;
        font-size: 0.875rem;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add loading animation to charts
function addLoadingAnimation() {
    const chartContainers = document.querySelectorAll('.chart-content');
    chartContainers.forEach(container => {
        const loading = document.createElement('div');
        loading.className = 'chart-loading';
        loading.innerHTML = `
            <div class="loading-spinner"></div>
            <p>Memuat data...</p>
        `;
        loading.style.cssText = `
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #64748b;
            font-size: 0.875rem;
        `;
        
        const spinner = loading.querySelector('.loading-spinner');
        spinner.style.cssText = `
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top: 3px solid ${chartColors.primary};
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        `;
        
        container.appendChild(loading);
        
        // Remove loading after chart is ready
        setTimeout(() => {
            if (loading.parentNode) {
                loading.parentNode.removeChild(loading);
            }
        }, 1000);
    });
}

// Add CSS animation for spinner
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Initialize loading animation
addLoadingAnimation();

// Add smooth scrolling for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add hover effects for better UX
document.querySelectorAll('.stat-card, .chart-container, .recent-activities, .quick-actions').forEach(element => {
    element.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    element.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Initialize tooltips for better UX
document.querySelectorAll('[title]').forEach(element => {
    element.addEventListener('mouseenter', function(e) {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = this.getAttribute('title');
        tooltip.style.cssText = `
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            z-index: 1000;
            pointer-events: none;
            white-space: nowrap;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        
        this.addEventListener('mouseleave', () => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, { once: true });
    });
});

console.log('SIOM Dashboard loaded successfully!'); 