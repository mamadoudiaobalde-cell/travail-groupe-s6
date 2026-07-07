// Système de notifications
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.container = null;
        this.init();
    }
    
    init() {
        // Créer le conteneur de notifications
        this.container = document.createElement('div');
        this.container.className = 'notification-container';
        this.container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        `;
        document.body.appendChild(this.container);
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = 'notification notification-${type}';
        notification.style.cssText = `
            background: white;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid ${this.getColor(type)};
        `;
        
        const icon = this.getIcon(type);
        notification.innerHTML = `
            <div style="flex-shrink: 0; font-size: 20px; color: ${this.getColor(type)};">
                <i class="fas ${icon}"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 500; color: #333;">${message}</div>
            </div>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #999; padding: 0 5px;">
                &times;
            </button>
        `;
        
        this.container.appendChild(notification);
        this.notifications.push(notification);
        
        // Auto-suppression
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100px)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }
        
        return notification;
    }
    
    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }
    
    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }
    
    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration);
    }
    
    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
    
    getColor(type) {
        const colors = {
            success: '#2e7d32',
            error: '#d32f2f',
            warning: '#ed6c02',
            info: '#1976d2'
        };
        return colors[type] || colors.info;
    }
    
    getIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }
    
    clear() {
        this.notifications.forEach(n => n.remove());
        this.notifications = [];
    }
}

// Initialiser
const notifications = new NotificationManager();

// Fonctions globales
function showNotification(message, type = 'info', duration = 5000) {
    return notifications.show(message, type, duration);
}

function showSuccess(message, duration = 5000) {
    return notifications.success(message, duration);
}

function showError(message, duration = 5000) {
    return notifications.error(message, duration);
}

function showWarning(message, duration = 5000) {
    return notifications.warning(message, duration);
}

function showInfo(message, duration = 5000) {
    return notifications.info(message, duration);
}

// Styles additionnels
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .notification {
        transition: all 0.3s ease;
    }
    
    .notification:hover {
        transform: translateX(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 480px) {
        .notification-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
`;
document.head.appendChild(style);

// Exporter
window.NotificationManager = NotificationManager;
window.notifications = notifications;
window.showNotification = showNotification;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;// Système de notifications
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.container = null;
        this.init();
    }
    
    init() {
        // Créer le conteneur de notifications
        this.container = document.createElement('div');
        this.container.className = 'notification-container';
        this.container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        `;
        document.body.appendChild(this.container);
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className =' notification notification-${type}';
        notification.style.cssText = `
            background: white;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid ${this.getColor(type)};
        `;
        
        const icon = this.getIcon(type);
        notification.innerHTML = `
            <div style="flex-shrink: 0; font-size: 20px; color: ${this.getColor(type)};">
                <i class="fas ${icon}"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 500; color: #333;">${message}</div>
            </div>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #999; padding: 0 5px;">
                &times;
            </button>
        `;
        
        this.container.appendChild(notification);
        this.notifications.push(notification);
        
        // Auto-suppression
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100px)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }
        
        return notification;
    }
    
    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }
    
    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }
    
    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration);
    }
    
    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
    
    getColor(type) {
        const colors = {
            success: '#2e7d32',
            error: '#d32f2f',
            warning: '#ed6c02',
            info: '#1976d2'
        };
        return colors[type] || colors.info;
    }
    
    getIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }
    
    clear() {
        this.notifications.forEach(n => n.remove());
        this.notifications = [];
    }
}

// Initialiser
const notifications = new NotificationManager();

// Fonctions globales
function showNotification(message, type = 'info', duration = 5000) {
    return notifications.show(message, type, duration);
}

function showSuccess(message, duration = 5000) {
    return notifications.success(message, duration);
}

function showError(message, duration = 5000) {
    return notifications.error(message, duration);
}

function showWarning(message, duration = 5000) {
    return notifications.warning(message, duration);
}

function showInfo(message, duration = 5000) {
    return notifications.info(message, duration);
}

// Styles additionnels
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .notification {
        transition: all 0.3s ease;
    }
    
    .notification:hover {
        transform: translateX(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 480px) {
        .notification-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
`;
document.head.appendChild(style);

// Exporter
window.NotificationManager = NotificationManager;
window.notifications = notifications;
window.showNotification = showNotification;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;