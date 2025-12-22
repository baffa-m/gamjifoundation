<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

// --- State Management ---
const isDark = ref(false);
const mobileMenuOpen = ref(false);
const currentSlide = ref(0);
const scrollY = ref(0);
let slideTimer = null;

// --- Data ---
const slides = [
  {
    image: 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80',
    title: 'Excellence in Education',
    subtitle: 'Empowering the next generation of Nigerian leaders through scholarships and mentorship.',
    cta: 'Apply for Awards'
  },
  {
    image: 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=1920&q=80',
    title: 'Unlock Your Potential',
    subtitle: 'Connecting over 10,000 students with verified sponsors and government grants.',
    cta: 'View Opportunities'
  },
  {
    image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1920&q=80',
    title: 'The Future is Yours',
    subtitle: 'Join a community dedicated to academic success and professional growth.',
    cta: 'Join Community'
  }
];

const features = [
  { 
    title: 'Scholarships', 
    desc: 'Full financial support for JAMB, WAEC, and University tuition.', 
    icon: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z' 
  },
  { 
    title: 'Live Tracking', 
    desc: 'Monitor your application status in real-time with our dashboard.', 
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' 
  },
  { 
    title: 'E-Learning', 
    desc: 'Access curated study materials and past questions for success.', 
    icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' 
  },
  { 
    title: 'Mentorship', 
    desc: 'Connect with industry professionals and academic advisors.', 
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' 
  }
];

const awards = [
  { title: "JAMB Excellence '26", sponsor: "Federal Ministry", amount: "₦500,000", deadline: "Mar 31", type: "Merit Based" },
  { title: "STEM Innovation", sponsor: "Tech Futures Fdn", amount: "₦750,000", deadline: "Apr 20", type: "Grant" },
  { title: "Arts & Culture", sponsor: "Heritage Alliance", amount: "₦300,000", deadline: "Feb 15", type: "Competition" }
];

const stats = [
  { value: '500+', label: 'Awards Given' },
  { value: '12K', label: 'Active Students' },
  { value: '₦45M', label: 'Funds Disbursed' },
];

// --- Logic ---
const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

const handleScroll = () => {
  scrollY.value = window.scrollY;
};

// --- Lifecycle ---
onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  slideTimer = setInterval(nextSlide, 6000);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  if (slideTimer) clearInterval(slideTimer);
});

// --- Computed Classes ---
const navClasses = computed(() => {
  const base = 'fixed w-full z-50 transition-all duration-300 border-b';
  if (scrollY.value > 10) {
    return isDark.value 
      ? `${base} bg-slate-900/90 backdrop-blur-md border-slate-800 shadow-lg` 
      : `${base} bg-white/90 backdrop-blur-md border-slate-200 shadow-sm`;
  }
  return `${base} bg-transparent border-transparent`;
});
</script>

