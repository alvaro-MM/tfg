import Alpine from 'alpinejs'

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

// Escucha eventos de Livewire para redirección después de que Livewire esté listo
document.addEventListener('livewire:initialized', () => {
    window.Livewire.on('redirect-to-confirm', (event) => {
        if (event.url) {
            window.location.href = event.url;
        }
    });
});
