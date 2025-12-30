@extends('layouts.public')

@section('title', 'Menú - ' . $table->name)

@section('header-title', 'Menú - ' . $table->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Buffet Status Indicator -->
    <div id="buffet-status" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-900">Cupo disponible</p>
                <p class="text-xs text-blue-700">Máximo 5 ítems por persona cada 10 minutos</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-blue-900" id="available-slots">-</p>
                <p class="text-xs text-blue-700">disponibles</p>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="mb-6 overflow-x-auto">
        <div class="flex space-x-2 border-b border-gray-200" id="category-tabs">
            <button class="category-tab px-4 py-2 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 hover:border-gray-300 active" data-category="all">
                Todos
            </button>
            <!-- Categories will be loaded here -->
        </div>
    </div>

    <!-- Products Grid -->
    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Products will be loaded here -->
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-12">
        <p class="text-gray-500">No hay productos disponibles en esta categoría.</p>
    </div>
</div>

<!-- Floating Cart Button -->
<div id="cart-button" class="fixed bottom-6 right-6 z-50">
    <button onclick="openCart()" class="bg-indigo-600 text-white rounded-full p-4 shadow-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span class="font-semibold" id="cart-count">0</span>
    </button>
</div>

<!-- Cart Modal -->
<div id="cart-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center">
    <div class="bg-white rounded-t-lg sm:rounded-lg w-full sm:max-w-md max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Carrito</h2>
                <button onclick="closeCart()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="cart-items" class="space-y-4 mb-4">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-xl font-bold" id="cart-total">€0.00</span>
                </div>
                <button onclick="checkout()" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    Proceder al pago
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const tableToken = '{{ $table->qr_token }}';
let menuData = null;
let currentCategory = 'all';

// Load menu data
async function loadMenuData() {
    try {
        const response = await fetch(`/menu/${tableToken}/data`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Menu data loaded:', data); // Debug
        menuData = data;
        
        if (!menuData || (!menuData.dishes && !menuData.drinks)) {
            console.warn('No menu data received');
            document.getElementById('empty-state').classList.remove('hidden');
            document.getElementById('empty-state').innerHTML = '<p class="text-gray-500">No hay productos disponibles en este momento.</p>';
            return;
        }
        
        renderCategories();
        renderProducts('all');
        updateBuffetStatus();
    } catch (error) {
        console.error('Error loading menu:', error);
        document.getElementById('empty-state').classList.remove('hidden');
        document.getElementById('empty-state').innerHTML = '<p class="text-red-500">Error al cargar el menú. Por favor, recarga la página.</p>';
    }
}

// Render categories
function renderCategories() {
    const tabsContainer = document.getElementById('category-tabs');
    const categories = menuData.categories;
    
    categories.forEach(category => {
        const button = document.createElement('button');
        button.className = 'category-tab px-4 py-2 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 hover:border-gray-300';
        button.textContent = category.name;
        button.dataset.category = category.id;
        button.onclick = () => selectCategory(category.id);
        tabsContainer.appendChild(button);
    });
}

// Select category
function selectCategory(categoryId) {
    currentCategory = categoryId;
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.classList.remove('active', 'border-indigo-600', 'text-indigo-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    
    const activeTab = document.querySelector(`[data-category="${categoryId}"]`);
    if (activeTab) {
        activeTab.classList.add('active', 'border-indigo-600', 'text-indigo-600');
        activeTab.classList.remove('border-transparent', 'text-gray-600');
    }
    
    renderProducts(categoryId);
}

// Render products
function renderProducts(categoryId) {
    const grid = document.getElementById('products-grid');
    grid.innerHTML = '';
    
    let products = [];
    if (categoryId === 'all') {
        products = [...menuData.dishes, ...menuData.drinks];
    } else {
        products = [
            ...menuData.dishes.filter(d => d.category_id == categoryId),
            ...menuData.drinks.filter(d => d.category_id == categoryId)
        ];
    }
    
    if (products.length === 0) {
        document.getElementById('empty-state').classList.remove('hidden');
        return;
    }
    
    document.getElementById('empty-state').classList.add('hidden');
    
    products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow';
        card.innerHTML = `
            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover">` : '<div class="w-full h-48 flex items-center justify-center text-gray-400">Sin imagen</div>'}
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">${product.name}</h3>
                <p class="text-sm text-gray-600 mb-2">${product.description || ''}</p>
                ${product.allergens && product.allergens.length > 0 ? `<p class="text-xs text-orange-600 mb-2">Alérgenos: ${product.allergens.join(', ')}</p>` : ''}
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-indigo-600">€${parseFloat(product.price).toFixed(2)}</span>
                    <button onclick="addToCart(${product.id}, '${menuData.dishes.find(d => d.id === product.id) ? 'dish' : 'drink'}', 1)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Agregar
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Add to cart
async function addToCart(id, type, quantity) {
    try {
        const response = await fetch(`/api/cart/${tableToken}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id, type, quantity })
        });
        
        const data = await response.json();
        updateCartDisplay(data);
        showNotification('Producto agregado al carrito');
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error al agregar producto', 'error');
    }
}

// Update cart display
async function updateCartDisplay(cartData = null) {
    if (!cartData) {
        const response = await fetch(`/api/cart/${tableToken}`);
        cartData = await response.json();
    }
    
    document.getElementById('cart-count').textContent = cartData.count || 0;
    document.getElementById('cart-total').textContent = `€${cartData.total?.toFixed(2) || '0.00'}`;
    
    const itemsContainer = document.getElementById('cart-items');
    itemsContainer.innerHTML = '';
    
    if (cartData.items && cartData.items.length > 0) {
        cartData.items.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex justify-between items-center border-b pb-2';
            itemDiv.innerHTML = `
                <div class="flex-1">
                    <p class="font-medium">${item.name}</p>
                    <p class="text-sm text-gray-600">€${parseFloat(item.price).toFixed(2)} x ${item.quantity}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity(${item.id}, '${item.type}', ${item.quantity - 1})" class="text-gray-600 hover:text-gray-900">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, '${item.type}', ${item.quantity + 1})" class="text-gray-600 hover:text-gray-900">+</button>
                    <button onclick="removeFromCart(${item.id}, '${item.type}')" class="ml-4 text-red-600 hover:text-red-900">Eliminar</button>
                </div>
            `;
            itemsContainer.appendChild(itemDiv);
        });
    } else {
        itemsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">El carrito está vacío</p>';
    }
}

// Update quantity
async function updateQuantity(id, type, quantity) {
    if (quantity <= 0) {
        removeFromCart(id, type);
        return;
    }
    
    try {
        const response = await fetch(`/api/cart/${tableToken}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id, type, quantity })
        });
        
        const data = await response.json();
        updateCartDisplay(data);
    } catch (error) {
        console.error('Error updating quantity:', error);
    }
}

