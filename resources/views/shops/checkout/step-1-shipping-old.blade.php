<div id="step1" class="step-content">
    <h2 class="text-3xl font-bold mb-8 text-gray-900">Shipping Information</h2>
    <form id="shippingForm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">First Name *</label>
                <input type="text" id="firstName" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">Last Name *</label>
                <input type="text" id="lastName" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">Email *</label>
                <input type="email" id="email" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">Phone Number *</label>
                <div class="flex gap-2">
                    <select id="countryCode" class="w-24 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
                        <option value="+44">+44</option>
                        <option value="+1">+1</option>
                        <option value="+234">+234</option>
                    </select>
                    <input type="tel" id="phone" required class="flex-1 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
                </div>
            </div>
    <!-- ========== ADDRESS AUTOCOMPLETE FIELD ========== -->
<div class="flex flex-col md:col-span-2 relative">
    <label class="font-medium mb-2 text-gray-900 text-sm">
        Search Address *
        <span class="text-xs text-gray-500 font-normal ml-2">(Start typing to search)</span>
    </label>
    <input 
        type="text" 
        id="addressAutocomplete" 
        placeholder="Start typing your address..."
        required 
        class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors"
        autocomplete="off"
    >
    <!-- Suggestions dropdown -->
    <ul id="autocompleteResults" class="absolute z-50 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-y-auto hidden"></ul>
</div>


            <div class="flex flex-col md:col-span-2">
                <label class="font-medium mb-2 text-gray-900 text-sm">Shipping Address *</label>
                <input type="text" id="address" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">Country *</label>
                <input type="text" id="country" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">State *</label>
                <input type="text" id="state" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">City *</label>
                <input type="text" id="city" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-2 text-gray-900 text-sm">Postal Code *</label>
                <input type="text" id="zipcode" required class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors">
            </div>
            <div class="flex flex-col md:col-span-2">
                <label class="font-medium mb-2 text-gray-900 text-sm">Leave a message (Optional)</label>
                <textarea id="message" class="px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-orange-600 transition-colors resize-y min-h-[100px]"></textarea>
            </div>
        </div>
        <button type="button" class="btn-hover-effect w-full bg-orange-600 text-white font-semibold py-3 px-10 rounded mt-8 active:scale-[0.98] transition-transform" onclick="goToStep(2)">
            <span class="btn-content">Proceed to Shipping Method</span>
        </button>
    </form>
</div>

<script>
// ===================== NOMINATIM AUTOCOMPLETE =====================
const addressInput = document.getElementById('addressAutocomplete');
const resultsContainer = document.getElementById('autocompleteResults');

let debounceTimer;

// Listen for user input
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
    }, 300); // debounce 300ms
});

// Fetch suggestions from Nominatim
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

// Display suggestions in dropdown
function displaySuggestions(suggestions) {
    if (!suggestions || suggestions.length === 0) {
        resultsContainer.innerHTML = '';
        resultsContainer.classList.add('hidden');
        return;
    }

    resultsContainer.innerHTML = suggestions.map(s => {
        return `<li class="px-4 py-2 hover:bg-orange-100 cursor-pointer" data-place='${JSON.stringify(s)}'>
            ${s.display_name}
        </li>`;
    }).join('');

    resultsContainer.classList.remove('hidden');

    // Add click listeners
    resultsContainer.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', function() {
            const place = JSON.parse(this.dataset.place);
            fillAddressFields(place);
            resultsContainer.classList.add('hidden');
        });
    });
}

// Fill form fields with selected address
function fillAddressFields(place) {
    const address = place.address || {};
    const street = [address.house_number, address.road].filter(Boolean).join(' ') || place.display_name;

    document.getElementById('address').value = street;
    document.getElementById('city').value = address.city || address.town || address.village || '';
    document.getElementById('state').value = address.state || '';
    document.getElementById('country').value = address.country || '';
    document.getElementById('zipcode').value = address.postcode || '';

    // Optional: highlight auto-filled fields
    ['address', 'city', 'state', 'country', 'zipcode'].forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            field.classList.add('bg-green-50', 'border-green-500');
            setTimeout(() => field.classList.remove('bg-green-50', 'border-green-500'), 2000);
        }
    });
}

// Hide suggestions if clicked outside
document.addEventListener('click', function(e) {
    if (!addressInput.contains(e.target) && !resultsContainer.contains(e.target)) {
        resultsContainer.classList.add('hidden');
    }
});
</script>
