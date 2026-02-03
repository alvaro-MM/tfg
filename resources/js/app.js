import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

// Función para mostrar/ocultar el menú desplegable
window.toggleDropdown = function () {
    document.getElementById("dropdownMenu").classList.toggle("hidden");
}

// Cerrar el menú al hacer clic fuera de él
document.addEventListener("click", function(event) {
    if (!event.target.closest(".dropdown-container")) {
        document.getElementById("dropdownMenu")?.classList.add("hidden");
    }
});

