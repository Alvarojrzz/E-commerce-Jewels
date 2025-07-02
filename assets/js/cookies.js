// Función para comprobar si las cookies han sido aceptadas
const checkCookiesAccepted = () => {
    const cookiesAccepted = localStorage.getItem('cookiesAceptadas') === 'true';
    console.log('Estado de cookies:', cookiesAccepted);
    return cookiesAccepted;
};

// Función para ocultar el banner
const hideBanner = (banner) => {
    if (banner) {
        banner.classList.remove('show');
        setTimeout(() => {
            banner.style.display = 'none';
        }, 600);
    }
};

// Función para mostrar el banner
const showCookieBanner = () => {
    console.log('Inicializando banner de cookies...');
    const banner = document.getElementById('cookieConsent');
    console.log('Banner encontrado:', banner);
    
    if (!banner) {
        console.error('No se encontró el elemento del banner');
        return;
    }

    if (!checkCookiesAccepted()) {
        banner.style.display = 'flex';
        setTimeout(() => {
            banner.classList.add('show');
        }, 100);

        // Añadir event listeners a los botones
        const acceptButton = document.getElementById('acceptCookies');
        const closeButton = document.querySelector('.close-btn');

        if (acceptButton) {
            acceptButton.addEventListener('click', () => {
                console.log('Aceptando cookies...');
                try {
                    localStorage.setItem('cookiesAceptadas', 'true');
                    hideBanner(banner);
                } catch (error) {
                    console.error('Error al guardar las cookies:', error);
                }
            });
        }

        if (closeButton) {
            closeButton.addEventListener('click', () => {
                console.log('Cerrando banner...');
                hideBanner(banner);
            });
        }
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', showCookieBanner);