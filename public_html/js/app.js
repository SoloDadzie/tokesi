// Cart functionality with Laravel backend integration
const cartState = {
  items: [],
  count: 0,
  total: 0,
};

// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Fetch helper
async function fetchAPI(url, options = {}) {
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
  };
  
  const response = await fetch(url, { ...defaultOptions, ...options });
  return response.json();
}

// Load cart on page load
async function loadCart() {
  try {
    const data = await fetchAPI('/api/cart');
    if (data.success) {
      cartState.items = data.items;
      cartState.count = data.count;
      cartState.total = data.total;
      updateCartBadge();
      // Only render if cart panel is open
      if (cartPanel.style.right === '0px') {
        renderCart();
      }
    }
  } catch (error) {
    console.error('Failed to load cart:', error);
  }
}

// Add to cart with loading state
async function addToCart(productId, quantity = 1, couponCode = null, buttonElement = null) {
  // Find button if clicked from event
  if (!buttonElement && event && event.target) {
    buttonElement = event.target.closest('button');
  }
  
  // Set loading state
  if (buttonElement) {
    buttonElement.disabled = true;
    const originalContent = buttonElement.innerHTML;
    buttonElement.setAttribute('data-original-content', originalContent);
    buttonElement.innerHTML = `
      <svg class="animate-spin w-4 h-4 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      Adding...
    `;
  }
  
  try {
    const data = await fetchAPI('/api/cart/add', {
      method: 'POST',
      body: JSON.stringify({ 
        product_id: productId, 
        quantity,
        coupon_code: couponCode 
      }),
    });
    
    if (data.success) {
      // Update state immediately from response
      if (data.items) {
        cartState.items = data.items;
        cartState.count = data.count;
        cartState.total = data.total;
      } else {
        // Fallback: reload cart data
        await loadCart();
      }
      
      updateCartBadge();
      // Render immediately if cart is open
      if (cartPanel.style.right === '0px') {
        renderCart();
      }
      
      let message = data.message;
      if (data.discount_applied) {
        message += ' with discount!';
      }
      showNotification(message, 'success');
      
      // Reset button state
      if (buttonElement) {
        buttonElement.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 inline-block mr-1.5">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
          </svg>
          Added!
        `;
        setTimeout(() => {
          buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
          buttonElement.disabled = false;
        }, 1500);
      }
      
      return true;
    } else {
      showNotification(data.message, 'error');
      
      // Reset button state on error
      if (buttonElement) {
        buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
        buttonElement.disabled = false;
      }
      
      return false;
    }
  } catch (error) {
    console.error('Failed to add to cart:', error);
    showNotification('Failed to add item to cart', 'error');
    
    // Reset button state on error
    if (buttonElement) {
      buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
      buttonElement.disabled = false;
    }
    
    return false;
  }
}

// Update quantity
async function updateQuantity(cartItemId, change) {
  const item = cartState.items.find(i => i.id === cartItemId);
  if (!item) return;
  
  const newQuantity = item.quantity + change;
  if (newQuantity <= 0) {
    return deleteCartItem(cartItemId);
  }
  
  // Optimistic update - update UI immediately
  item.quantity = newQuantity;
  item.subtotal = item.price * newQuantity;
  cartState.total = cartState.items.reduce((sum, i) => sum + i.subtotal, 0);
  renderCart();
  updateCartBadge();
  
  try {
    const data = await fetchAPI(`/api/cart/${cartItemId}`, {
      method: 'PUT',
      body: JSON.stringify({ quantity: newQuantity }),
    });
    
    if (data.success) {
      // Update with accurate server data
      if (data.items) {
        cartState.items = data.items;
        cartState.count = data.count;
        cartState.total = data.total;
      } else {
        await loadCart();
      }
      renderCart();
      updateCartBadge();
    } else {
      // Revert on error
      await loadCart();
      renderCart();
      showNotification(data.message, 'error');
    }
  } catch (error) {
    // Revert on error
    await loadCart();
    renderCart();
    console.error('Failed to update quantity:', error);
    showNotification('Failed to update quantity', 'error');
  }
}

// Delete cart item
async function deleteCartItem(cartItemId) {
  // Optimistic update - remove from UI immediately
  const itemIndex = cartState.items.findIndex(i => i.id === cartItemId);
  if (itemIndex === -1) return;
  
  const removedItem = cartState.items.splice(itemIndex, 1)[0];
  cartState.count = cartState.items.reduce((sum, i) => sum + i.quantity, 0);
  cartState.total = cartState.items.reduce((sum, i) => sum + i.subtotal, 0);
  renderCart();
  updateCartBadge();
  
  try {
    const data = await fetchAPI(`/api/cart/${cartItemId}`, {
      method: 'DELETE',
    });
    
    if (data.success) {
      // Update with accurate server data
      if (data.items) {
        cartState.items = data.items;
        cartState.count = data.count;
        cartState.total = data.total;
      } else {
        await loadCart();
      }
      renderCart();
      updateCartBadge();
      showNotification(data.message, 'success');
    } else {
      // Revert on error
      cartState.items.splice(itemIndex, 0, removedItem);
      await loadCart();
      renderCart();
      showNotification(data.message, 'error');
    }
  } catch (error) {
    // Revert on error
    cartState.items.splice(itemIndex, 0, removedItem);
    await loadCart();
    renderCart();
    console.error('Failed to remove item:', error);
    showNotification('Failed to remove item', 'error');
  }
}

// Update cart badge
function updateCartBadge() {
  const cartBadge = document.getElementById('cartBadge');
  cartBadge.textContent = cartState.count;
  
  if (cartState.count === 0) {
    cartBadge.classList.add('hidden');
    cartBadge.classList.remove('flex');
  } else {
    cartBadge.classList.remove('hidden');
    cartBadge.classList.add('flex');
  }
}

// Render cart
function renderCart() {
  const cartItemsContainer = document.getElementById('cartItems');
  const cartSummary = document.getElementById('cartSummary');
  const cartTotalPrice = document.getElementById('cartTotalPrice');

  if (cartState.items.length === 0) {
    cartItemsContainer.innerHTML = `
      <div class="flex flex-col items-center justify-center h-full p-10 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 fill-gray-300 mb-5" viewBox="0 0 24 24">
          <path d="M4.00488 16V4H2.00488V2H5.00488C5.55717 2 6.00488 2.44772 6.00488 3V15H18.4433L20.4433 7H8.00488V5H21.7241C22.2764 5 22.7241 5.44772 22.7241 6C22.7241 6.08176 22.7141 6.16322 22.6942 6.24254L20.1942 16.2425C20.083 16.6877 19.683 17 19.2241 17H5.00488C4.4526 17 4.00488 16.5523 4.00488 16ZM6.00488 23C4.90031 23 4.00488 22.1046 4.00488 21C4.00488 19.8954 4.90031 19 6.00488 19C7.10945 19 8.00488 19.8954 8.00488 21C8.00488 22.1046 7.10945 23 6.00488 23ZM18.0049 23C16.9003 23 16.0049 22.1046 16.0049 21C16.0049 19.8954 16.9003 19 18.0049 19C19.1094 19 20.0049 19.8954 20.0049 21C20.0049 22.1046 19.1094 23 18.0049 23Z"></path>
        </svg>
        <h3 class="text-xl text-gray-800 mb-2.5">Your cart is empty</h3>
        <p class="text-gray-600 mb-6 text-sm">Add some items to get started</p>
        <button class="px-[30px] py-3 bg-[rgb(24,24,24)] text-white border-none rounded-lg text-sm font-semibold cursor-pointer hover:bg-[rgb(40,40,40)] transition-colors" onclick="closeCart()">Continue Shopping</button>
      </div>
    `;
    cartSummary.classList.add('hidden');
    return;
  }

  cartItemsContainer.innerHTML = cartState.items.map(item => `
    <div class="grid grid-cols-[70px_1fr_auto] gap-3 mb-5 pb-5 border-b border-gray-200 last:border-b-0">
      <div class="w-[70px] h-[70px] bg-gray-100 rounded-lg overflow-hidden">
        <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.name}" class="w-full h-full object-cover">
      </div>
      <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-1.5">${item.name}</h4>
        <p class="text-xs text-gray-600 mb-2.5">£${item.price.toFixed(2)} each</p>
        <div class="flex items-center gap-2 mt-2">
          <button class="w-6 h-6 border border-gray-300 bg-white rounded cursor-pointer flex items-center justify-center text-base font-semibold text-gray-800 hover:bg-gray-100 hover:border-gray-800 active:scale-95 transition-all" onclick="updateQuantity(${item.id}, -1)">−</button>
          <span class="min-w-[30px] text-center text-sm font-semibold text-gray-800">${item.quantity}</span>
          <button class="w-6 h-6 border border-gray-300 bg-white rounded cursor-pointer flex items-center justify-center text-base font-semibold text-gray-800 hover:bg-gray-100 hover:border-gray-800 active:scale-95 transition-all" onclick="updateQuantity(${item.id}, 1)">+</button>
        </div>
      </div>
      <div class="flex flex-col items-end justify-between">
        <div class="text-[15px] font-semibold text-gray-800">£${item.subtotal.toFixed(2)}</div>
        <svg class="cursor-pointer w-[18px] h-[18px] fill-[#ff4444] hover:scale-110 transition-transform" onclick="deleteCartItem(${item.id})" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path>
        </svg>
      </div>
    </div>
  `).join('');

  cartTotalPrice.textContent = `£${cartState.total.toFixed(2)}`;
  cartSummary.classList.remove('hidden');
}

// Notification system
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `fixed top-24 right-6 z-[200] px-6 py-3 rounded-lg text-white font-medium shadow-lg transition-all duration-300 ${
    type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'
  }`;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.opacity = '0';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Menu toggle
const menuBtn = document.getElementById('menuBtn');
const mobileMenu = document.getElementById('mobileMenu');

menuBtn.addEventListener('click', () => {
  menuBtn.classList.toggle('active');
  if (mobileMenu.classList.contains('max-h-0')) {
    mobileMenu.classList.remove('max-h-0', 'px-0');
    mobileMenu.style.maxHeight = '400px';
    mobileMenu.style.padding = '20px';
  } else {
    mobileMenu.classList.add('max-h-0', 'px-0');
    mobileMenu.style.maxHeight = '0';
    mobileMenu.style.padding = '0 20px';
  }
});

// Search functionality
const searchModal = document.getElementById('searchModal');
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const searchBoxDesktop = document.getElementById('searchBoxDesktop');
const searchBoxMobile = document.getElementById('searchBoxMobile');

function openSearchModal() {
  searchModal.classList.remove('hidden');
  searchModal.classList.add('flex');
  searchInput.focus();
}

function closeSearchModal() {
  searchModal.classList.add('hidden');
  searchModal.classList.remove('flex');
  searchInput.value = '';
  searchResults.innerHTML = '';
}

searchBoxDesktop.addEventListener('click', openSearchModal);
searchBoxMobile.addEventListener('click', openSearchModal);
searchModal.addEventListener('click', (e) => {
  if (e.target === searchModal) closeSearchModal();
});

document.addEventListener('keydown', (e) => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    openSearchModal();
  }
  if (e.key === 'Escape') {
    closeSearchModal();
    closeCart();
  }
});

// Search with backend
let searchTimeout;
searchInput.addEventListener('input', async (e) => {
  clearTimeout(searchTimeout);
  const query = e.target.value.trim();
  
  if (query.length === 0) {
    searchResults.innerHTML = '';
    return;
  }

  searchTimeout = setTimeout(async () => {
    try {
      const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
      const products = await response.json();

      if (products.length === 0) {
        searchResults.innerHTML = '<div class="p-5 text-center text-gray-600">No results found</div>';
        return;
      }

      searchResults.innerHTML = products.map(product => `
        <a href="${product.url}" class="block p-3 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
          <div class="flex items-center gap-3">
            ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-12 h-12 object-cover rounded">` : ''}
            <div>
              <h4 class="text-sm mb-1 text-gray-800 font-semibold">${product.name}</h4>
              <p class="text-xs text-gray-600">${product.category} • ${product.price}</p>
            </div>
          </div>
        </a>
      `).join('');
    } catch (error) {
      console.error('Search failed:', error);
      searchResults.innerHTML = '<div class="p-5 text-center text-gray-600">Search failed</div>';
    }
  }, 300);
});

// Cart panel
const cartBtn = document.getElementById('cartBtn');
const cartPanel = document.getElementById('cartPanel');
const cartOverlay = document.getElementById('cartOverlay');
const cartClose = document.getElementById('cartClose');

async function openCart() {
  if (window.innerWidth <= 900) {
    cartPanel.style.width = '100%';
    cartPanel.style.height = '100vh';
    cartPanel.style.maxHeight = '100vh';
    cartPanel.style.top = '0';
    cartPanel.style.borderRadius = '0';
  } else {
    cartPanel.style.width = '400px';
    cartPanel.style.height = '85vh';
    cartPanel.style.maxHeight = '700px';
    cartPanel.style.top = '80px';
    cartPanel.style.borderRadius = '12px 0 0 12px';
  }
  
  cartPanel.style.right = '0';
  cartOverlay.classList.remove('hidden');
  
  // Render immediately with cached data
  renderCart();
  
  // Then refresh in background (non-blocking)
  loadCart().then(() => renderCart());
}

function closeCart() {
  if (window.innerWidth <= 900) {
    cartPanel.style.right = '-100%';
  } else {
    cartPanel.style.right = '-400px';
  }
  cartOverlay.classList.add('hidden');
}

cartBtn.addEventListener('click', openCart);
cartClose.addEventListener('click', closeCart);
cartOverlay.addEventListener('click', closeCart);

// Responsive cart panel
window.addEventListener('resize', () => {
  if (window.innerWidth <= 900) {
    cartPanel.style.width = '100%';
    cartPanel.style.height = '100vh';
    cartPanel.style.maxHeight = '100vh';
    cartPanel.style.top = '0';
    cartPanel.style.borderRadius = '0';
    if (!cartOverlay.classList.contains('hidden')) {
      cartPanel.style.right = '0';
    } else {
      cartPanel.style.right = '-100%';
    }
  } else {
    cartPanel.style.width = '400px';
    cartPanel.style.height = '85vh';
    cartPanel.style.maxHeight = '700px';
    cartPanel.style.top = '80px';
    cartPanel.style.borderRadius = '12px 0 0 12px';
    if (!cartOverlay.classList.contains('hidden')) {
      cartPanel.style.right = '0';
    } else {
      cartPanel.style.right = '-400px';
    }
  }
});

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', loadCart);


  //script to change between image in product details/

    