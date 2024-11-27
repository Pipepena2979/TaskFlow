const mobileMenuBtn = document.querySelector('#mobile-menu');
const CerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function() {
        sidebar.classList.add('mostrar');
    });
}

if(CerrarMenuBtn) {
    CerrarMenuBtn.addEventListener('click', function() {
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 500);
    });
}

//** SE ELIMINA LA CLASE DE MOSTRAR EN M.TABLET, M.DESKTOP Y MAYORES */
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function() {
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
    }
})