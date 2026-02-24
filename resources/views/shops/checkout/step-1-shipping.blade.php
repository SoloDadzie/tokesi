<div id="step1" class="step-content active">
    <h2 class="section-title">Shipping Information</h2>
    <form id="shippingForm" class="checkout-form">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">First Name *</label>
                <input type="text" id="firstName" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Last Name *</label>
                <input type="text" id="lastName" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" id="email" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <div class="phone-input-group">
                    <select id="countryCode" class="form-select phone-code">
                        <option value="+44">+44</option>
                        <option value="+1">+1</option>
                        <option value="+234">+234</option>
                    </select>
                    <input type="tel" id="phone" required class="form-input phone-number">
                </div>
            </div>
            
            <div class="form-group form-group-full">
                <label class="form-label">
                    Search Address *
                    <span class="form-label-hint">(Start typing to search)</span>
                </label>
                <input 
                    type="text" 
                    id="addressAutocomplete" 
                    placeholder="Start typing your address..."
                    required 
                    class="form-input"
                    autocomplete="off"
                >
                <ul id="autocompleteResults" class="autocomplete-results hidden"></ul>
            </div>

            <div class="form-group form-group-full">
                <label class="form-label">Shipping Address *</label>
                <input type="text" id="address" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Country *</label>
                <input type="text" id="country" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">State *</label>
                <input type="text" id="state" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">City *</label>
                <input type="text" id="city" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Postal Code *</label>
                <input type="text" id="zipcode" required class="form-input">
            </div>
            <div class="form-group form-group-full">
                <label class="form-label">Leave a message (Optional)</label>
                <textarea id="message" class="form-textarea"></textarea>
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-full" onclick="goToStep(2)">
            Proceed to Shipping Method
        </button>
    </form>
</div>

<script>
// Address Autocomplete with Nominatim
const addressInput = document.getElementById('addressAutocomplete');
const resultsContainer = document.getElementById('autocompleteResults');
let debounceTimer;

addressInput.addEventListener('input', function() {
    const query = this.value.trim();
    if (query.length < 3) {
        resultsContainer.innerHTML = '';
        resultsContainer.classList.add('hidden');
        return;
    }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchSuggestions(query);
    }, 300);
});

async function fetchSuggestions(query) {
    try {
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5`;
        const response = await fetch(url, { headers: { 'Accept-Language': 'en' } });
        const data = await response.json();
        displaySuggestions(data);
    } catch (error) {
        console.error('Nominatim error:', error);
    }
}

function displaySuggestions(suggestions) {
    if (!suggestions || suggestions.length === 0) {
        resultsContainer.innerHTML = '';
        resultsContainer.classList.add('hidden');
        return;
    }

    resultsContainer.innerHTML = suggestions.map(s => {
        return `<li class="autocomplete-item" data-place='${JSON.stringify(s)}'>
            ${s.display_name}
        </li>`;
    }).join('');

    resultsContainer.classList.remove('hidden');

    resultsContainer.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', function() {
            const place = JSON.parse(this.dataset.place);
            fillAddressFields(place);
            resultsContainer.classList.add('hidden');
        });
    });
}

function fillAddressFields(place) {
    const address = place.address || {};
    const street = [address.house_number, address.road].filter(Boolean).join(' ') || place.display_name;

    document.getElementById('address').value = street;
    document.getElementById('city').value = address.city || address.town || address.village || '';
    document.getElementById('state').value = address.state || '';
    document.getElementById('country').value = address.country || '';
    document.getElementById('zipcode').value = address.postcode || '';

    ['address', 'city', 'state', 'country', 'zipcode'].forEach(id => {
        const field = document.getElementById(id);
        if (field && field.value) {
            field.classList.add('field-autofilled');
            setTimeout(() => field.classList.remove('field-autofilled'), 2000);
        }
    });
}

document.addEventListener('click', function(e) {
    if (!addressInput.contains(e.target) && !resultsContainer.contains(e.target)) {
        resultsContainer.classList.add('hidden');
    }
});
</script>