// Remove from cart
async function removeFromCart(id, type) {
    try {
        const response = await fetch(`/api/cart/${tableToken}/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id, type })
        });
        
        const data = await response.json();
        updateCartDisplay(data);
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}

// Open/close cart
function openCart() {
    document.getElementById('cart-modal').classList.remove('hidden');
    updateCartDisplay();
}

function closeCart() {
    document.getElementById('cart-modal').classList.add('hidden');
}

// Checkout
async function checkout() {
    try {
        const response = await fetch(`/checkout/${tableToken}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            window.location.href = `/order/${tableToken}/confirm/${data.order_id}`;
        } else {
            showNotification(data.message || data.error || 'Error al procesar el pedido', 'error');
        }
    } catch (error) {
        console.error('Error during checkout:', error);
        showNotification('Error al procesar el pedido', 'error');
    }
}

// Update buffet status
async function updateBuffetStatus() {
    try {
        const response = await fetch(`/checkout/${tableToken}/status`);
        const data = await response.json();
        
        const availableSlots = data.buffet_status.available;
        document.getElementById('available-slots').textContent = availableSlots;
        
        if (availableSlots === 0) {
            document.getElementById('buffet-status').classList.remove('bg-blue-50', 'border-blue-200');
            document.getElementById('buffet-status').classList.add('bg-red-50', 'border-red-200');
        }
    } catch (error) {
        console.error('Error updating buffet status:', error);
    }
}

// Show notification
function showNotification(message, type = 'success') {
    // Simple notification - can be enhanced with a toast library
    alert(message);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadMenuData();
    setInterval(updateBuffetStatus, 30000); // Update every 30 seconds
});
</script>
@endpush

