<!-- ======= DESCRIPTION & REVIEWS SECTION ======= -->
<section class="p-8 md:p-10 bg-white">
  <div class="max-w-[1100px] mx-auto">
    <!-- Tabs header -->
    <div class="flex justify-center mb-4 border-b border-gray-200">
      <div class="relative inline-flex gap-8 items-center">
        <button class="tab-btn active bg-transparent border-none py-3 px-0 font-semibold cursor-pointer text-[0.95rem] text-gray-600 relative transition-colors duration-200 focus:outline-none" data-tab="desc">Description</button>
        <button class="tab-btn bg-transparent border-none py-3 px-0 font-semibold cursor-pointer text-[0.95rem] text-gray-600 relative transition-colors duration-200 focus:outline-none" data-tab="rev">Review</button>
        <div class="tab-indicator absolute -bottom-px left-0 h-[3px] w-[120px] bg-[#d67a00] origin-left transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"></div>
      </div>
    </div>

    <!-- Tab content -->
    <div class="mt-8">
      <!-- Description Tab -->
      <div class="tab-panel animate-fadeIn" id="desc">
        <p class="leading-[1.7] text-gray-600">
          <strong class="text-[#1a1a1a] text-[1.05rem]">About this product:</strong><br><br>
          @if($product->long_description)
            {!! $product->long_description !!}
          @else
            {!! $product->short_description ?? 'No description available.' !!}
          @endif
        </p>
      </div>

      <!-- Reviews Tab -->
      <div class="tab-panel hidden" id="rev">
        <!-- Rating summary -->
        <div class="flex gap-10 items-start flex-wrap p-6 bg-[#fafafa] border border-gray-200">
          <div class="text-center min-w-[140px]">
            <div class="text-5xl font-extrabold text-[#1a1a1a] leading-none" id="avgRating">0.0</div>
            <div class="my-2.5" id="avgStars"></div>
            <div class="text-gray-600 text-sm mt-2" id="totalReviews">(<span>0</span> Reviews)</div>
          </div>

          <div class="flex-1 min-w-[280px]">
            <div class="flex items-center gap-3 mb-2.5 text-sm">
              <span class="w-[30px] text-center text-gray-700 font-semibold">5</span>
              <div class="flex-1 h-2 bg-gray-200 overflow-hidden">
                <div class="bar-fill h-full w-0 bg-[#d67a00] transition-all duration-500 ease-in-out" data-fill="60%"></div>
              </div>
              <span class="percent w-[45px] text-right text-gray-600 text-[0.85rem] font-medium">60%</span>
            </div>
            <div class="flex items-center gap-3 mb-2.5 text-sm">
              <span class="w-[30px] text-center text-gray-700 font-semibold">4.5</span>
              <div class="flex-1 h-2 bg-gray-200 overflow-hidden">
                <div class="bar-fill h-full w-0 bg-[#d67a00] transition-all duration-500 ease-in-out" data-fill="20%"></div>
              </div>
              <span class="percent w-[45px] text-right text-gray-600 text-[0.85rem] font-medium">20%</span>
            </div>
            <div class="flex items-center gap-3 mb-2.5 text-sm">
              <span class="w-[30px] text-center text-gray-700 font-semibold">4</span>
              <div class="flex-1 h-2 bg-gray-200 overflow-hidden">
                <div class="bar-fill h-full w-0 bg-[#d67a00] transition-all duration-500 ease-in-out" data-fill="12%"></div>
              </div>
              <span class="percent w-[45px] text-right text-gray-600 text-[0.85rem] font-medium">12%</span>
            </div>
            <div class="flex items-center gap-3 mb-2.5 text-sm">
              <span class="w-[30px] text-center text-gray-700 font-semibold">3</span>
              <div class="flex-1 h-2 bg-gray-200 overflow-hidden">
                <div class="bar-fill h-full w-0 bg-[#d67a00] transition-all duration-500 ease-in-out" data-fill="6%"></div>
              </div>
              <span class="percent w-[45px] text-right text-gray-600 text-[0.85rem] font-medium">6%</span>
            </div>
          </div>
        </div>

        <hr class="border-none border-t border-gray-200 my-6">

        <!-- Reviews list -->
        <div class="mt-5">
          <ul class="list-none p-0 m-0 flex flex-col gap-5" id="reviewsList"></ul>
        </div>

        <!-- Buttons and form -->
        <div class="flex gap-3 items-center mt-6 flex-wrap">
          <button id="seeMoreBtn2" class="see-more bg-white border border-[#d1d1d1] py-2.5 px-6 rounded cursor-pointer font-semibold text-sm text-gray-800 transition-all duration-200 hover:bg-gray-100 hover:border-gray-600">See More</button>
          <button id="toggleLeave" class="leave-btn bg-[#1a1a1a] text-white border-none py-3 px-6 rounded cursor-pointer font-semibold text-sm transition-colors duration-200 hover:bg-gray-800">Leave a review</button>
        </div>

        <!-- Review form (hidden by default) -->
        <div class="leave-form-container collapsed mt-6 border border-[#d1d1d1] bg-white overflow-hidden transition-all duration-300" id="leaveFormContainer">
          <div class="leave-form p-6">
            <div class="mb-5">
              <label class="block text-sm mb-2 text-gray-800 font-semibold">Your rating</label>
              <div class="flex gap-1.5" id="selectStars">
                <button class="star-btn bg-transparent border-none cursor-pointer p-1 inline-flex items-center justify-center relative transition-transform duration-150 hover:scale-110" data-value="1" type="button">
                  <svg viewBox="0 0 24 24" class="star-icon w-[26px] h-[26px] fill-none stroke-[#1a1a1a] stroke-[1.5] transition-all duration-150">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <span class="star-tooltip absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 bg-[#1a1a1a] text-white py-1.5 px-3 rounded text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200 font-medium">Not satisfied</span>
                </button>
                <button class="star-btn bg-transparent border-none cursor-pointer p-1 inline-flex items-center justify-center relative transition-transform duration-150 hover:scale-110" data-value="2" type="button">
                  <svg viewBox="0 0 24 24" class="star-icon w-[26px] h-[26px] fill-none stroke-[#1a1a1a] stroke-[1.5] transition-all duration-150">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <span class="star-tooltip absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 bg-[#1a1a1a] text-white py-1.5 px-3 rounded text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200 font-medium">Could be better</span>
                </button>
                <button class="star-btn bg-transparent border-none cursor-pointer p-1 inline-flex items-center justify-center relative transition-transform duration-150 hover:scale-110" data-value="3" type="button">
                  <svg viewBox="0 0 24 24" class="star-icon w-[26px] h-[26px] fill-none stroke-[#1a1a1a] stroke-[1.5] transition-all duration-150">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <span class="star-tooltip absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 bg-[#1a1a1a] text-white py-1.5 px-3 rounded text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200 font-medium">Good</span>
                </button>
                <button class="star-btn bg-transparent border-none cursor-pointer p-1 inline-flex items-center justify-center relative transition-transform duration-150 hover:scale-110" data-value="4" type="button">
                  <svg viewBox="0 0 24 24" class="star-icon w-[26px] h-[26px] fill-none stroke-[#1a1a1a] stroke-[1.5] transition-all duration-150">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <span class="star-tooltip absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 bg-[#1a1a1a] text-white py-1.5 px-3 rounded text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200 font-medium">Very good</span>
                </button>
                <button class="star-btn bg-transparent border-none cursor-pointer p-1 inline-flex items-center justify-center relative transition-transform duration-150 hover:scale-110" data-value="5" type="button">
                  <svg viewBox="0 0 24 24" class="star-icon w-[26px] h-[26px] fill-none stroke-[#1a1a1a] stroke-[1.5] transition-all duration-150">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <span class="star-tooltip absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 bg-[#1a1a1a] text-white py-1.5 px-3 rounded text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-200 font-medium">Excellent</span>
                </button>
              </div>
            </div>

            <div class="mb-5 flex gap-4 flex-col md:flex-row">
              <div class="flex-1">
                <label class="block text-sm mb-2 text-gray-800 font-semibold">First name</label>
                <input type="text" id="firstName" placeholder="First name" class="w-full py-3 px-3.5 rounded border border-[#d1d1d1] text-sm transition-colors duration-200 focus:outline-none focus:border-[#d67a00]">
              </div>
              <div class="flex-1">
                <label class="block text-sm mb-2 text-gray-800 font-semibold">Last name</label>
                <input type="text" id="lastName" placeholder="Last name" class="w-full py-3 px-3.5 rounded border border-[#d1d1d1] text-sm transition-colors duration-200 focus:outline-none focus:border-[#d67a00]">
              </div>
            </div>

            <div class="mb-5">
              <label class="block text-sm mb-2 text-gray-800 font-semibold">Your review</label>
              <textarea id="reviewText" rows="4" placeholder="Write your review..." class="w-full py-3 px-3.5 rounded border border-[#d1d1d1] text-sm resize-y min-h-[100px] transition-colors duration-200 focus:outline-none focus:border-[#d67a00]"></textarea>
            </div>

            <div class="mb-5 flex justify-end">
              <button id="sendReview" class="btn-send bg-[#2b3043] text-white border-none py-3 px-8 rounded cursor-pointer font-semibold text-sm relative overflow-hidden transition-transform duration-150 hover:-translate-y-px">
                <span class="relative z-10">Send</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  const productId = {{ $product->id }};
  let currentReviews = [];
  const PER_PAGE = 5;
  let pageIndex = 0;

  // DOM refs
  const tabBtns = document.querySelectorAll('.tab-btn');
  const indicator = document.querySelector('.tab-indicator');
  const panels = document.querySelectorAll('.tab-panel');
  const reviewsList = document.getElementById('reviewsList');
  const seeMoreBtn2 = document.getElementById('seeMoreBtn2');
  const avgRatingEl = document.getElementById('avgRating');
  const avgStarsEl = document.getElementById('avgStars');
  const totalReviewsEl = document.querySelector('#totalReviews span');
  const toggleLeave = document.getElementById('toggleLeave');
  const leaveFormContainer = document.getElementById('leaveFormContainer');
  const selectStars = document.getElementById('selectStars');
  const starButtons = selectStars.querySelectorAll('.star-btn');
  const firstName = document.getElementById('firstName');
  const lastName = document.getElementById('lastName');
  const reviewText = document.getElementById('reviewText');
  const sendReview = document.getElementById('sendReview');

  // Load reviews from database
  async function loadReviews() {
    try {
      const response = await fetch(`/api/products/${productId}/reviews`);
      const data = await response.json();
      
      if (data.success) {
        currentReviews = data.reviews;
        updateStats(data.average_rating, data.total_reviews, data.rating_breakdown);
        renderReviews();
      }
    } catch (error) {
      console.error('Failed to load reviews:', error);
    }
  }

  // Tab indicator positioning
  function updateIndicator() {
    const activeBtn = document.querySelector('.tab-btn.active');
    if(!activeBtn) return;
    const left = activeBtn.offsetLeft;
    indicator.style.transform = `translateX(${left}px)`;
    indicator.style.width = `${activeBtn.offsetWidth}px`;
  }

  // Switch tab
  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      tabBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      panels.forEach(p => p.classList.add('hidden'));
      const panel = document.getElementById(btn.dataset.tab);
      if(panel) panel.classList.remove('hidden');
      updateIndicator();
    });
  });

  window.addEventListener('resize', updateIndicator);
  window.addEventListener('load', updateIndicator);

  // Render average stars
  function renderAvgStars(avg) {
    avgStarsEl.innerHTML = '';
    const full = Math.floor(avg);
    const half = (avg - full) >= 0.5;
    for(let i=1; i<=5; i++){
      const svg = document.createElementNS('http://www.w3.org/2000/svg','svg');
      svg.setAttribute('viewBox','0 0 24 24');
      svg.setAttribute('width','16');
      svg.setAttribute('height','16');
      const path = document.createElementNS('http://www.w3.org/2000/svg','path');
      path.setAttribute('d','M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z');
      if(i <= full) path.setAttribute('fill','#d67a00');
      else if(i === full+1 && half) path.setAttribute('fill','#d67a00');
      else path.setAttribute('fill','#e6e6e6');
      svg.appendChild(path);
      avgStarsEl.appendChild(svg);
    }
  }

  // Update stats
  function updateStats(avgRating, totalReviews, breakdown) {
    avgRatingEl.textContent = avgRating ? avgRating.toFixed(1) : '0.0';
    totalReviewsEl.textContent = totalReviews || 0;
    renderAvgStars(avgRating || 0);

    // Update rating bars
    const bars = document.querySelectorAll('.bar-fill');
    const percents = document.querySelectorAll('.percent');
    
    if (breakdown) {
      const fills = [
        breakdown['5'] + '%',
        breakdown['4.5'] + '%',
        breakdown['4'] + '%',
        breakdown['3'] + '%'
      ];
      
      bars.forEach((el, i) => {
        el.style.width = fills[i];
      });
      
      percents.forEach((el, i) => {
        el.textContent = fills[i];
      });
    }
  }

  // Create review item
  function createReviewItem(r) {
    const li = document.createElement('li');
    li.className = 'flex gap-4 items-start p-5 bg-[#fafafa] border border-gray-200 rounded';
    
    const initial = r.name.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase();
    const avatar = document.createElement('div');
    avatar.className = 'w-11 h-11 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white text-[0.85rem]';
    avatar.textContent = initial;
    const colors = ['#b45f06','#0b76ff','#6a2a6c','#0a8a5f','#ff7a59'];
    avatar.style.background = colors[(r.id || 0) % colors.length];

    const body = document.createElement('div');
    body.className = 'flex-1';
    
    const head = document.createElement('div');
    head.className = 'flex items-center gap-3 mb-2 flex-wrap';
    
    const name = document.createElement('div');
    name.className = 'font-bold text-[#1a1a1a] text-[0.95rem]';
    name.textContent = r.name;
    
    const meta = document.createElement('div');
    meta.className = 'ml-auto flex items-center gap-2.5 text-gray-500 text-[0.85rem]';
    
    const starsWrap = document.createElement('div');
    starsWrap.className = 'flex gap-1 items-center';
    const full = Math.floor(r.rating);
    const half = r.rating - full >= 0.5;
    for(let i=1; i<=5; i++){
      const s = document.createElementNS('http://www.w3.org/2000/svg','svg');
      s.setAttribute('viewBox','0 0 24 24');
      s.innerHTML = '<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>';
      s.style.width='14px'; s.style.height='14px';
      if(i <= full || (i === full+1 && half)) s.querySelector('path').setAttribute('fill','#d67a00');
      else s.querySelector('path').setAttribute('fill','#e6e6e6');
      starsWrap.appendChild(s);
    }
    
    const date = document.createElement('div');
    date.textContent = r.date;
    meta.appendChild(starsWrap);
    meta.appendChild(date);

    head.appendChild(name);
    head.appendChild(meta);

    const text = document.createElement('div');
    text.className = 'text-gray-600 leading-relaxed mt-2';
    text.textContent = r.text;

    body.appendChild(head);
    body.appendChild(text);

    li.appendChild(avatar);
    li.appendChild(body);
    return li;
  }

  // Render reviews
  function renderReviews() {
    reviewsList.innerHTML = '';
    
    if (currentReviews.length === 0) {
      reviewsList.innerHTML = '<li class="text-center py-10 text-gray-500">No reviews yet. Be the first to review this product!</li>';
      seeMoreBtn2.style.display = 'none';
      return;
    }
    
    const start = pageIndex * PER_PAGE;
    const pageItems = currentReviews.slice(start, start + PER_PAGE);
    pageItems.forEach(r => reviewsList.appendChild(createReviewItem(r)));
    
    const showMore = (pageIndex+1)*PER_PAGE < currentReviews.length;
    seeMoreBtn2.style.display = showMore ? 'inline-block' : 'none';
  }

  seeMoreBtn2.addEventListener('click', () => { 
    pageIndex++; 
    renderReviews(); 
  });

  // Toggle review form
  toggleLeave.addEventListener('click', () => {
    const isCollapsed = leaveFormContainer.classList.contains('collapsed');
    if(isCollapsed) {
      leaveFormContainer.classList.remove('collapsed');
      toggleLeave.textContent = 'Hide Box';
      setTimeout(() => leaveFormContainer.scrollIntoView({behavior:'smooth', block:'center'}), 100);
    } else {
      leaveFormContainer.classList.add('collapsed');
      toggleLeave.textContent = 'Leave a review';
    }
  });

  // Star selection
  let chosenRating = 0;
  starButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      chosenRating = Number(btn.dataset.value);
      starButtons.forEach(s => {
        if(Number(s.dataset.value) <= chosenRating) s.classList.add('filled');
        else s.classList.remove('filled');
      });
    });
  });

  // Send review with spinner
  sendReview.addEventListener('click', async (e) => {
    e.preventDefault();
    const f = firstName.value.trim();
    const l = lastName.value.trim();
    const txt = reviewText.value.trim();
    
    if(!chosenRating) { 
      alert('Please select a rating'); 
      return; 
    }
    if(!f || !txt) { 
      alert('Please provide at least your first name and review text'); 
      return; 
    }

    // Show spinner
    const originalText = sendReview.innerHTML;
    sendReview.disabled = true;
    sendReview.innerHTML = `
      <svg class="animate-spin h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span class="ml-2">Sending...</span>
    `;

    try {
      const response = await fetch('/api/reviews', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          product_id: productId,
          first_name: f,
          last_name: l,
          rating: chosenRating,
          review_text: txt,
        }),
      });

      const data = await response.json();

      if (data.success) {
        // Show success message
        alert(data.message);
        
        // Reset form
        chosenRating = 0;
        starButtons.forEach(s => s.classList.remove('filled'));
        firstName.value = '';
        lastName.value = '';
        reviewText.value = '';
        
        // Hide form
        leaveFormContainer.classList.add('collapsed');
        toggleLeave.textContent = 'Leave a review';
        
        // Switch to review tab
        document.querySelector('.tab-btn[data-tab="rev"]').click();
      } else {
        alert('Failed to submit review. Please try again.');
      }
    } catch (error) {
      console.error('Review submission error:', error);
      alert('An error occurred. Please try again.');
    } finally {
      // Restore button
      sendReview.disabled = false;
      sendReview.innerHTML = originalText;
    }
  });

  // Initial load
  updateIndicator();
  setTimeout(updateIndicator, 200);
  loadReviews();
})();
</script>