<template>
  <div :class="['min-h-screen transition-colors duration-500 font-sans selection:bg-brand-500 selection:text-white', isDark ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-800']">
    
    <!-- NAVIGATION -->
    <nav :class="navClasses">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          
          <!-- Logo -->
          <div class="flex items-center space-x-3 group cursor-pointer">
            <div class="relative w-10 h-10 flex items-center justify-center bg-gradient-to-br from-brand-600 to-brand-800 rounded-xl shadow-lg group-hover:shadow-brand-500/30 transition-all duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="flex flex-col">
              <span :class="['text-xl font-bold tracking-tight', isDark ? 'text-white' : 'text-slate-900']">GAMJI</span>
              <span class="text-[10px] uppercase tracking-wider font-semibold text-brand-600">Foundation</span>
            </div>
          </div>

          <!-- Desktop Links -->
          <div class="hidden md:flex items-center space-x-8">
            <a v-for="item in ['Features', 'Awards', 'Events', 'About']" :key="item" :href="`#${item.toLowerCase()}`" 
               :class="['text-sm font-medium transition-colors hover:text-brand-500', isDark ? 'text-slate-300' : 'text-slate-600']">
               {{ item }}
            </a>
          </div>

          <!-- Actions -->
          <div class="hidden md:flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button @click="isDark = !isDark" :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-yellow-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']">
              <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            
            <button :class="['px-5 py-2 rounded-lg text-sm font-semibold transition-all', isDark ? 'text-slate-200 hover:bg-slate-800' : 'text-slate-700 hover:bg-slate-100']">
              Log In
            </button>
            <button class="px-5 py-2 bg-brand-600 text-white rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/20 hover:bg-brand-700 hover:shadow-brand-600/40 transition-all transform hover:-translate-y-0.5">
              Get Started
            </button>
          </div>

          <!-- Mobile Toggle -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"></path></svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform -translate-y-2 opacity-0" enter-to-class="transform translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="transform translate-y-0 opacity-100" leave-to-class="transform -translate-y-2 opacity-0">
        <div v-if="mobileMenuOpen" :class="['md:hidden border-t px-4 py-6 space-y-4 shadow-xl', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-100']">
          <a v-for="item in ['Features', 'Awards', 'Events', 'About']" :key="item" href="#" class="block font-medium text-lg opacity-80">
            {{ item }}
          </a>
          <div class="h-px bg-slate-200 my-4"></div>
          <button class="w-full py-3 rounded-lg font-bold bg-brand-600 text-white">Get Started</button>
        </div>
      </transition>
    </nav>

    <!-- CINEMATIC HERO SLIDER -->
    <header class="relative h-[110vh] min-h-[600px] overflow-hidden">
      <!-- Slides -->
      <div v-for="(slide, idx) in slides" :key="idx" 
           :class="['absolute inset-0 transition-opacity duration-1000 ease-in-out', idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0']">
        <!-- Image with Ken Burns Effect -->
        <div class="absolute inset-0 bg-cover bg-center transform scale-105 transition-transform duration-[10000ms] ease-linear" 
             :class="idx === currentSlide ? 'scale-110' : 'scale-100'"
             :style="`background-image: url(${slide.image})`">
        </div>
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/60 to-transparent"></div>
        
        <!-- Content -->
        <div class="relative h-full container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
          <div class="max-w-3xl space-y-8 pl-4 border-l-4 border-brand-500">
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight tracking-tight drop-shadow-sm">
              {{ slide.title }}
            </h1>
            <p class="text-xl md:text-2xl text-slate-200 font-light max-w-xl leading-relaxed">
              {{ slide.subtitle }}
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
              <button class="px-8 py-4 bg-brand-600 text-white rounded-lg font-semibold hover:bg-brand-700 transition-all shadow-[0_0_20px_rgba(74,93,35,0.4)] hover:shadow-[0_0_30px_rgba(74,93,35,0.6)] flex items-center gap-2">
                {{ slide.cta }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
              </button>
              <button class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white border border-white/20 rounded-lg font-semibold hover:bg-white/20 transition-all">
                Partner With Us
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Controls -->
      <div class="absolute bottom-10 right-10 z-20 flex gap-2">
         <button @click="prevSlide" class="p-3 rounded-full border border-white/20 text-white hover:bg-white/20 backdrop-blur-md transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
         <button @click="nextSlide" class="p-3 rounded-full border border-white/20 text-white hover:bg-white/20 backdrop-blur-md transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
      </div>
      
      <!-- Stats Floating Bar -->
      <div class="absolute -bottom-1 w-full z-30">
        <div class="hidden md:grid grid-cols-3 max-w-5xl mx-auto divide-x divide-slate-700 bg-slate-900 border-t border-x border-slate-700 rounded-t-2xl shadow-2xl">
          <div v-for="stat in stats" :key="stat.label" class="p-6 text-center group cursor-default hover:bg-slate-800 transition-colors rounded-t-2xl">
            <div class="text-3xl font-bold text-white mb-1 group-hover:text-brand-400 transition-colors">{{ stat.value }}</div>
            <div class="text-sm font-medium text-slate-400 uppercase tracking-widest">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="relative z-10 pb-20">

      <!-- FEATURES SECTION -->
      <section id="features" :class="['py-24', isDark ? 'bg-slate-900' : 'bg-white']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Why Choose Gamji</span>
            <h2 :class="['text-3xl md:text-4xl font-bold mt-2 mb-4', isDark ? 'text-white' : 'text-slate-900']">Empowering Academic Success</h2>
            <p :class="['text-lg', isDark ? 'text-slate-400' : 'text-slate-600']">We provide a comprehensive ecosystem designed to support students from application to graduation.</p>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="(f, i) in features" :key="i" 
                 :class="['group p-8 rounded-2xl border transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden', 
                          isDark ? 'bg-slate-800 border-slate-700 hover:border-brand-500/30' : 'bg-white border-slate-200 hover:border-brand-200']">
              <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/5 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-brand-500/10 transition-colors"></div>
              <div class="w-14 h-14 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="f.icon"></path></svg>
              </div>
              <h3 :class="['text-xl font-bold mb-3', isDark ? 'text-white' : 'text-slate-900']">{{ f.title }}</h3>
              <p :class="['leading-relaxed text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">{{ f.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- AWARDS TICKETS SECTION -->
      <section id="awards" :class="['py-24', isDark ? 'bg-slate-950' : 'bg-slate-50']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-end mb-12">
            <div>
               <h2 :class="['text-3xl font-bold', isDark ? 'text-white' : 'text-slate-900']">Available Grants</h2>
               <p class="text-slate-500 mt-2">Opportunities closing soon</p>
            </div>
            <a href="#" class="hidden md:flex items-center gap-2 text-brand-600 font-semibold hover:text-brand-700">
              View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
          </div>

          <div class="grid md:grid-cols-3 gap-6">
            <div v-for="award in awards" :key="award.title" 
                 :class="['relative rounded-2xl overflow-hidden group cursor-pointer border', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200 shadow-sm']">
              
              <!-- Top Decoration -->
              <div class="h-2 w-full bg-gradient-to-r from-brand-600 to-brand-400"></div>
              
              <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                  <span class="px-3 py-1 text-xs font-bold rounded-full bg-brand-100 text-brand-800">{{ award.type }}</span>
                  <span :class="['text-xs font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Ends {{ award.deadline }}</span>
                </div>
                
                <h3 :class="['text-xl font-bold mb-1 group-hover:text-brand-600 transition-colors', isDark ? 'text-white' : 'text-slate-900']">{{ award.title }}</h3>
                <p class="text-sm text-slate-500 mb-6">Sponsored by {{ award.sponsor }}</p>
                
                <div :class="['flex items-center justify-between pt-4 border-t', isDark ? 'border-slate-700' : 'border-slate-100']">
                  <div>
                    <span class="block text-xs text-slate-500">Value</span>
                    <span :class="['font-bold text-lg', isDark ? 'text-slate-200' : 'text-slate-800']">{{ award.amount }}</span>
                  </div>
                  <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA BANNER -->
      <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto rounded-3xl overflow-hidden relative bg-brand-900 shadow-2xl">
          <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
          <div class="absolute top-0 right-0 w-96 h-96 bg-brand-500 rounded-full blur-[100px] opacity-30 pointer-events-none"></div>
          
          <div class="relative z-10 flex flex-col md:flex-row items-center justify-between p-12 gap-8">
            <div class="text-left max-w-xl">
              <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Start Your Excellence Journey</h2>
              <p class="text-brand-100 text-lg">Create a profile today to get matched with scholarships, mentors, and training programs tailored to your skills.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 shrink-0">
               <button class="px-8 py-4 bg-white text-brand-900 rounded-xl font-bold hover:bg-brand-50 transition shadow-lg">Register Now</button>
               <button class="px-8 py-4 bg-brand-800 text-white border border-brand-700 rounded-xl font-bold hover:bg-brand-700 transition">Contact Support</button>
            </div>
          </div>
        </div>
      </section>
      
    </main>

    <!-- FOOTER -->
    <footer :class="['pt-20 pb-10 border-t', isDark ? 'bg-slate-900 border-slate-800' : 'bg-slate-900 border-slate-800 text-slate-300']">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 mb-16">
          <div class="col-span-2 md:col-span-1">
             <div class="flex items-center space-x-2 mb-6">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center text-white font-bold">G</div>
                <span class="text-2xl font-bold text-white">GAMJI</span>
             </div>
             <p class="text-slate-400 text-sm leading-relaxed">
               Building bridges to a brighter future for Nigerian students through accessible education and financial aid.
             </p>
          </div>
          
          <div>
            <h4 class="text-white font-bold mb-6">Platform</h4>
            <ul class="space-y-3 text-sm text-slate-400">
              <li><a href="#" class="hover:text-brand-400 transition">Scholarship Portal</a></li>
              <li><a href="#" class="hover:text-brand-400 transition">Mentorships</a></li>
              <li><a href="#" class="hover:text-brand-400 transition">Success Stories</a></li>
            </ul>
          </div>
          
          <div>
            <h4 class="text-white font-bold mb-6">Resources</h4>
            <ul class="space-y-3 text-sm text-slate-400">
              <li><a href="#" class="hover:text-brand-400 transition">Exam Prep</a></li>
              <li><a href="#" class="hover:text-brand-400 transition">Career Guide</a></li>
              <li><a href="#" class="hover:text-brand-400 transition">Blog</a></li>
            </ul>
          </div>
          
          <div>
            <h4 class="text-white font-bold mb-6">Contact</h4>
            <ul class="space-y-3 text-sm text-slate-400">
              <li>Abuja, Nigeria</li>
              <li>support@gamji.org</li>
              <li>+234 800 123 4567</li>
            </ul>
          </div>
        </div>
        
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
          <p>&copy; 2025 GAMJI Foundation. All rights reserved.</p>
          <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#" class="hover:text-white transition">Privacy Policy</a>
            <a href="#" class="hover:text-white transition">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>

  </div>
</template>

<style>
/* Defining the custom Brand Color - Olive/Forest Green */
:root {
  --brand-50: #f4f7ed;
  --brand-100: #e3ebd3;
  --brand-200: #c6d9a3;
  --brand-300: #a3c26d;
  --brand-400: #82aa41;
  --brand-500: #658c2b; /* Original roughly matches here */
  --brand-600: #4a5d23; /* Your Original Brand Color */
  --brand-700: #3d4d1c;
  --brand-800: #323e19;
  --brand-900: #2a3317;
}

/* Custom Tailwind classes for the brand color */
.bg-brand-50 { background-color: var(--brand-50); }
.bg-brand-100 { background-color: var(--brand-100); }
.bg-brand-500 { background-color: var(--brand-500); }
.bg-brand-600 { background-color: var(--brand-600); }
.bg-brand-700 { background-color: var(--brand-700); }
.bg-brand-800 { background-color: var(--brand-800); }
.bg-brand-900 { background-color: var(--brand-900); }

.text-brand-100 { color: var(--brand-100); }
.text-brand-300 { color: var(--brand-300); }
.text-brand-400 { color: var(--brand-400); }
.text-brand-500 { color: var(--brand-500); }
.text-brand-600 { color: var(--brand-600); }
.text-brand-700 { color: var(--brand-700); }
.text-brand-800 { color: var(--brand-800); }
.text-brand-900 { color: var(--brand-900); }

.border-brand-200 { border-color: var(--brand-200); }
.border-brand-500 { border-color: var(--brand-500); }
.border-brand-700 { border-color: var(--brand-700); }

.shadow-brand-500\/30 { box-shadow: 0 0 15px rgba(101, 140, 43, 0.3); }
.shadow-brand-600\/20 { box-shadow: 0 4px 6px -1px rgba(74, 93, 35, 0.2); }
.shadow-brand-600\/40 { box-shadow: 0 10px 15px -3px rgba(74, 93, 35, 0.4); }

.selection\:bg-brand-500 *::selection { background-color: var(--brand-500); color: white; }
</style>