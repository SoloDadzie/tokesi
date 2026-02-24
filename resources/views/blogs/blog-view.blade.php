@extends('layouts.app')

@section('title', 'Blog Details')

@section('content')

<section class="bg-white">
  <!-- Header Section -->
  <div class="bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto mt-12 px-[25px] md:px-[40px] lg:px-[50px]">
      <h2 class="text-xl md:text-xl font-semi-bold text-gray-500 mb-3">Blogs & Events</h2>
      
      <!-- Title -->
      <h1 class="font-serif text-3xl md:text-5xl font-bold text-gray-900 mb-4">Why Children Should Be Trained To Read Books</h1>
      
      <!-- Breadcrumb -->
      <nav class="text-sm text-gray-600">
        <a href="#" class="hover:text-[#d67a00] transition-colors">Home</a>
        <span class="mx-2">|</span>
        <a href="#" class="hover:text-[#d67a00] transition-colors">Education</a>
        <span class="mx-2">|</span>
        <span class="text-gray-900">Why Children Should Be Trained To Read Books</span>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-[25px] lg:px-[70px] py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Left Column -->
      <div class="lg:col-span-8 lg:pr-8 lg:border-r border-gray-200">
        <!-- Short Description -->
        <p class="text-lg text-gray-600 mb-6">Discover the transformative power of reading and how early literacy shapes young minds for a lifetime of learning and imagination.</p>
        
        <!-- Blog Image -->
        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=500&fit=crop" alt="Children reading books" class="w-full h-auto rounded-[4px] mb-6">
        
        <!-- Meta Information -->
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-8">
          <span class="bg-gray-100 px-3 py-1 rounded-[4px]">Education</span>
          <span class="bg-gray-100 px-3 py-1 rounded-[4px]">Children</span>
          <span class="bg-gray-100 px-3 py-1 rounded-[4px]">Literacy</span>
          <span>•</span>
          <span>August 9, 2025</span>
          <span>•</span>
          <span>Tokesi Akinola</span>
          <span>•</span>
          <span>2 Comments</span>
        </div>
        
        <!-- Main Content -->
        <div class="prose max-w-none mb-12">
          <p class="text-gray-700 leading-relaxed mb-6">Reading is one of the most fundamental skills that children need to develop in their early years. It opens doors to knowledge, enhances imagination, and builds the foundation for academic success. When children learn to read at an early age, they develop cognitive abilities that will serve them throughout their lives.</p>
          
          <p class="text-gray-700 leading-relaxed mb-8">The benefits of reading extend far beyond academic achievement. Children who read regularly develop better communication skills, improved concentration, and enhanced creativity. They learn to express themselves more effectively and develop a deeper understanding of the world around them.</p>
          
          <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-8">Building Strong Foundations</h2>
          
          <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&h=500&fit=crop" alt="Parent reading with child" class="w-full h-auto rounded-[4px] mb-2">
          <p class="text-center text-sm text-gray-500 mb-6">A parent engaging in reading time with their child</p>
          
          <p class="text-gray-700 leading-relaxed mb-6">Early childhood is a critical period for brain development. During these formative years, children's brains are incredibly receptive to learning new skills. Introducing reading at this stage helps create neural pathways that support language development, critical thinking, and problem-solving abilities.</p>
          
          <p class="text-gray-700 leading-relaxed mb-8">Parents and educators play a crucial role in nurturing a love for reading. By making reading a daily habit and choosing age-appropriate books that capture children's interests, we can instill a lifelong passion for learning and exploration.</p>
          
          <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-8">Key Benefits of Early Reading</h2>
          
          <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=500&fit=crop" alt="Children in library" class="w-full h-auto rounded-[4px] mb-6">
          
          <p class="text-gray-700 leading-relaxed mb-4">Research has consistently shown that children who are exposed to reading from an early age perform better academically and develop stronger social skills. Here are some key benefits:</p>
          
          <ul class="list-disc pl-6 mb-8 text-gray-700 space-y-2">
            <li>Enhanced vocabulary and language skills</li>
            <li>Improved concentration and focus</li>
            <li>Better analytical thinking abilities</li>
            <li>Increased empathy and emotional intelligence</li>
            <li>Stronger academic performance across all subjects</li>
            <li>Development of imagination and creativity</li>
          </ul>
          
          <p class="text-gray-700 leading-relaxed mb-6">Creating a reading-friendly environment at home and in schools is essential. This includes having a variety of books accessible, setting aside dedicated reading time, and making the experience enjoyable rather than a chore. When children associate reading with pleasure and discovery, they are more likely to become lifelong readers.</p>
        </div>
        
        <!-- Comments Section -->
        <div class="border-t border-gray-200 pt-8 mb-8">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Comments (2)</h3>
          
          <!-- Comment 1 -->
          <div class="mb-6 pb-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full bg-gray-300 flex-shrink-0"></div>
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <h4 class="font-semibold text-gray-900">Sarah Johnson</h4>
                  <span class="text-sm text-gray-500">August 10, 2025</span>
                </div>
                <p class="text-gray-700 mb-3">This is such an important topic! I've been reading to my daughter every night since she was six months old, and I can already see the difference it's making in her language development.</p>
                <button class="text-sm text-[#d67a00] hover:underline font-medium">Reply</button>
              </div>
            </div>
          </div>
          
          <!-- Comment 2 -->
          <div class="mb-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full bg-gray-300 flex-shrink-0"></div>
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <h4 class="font-semibold text-gray-900">Michael Chen</h4>
                  <span class="text-sm text-gray-500">August 11, 2025</span>
                </div>
                <p class="text-gray-700 mb-3">Great article! As a teacher, I've witnessed firsthand how children who read regularly excel in all areas of their education. We need more awareness about this.</p>
                <button class="text-sm text-[#d67a00] hover:underline font-medium">Reply</button>
                
                <!-- Reply to Comment 2 -->
                <div class="mt-4 ml-8 flex items-start gap-4">
                  <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0"></div>
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <h4 class="font-semibold text-gray-900">Tokesi Akinola</h4>
                      <span class="text-sm text-gray-500">August 11, 2025</span>
                    </div>
                    <p class="text-gray-700">Thank you for sharing your experience, Michael! Teachers like you are making a real difference in children's lives.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Leave a Comment Form -->
        <div class="bg-gray-50 p-6 rounded-[4px]">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
          <form>
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2" for="name">Name *</label>
              <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00]" required>
            </div>
            
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2" for="email">Email (optional)</label>
              <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00]">
            </div>
            
            <div class="mb-6">
              <label class="block text-gray-700 font-medium mb-2" for="message">Message *</label>
              <textarea id="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00] resize-none" required></textarea>
            </div>
            
            <button type="submit" class="relative bg-gray-900 text-white px-8 py-3 rounded-[4px] font-medium overflow-hidden group">
              <span class="relative z-10">Comment</span>
              <span class="absolute inset-0 bg-[#d67a00] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
            </button>
          </form>
        </div>
      </div>
      
      <!-- Right Column -->
      <div class="lg:col-span-4">
        <!-- Recent Posts -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Most Popular</h3>
          
          <!-- Post 1 -->
          <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=120&h=90&fit=crop" alt="Blog post" class="w-24 h-20 object-cover rounded-[4px] flex-shrink-0">
            <div>
              <h4 class="font-semibold text-gray-900 mb-2 hover:text-[#d67a00] transition-colors cursor-pointer">Why Children Should Be Trained To Read Books</h4>
              <p class="text-sm text-gray-500">August 9, 2025</p>
            </div>
          </div>
          
          <!-- Post 2 -->
          <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=120&h=90&fit=crop" alt="Blog post" class="w-24 h-20 object-cover rounded-[4px] flex-shrink-0">
            <div>
              <h4 class="font-semibold text-gray-900 mb-2 hover:text-[#d67a00] transition-colors cursor-pointer">Why You Should Read Books</h4>
              <p class="text-sm text-gray-500">August 9, 2025</p>
            </div>
          </div>
          
          <!-- Post 3 -->
          <div class="flex gap-4 mb-6">
            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=120&h=90&fit=crop" alt="Blog post" class="w-24 h-20 object-cover rounded-[4px] flex-shrink-0">
            <div>
              <h4 class="font-semibold text-gray-900 mb-2 hover:text-[#d67a00] transition-colors cursor-pointer">The Power of Early Literacy</h4>
              <p class="text-sm text-gray-500">August 8, 2025</p>
            </div>
          </div>
        </div>
        
        <!-- Author Box -->
        <div class="bg-black text-white p-6 mb-8">
          <h3 class="text-xl font-bold mb-3">Tokesi Akinola</h3>
          <p class="text-gray-300 mb-4 text-sm leading-relaxed">Award-winning children's book author passionate about fostering literacy and imagination in young readers. Dedicated to creating stories that inspire and educate.</p>
          <button class="relative bg-white text-black px-6 py-2 rounded-[4px] font-medium overflow-hidden group w-full">
            <span class="relative z-10">Read More</span>
            <span class="absolute inset-0 bg-[#d67a00] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
          </button>
        </div>
        
        <!-- Post Categories -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Post Category</h3>
          <div class="space-y-0">
            <div class="flex justify-between items-center py-3 border-b border-gray-200 hover:text-[#d67a00] transition-colors cursor-pointer">
              <span>Children Books</span>
              <span class="text-gray-500">(4)</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200 hover:text-[#d67a00] transition-colors cursor-pointer">
              <span>Education</span>
              <span class="text-gray-500">(7)</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200 hover:text-[#d67a00] transition-colors cursor-pointer">
              <span>Parenting Tips</span>
              <span class="text-gray-500">(5)</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-200 hover:text-[#d67a00] transition-colors cursor-pointer">
              <span>Events</span>
              <span class="text-gray-500">(3)</span>
            </div>
          </div>
        </div>
        
        <!-- Post Tags -->
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Post Tags</h3>
          <div class="flex flex-wrap gap-2">
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Reading</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Children</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Education</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Literacy</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Books</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Parenting</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Learning</a>
            <a href="#" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">Development</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection