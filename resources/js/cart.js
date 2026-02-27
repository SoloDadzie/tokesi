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
      const cartPanel = document.getElementById('cartPanel');
      if (cartPanel && cartPanel.style.right === '0px') {
        renderCart();
      }
    }
  } catch (error) {
    console.error('Failed to load cart:', error);
  }
}

// Add to cart with loading state
window.addToCart = async function(productId, quantity = 1, couponCode = null, buttonElement = null) {
  if (!buttonElement && event && event.target) {
    buttonElement = event.target.closest('button');
  }
  
  if (buttonElement) {
    buttonElement.disabled = true;
    const originalContent = buttonElement.innerHTML;
    buttonElement.setAttribute('data-original-content', originalContent);
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
      if (data.items) {
        cartState.items = data.items;
        cartState.count = data.count;
        cartState.total = data.total;
      } else {
        await loadCart();
      }
      
      updateCartBadge();
      const cartPanel = document.getElementById('cartPanel');
      if (cartPanel && cartPanel.style.right === '0px') {
        renderCart();
      }
      
      let message = data.message;
      if (data.discount_applied) {
        message += ' with discount!';
      }
      showNotification(message, 'success');
      
      if (buttonElement) {
        const originalWidth = buttonElement.offsetWidth;
        buttonElement.style.minWidth = originalWidth + 'px';
        buttonElement.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg><span style="vertical-align: middle;">Added</span>`;
        setTimeout(() => {
          buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
          buttonElement.style.minWidth = '';
          buttonElement.disabled = false;
        }, 1500);
      }
      
      return true;
    } else {
      showNotification(data.message, 'error');
      if (buttonElement) {
        buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
        buttonElement.disabled = false;
      }
      return false;
    }
  } catch (error) {
    console.error('Failed to add to cart:', error);
    showNotification('Failed to add item to cart', 'error');
    if (buttonElement) {
      buttonElement.innerHTML = buttonElement.getAttribute('data-original-content');
      buttonElement.disabled = false;
    }
    return false;
  }
};

// Update quantity
window.updateQuantity = async function(cartItemId, change) {
  const item = cartState.items.find(i => i.id === cartItemId);
  if (!item) return;
  
  const newQuantity = item.quantity + change;
  if (newQuantity <= 0) {
    return deleteCartItem(cartItemId);
  }
  
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
      await loadCart();
      renderCart();
      showNotification(data.message, 'error');
    }
  } catch (error) {
    await loadCart();
    renderCart();
    console.error('Failed to update quantity:', error);
    showNotification('Failed to update quantity', 'error');
  }
};

// Delete cart item
window.deleteCartItem = async function(cartItemId) {
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
      cartState.items.splice(itemIndex, 0, removedItem);
      await loadCart();
      renderCart();
      showNotification(data.message, 'error');
    }
  } catch (error) {
    cartState.items.splice(itemIndex, 0, removedItem);
    await loadCart();
    renderCart();
    console.error('Failed to remove item:', error);
    showNotification('Failed to remove item', 'error');
  }
};

// Update cart badge
function updateCartBadge() {
  const cartBadge = document.getElementById('cartBadge');
  if (!cartBadge) return;
  cartBadge.textContent = cartState.count;
  cartBadge.style.display = cartState.count === 0 ? 'none' : 'flex';
}

// Render cart
function renderCart() {
  const cartItemsContainer = document.getElementById('cartItems');
  const cartSummary = document.getElementById('cartSummary');
  const cartTotalPrice = document.getElementById('cartTotalPrice');

  if (cartState.items.length === 0) {
    cartItemsContainer.innerHTML = `
      <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:2.5rem;text-align:center;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:80px;height:80px;fill:#ccc;margin-bottom:1.25rem;" viewBox="0 0 24 24">
          <path d="M4.00488 16V4H2.00488V2H5.00488C5.55717 2 6.00488 2.44772 6.00488 3V15H18.4433L20.4433 7H8.00488V5H21.7241C22.2764 5 22.7241 5.44772 22.7241 6C22.7241 6.08176 22.7141 6.16322 22.6942 6.24254L20.1942 16.2425C20.083 16.6877 19.683 17 19.2241 17H5.00488C4.4526 17 4.00488 16.5523 4.00488 16ZM6.00488 23C4.90031 23 4.00488 22.1046 4.00488 21C4.00488 19.8954 4.90031 19 6.00488 19C7.10945 19 8.00488 19.8954 8.00488 21C8.00488 22.1046 7.10945 23 6.00488 23ZM18.0049 23C16.9003 23 16.0049 22.1046 16.0049 21C16.0049 19.8954 16.9003 19 18.0049 19C19.1094 19 20.0049 19.8954 20.0049 21C20.0049 22.1046 19.1094 23 18.0049 23Z"></path>
        </svg>
        <h3 style="font-family:var(--font-serif);font-size:1.25rem;color:var(--color-text-dark);margin-bottom:0.5rem;">Your cart is empty</h3>
        <p style="color:var(--color-text-light);font-size:0.9rem;margin-bottom:1.5rem;">Add some items to get started</p>
        <button class="btn btn-primary" onclick="closeCart()">Continue Shopping</button>
      </div>
    `;
    cartSummary.style.display = 'none';
    return;
  }

  cartItemsContainer.innerHTML = cartState.items.map(item => `
    <div style="display:grid;grid-template-columns:70px 1fr auto;gap:0.75rem;margin-bottom:1.25rem;padding-bottom:1.25rem;border-bottom:1px solid var(--color-border);">
      <div style="width:70px;height:70px;background:var(--color-cream);border-radius:4px;overflow:hidden;">
        <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div>
        <h4 style="font-size:0.9rem;font-weight:600;color:var(--color-text-dark);margin-bottom:0.25rem;">${item.name}</h4>
        <p style="font-size:0.8rem;color:var(--color-text-light);margin-bottom:0.5rem;">£${item.price.toFixed(2)} each</p>
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <button onclick="updateQuantity(${item.id}, -1)" style="width:26px;height:26px;border:1px solid var(--color-border);background:var(--color-white);border-radius:4px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">−</button>
          <span style="min-width:28px;text-align:center;font-size:0.9rem;font-weight:600;color:var(--color-text-dark);">${item.quantity}</span>
          <button onclick="updateQuantity(${item.id}, 1)" style="width:26px;height:26px;border:1px solid var(--color-border);background:var(--color-white);border-radius:4px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">+</button>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:flex-end;justify-content:space-between;">
        <span style="font-size:0.95rem;font-weight:600;color:var(--color-gold);">£${item.subtotal.toFixed(2)}</span>
        <button onclick="deleteCartItem(${item.id})" style="background:none;border:none;cursor:pointer;color:#e53e3e;font-size:1.1rem;" title="Remove">&times;</button>
      </div>
    </div>
  `).join('');

  cartTotalPrice.textContent = `£${cartState.total.toFixed(2)}`;
  cartSummary.style.display = 'block';
}

// Notification system
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  const bgColor = type === 'success' ? '#38a169' : type === 'error' ? '#e53e3e' : '#3182ce';
  notification.style.cssText = `position:fixed;top:6rem;right:1.5rem;z-index:9999;padding:0.75rem 1.5rem;border-radius:4px;color:#fff;font-weight:500;box-shadow:0 4px 20px rgba(0,0,0,0.2);transition:opacity 0.3s;background:${bgColor};font-family:var(--font-sans);font-size:0.9rem;`;
  notification.textContent = message;
  document.body.appendChild(notification);
  setTimeout(() => {
    notification.style.opacity = '0';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Cart panel functions
window.openCart = async function() {
  const cartPanel = document.getElementById('cartPanel');
  const cartOverlay = document.getElementById('cartOverlay');
  if (!cartPanel || !cartOverlay) return;
  cartPanel.style.right = '0';
  cartOverlay.style.display = 'block';
  renderCart();
  loadCart().then(() => renderCart());
};

window.closeCart = function() {
  const cartPanel = document.getElementById('cartPanel');
  const cartOverlay = document.getElementById('cartOverlay');
  if (!cartPanel || !cartOverlay) return;
  cartPanel.style.right = window.innerWidth <= 768 ? '-100%' : '-420px';
  cartOverlay.style.display = 'none';
};

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', () => {
  loadCart();
  
  const cartBtn = document.getElementById('cartBtn');
  const cartClose = document.getElementById('cartClose');
  const cartOverlay = document.getElementById('cartOverlay');
  
  if (cartBtn) cartBtn.addEventListener('click', openCart);
  if (cartClose) cartClose.addEventListener('click', closeCart);
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);
});
