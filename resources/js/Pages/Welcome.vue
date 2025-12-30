<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ArrowRight, Star, Shield, Zap } from 'lucide-vue-next';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import FeatureCard from '@/Components/FeatureCard.vue';
import AwardCard from '@/Components/AwardCard.vue';

defineOptions({
  layout: GuestLayout
});

const props = defineProps({
  slides: Array,
  stats: Array,
  features: Array,
  awards: Array,
  latestNews: Array
});

// Hero Slider Logic
const currentSlide = ref(0);
let slideTimer = null;

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % props.slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + props.slides.length) % props.slides.length;
};

onMounted(() => {
  slideTimer = setInterval(nextSlide, 6000);
});

onUnmounted(() => {
  if (slideTimer) clearInterval(slideTimer);
});

// Theme support
const page = usePage();
const { isDark } = useTheme();
import { useTheme } from '@/Composables/useTheme';
</script>

<template>
  <Head title="Welcome" />
  <div class="overflow-x-hidden">
    <!-- HERO SLIDER -->
    <header class="relative h-[100vh] min-h-[700px] bg-slate-900">
      <!-- Slides -->
      <div 
        v-for="(slide, idx) in slides" 
        :key="idx" 
        :class="[
          'absolute inset-0 transition-opacity duration-1000 ease-in-out', 
          idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'
        ]"
      >
        <!-- Image with Ken Burns Effect -->
        <div 
          class="absolute inset-0 bg-cover bg-center transform scale-105 transition-transform duration-[10000ms] ease-linear" 
          :class="idx === currentSlide ? 'scale-110' : 'scale-100'"
          :style="`background-image: url(${slide.image})`"
        />
        
        <!-- Premium Gradient Overlay (Improved Contrast) -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/40 to-transparent" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent" />
        
        <!-- Content -->
        <div class="relative h-full container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
          <div class="max-w-4xl space-y-8 pl-4 md:pl-8 border-l-4 border-brand-500 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-brand-300 text-sm font-semibold tracking-wider uppercase mb-2 shadow-sm">
              <Star class="w-4 h-4 fill-current" />
              Excellence in Education
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold font-display text-white leading-tight tracking-tight drop-shadow-xl">
              {{ slide.title }}
            </h1>
            
            <p class="text-xl md:text-2xl text-slate-200 font-light max-w-2xl leading-relaxed opacity-90 drop-shadow-md">
              {{ slide.subtitle }}
            </p>
            
            <div class="flex flex-wrap gap-4 pt-6">
              <Link 
                v-if="slide.cta && slide.ctaLink"
                :href="slide.ctaLink"
                class="group px-8 py-4 bg-brand-600 text-white rounded-xl font-semibold hover:bg-brand-500 transition-all shadow-[0_0_20px_rgba(34,197,94,0.4)] hover:shadow-[0_0_30px_rgba(34,197,94,0.6)] hover:-translate-y-1 flex items-center gap-3"
              >
                {{ slide.cta }}
                <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              <Link 
                href="/partner"
                class="px-8 py-4 bg-white/5 backdrop-blur-md text-white border border-white/20 rounded-xl font-semibold hover:bg-white/10 transition-all hover:-translate-y-1"
              >
                Partner With Us
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Controls & Indicators -->
      <div class="absolute bottom-12 right-4 md:right-12 z-20 flex flex-col items-end gap-6">
        <!-- Navigation Arrows -->
        <div class="flex gap-4">
          <button 
            @click="prevSlide" 
            class="p-4 rounded-full border border-white/20 text-white hover:bg-white/20 backdrop-blur-md transition hover:scale-110 active:scale-95 group"
          >
            <ChevronLeft class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" />
          </button>
          <button 
            @click="nextSlide" 
            class="p-4 rounded-full border border-white/20 text-white hover:bg-white/20 backdrop-blur-md transition hover:scale-110 active:scale-95 group"
          >
            <ChevronRight class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" />
          </button>
        </div>

        <!-- Slide Indicators -->
        <div class="flex gap-3">
            <button 
                v-for="(slide, idx) in slides" 
                :key="idx"
                @click="currentSlide = idx"
                :class="[
                    'h-1.5 rounded-full transition-all duration-300',
                    currentSlide === idx ? 'w-8 bg-brand-500' : 'w-2 bg-white/30 hover:bg-white/50'
                ]"
            />
        </div>
      </div>
      
      <!-- Floating Stats -->
      <div class="absolute bottom-0 left-0 w-full z-30 transform translate-y-1/2 hidden lg:block">
        <div class="container mx-auto px-4">
          <div class="grid grid-cols-3 gap-8 max-w-5xl mx-auto stagger-animation">
            <div 
              v-for="stat in stats" 
              :key="stat.label" 
              class="card-premium p-8 rounded-2xl text-center group border-t-4 border-brand-500 relative overflow-hidden"
            >
              <!-- Shine Effect -->
              <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
              
              <div class="relative z-10">
                <div class="text-5xl font-bold text-brand-700 mb-3 font-display group-hover:scale-110 transition-transform inline-block drop-shadow-sm">
                  {{ stat.value }}
                </div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                  {{ stat.label }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main :class="['relative z-10 pb-20 transition-colors duration-500', isDark ? 'bg-slate-950' : 'bg-gradient-to-b from-slate-50 to-white']">
      
      <!-- Spacer for floating stats -->
      <div class="h-32 hidden lg:block"></div>

      <!-- FEATURES SECTION -->
      <section 
        id="features" 
        :class="['py-24 relative overflow-hidden transition-colors duration-500', isDark ? 'bg-slate-900' : 'bg-white']"
      >
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
          <div class="absolute top-20 left-10 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl animate-pulse-slow" />
          <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-500/5 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;" />
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center max-w-3xl mx-auto mb-20 animate-fade-in-up">
            <span class="inline-block py-2 px-4 rounded-full bg-gradient-to-r from-brand-100 to-emerald-50 text-brand-700 text-xs font-bold tracking-wider uppercase mb-6 border border-brand-200">
              Why Choose Gamji
            </span>
            <h2 :class="['text-4xl md:text-5xl font-bold font-display mb-6 transition-colors duration-500', isDark ? 'text-white' : 'text-slate-900']">
              Empowering <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-brand-500 to-emerald-400">Academic Success</span>
            </h2>
            <p :class="['text-xl leading-relaxed transition-colors duration-500', isDark ? 'text-slate-400' : 'text-slate-600']">
              We provide a comprehensive ecosystem designed to support students from application to graduation with world-class resources.
            </p>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-animation">
            <FeatureCard 
              v-for="(feature, i) in features" 
              :key="i"
              :feature="feature"
              :is-dark="isDark"
              class="h-full"
            />
          </div>
        </div>
      </section>

      <!-- AWARDS SECTION -->
      <section 
        id="awards" 
        :class="['py-24 relative transition-colors duration-500', isDark ? 'bg-slate-950' : 'bg-white']"
      >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div>
              <h2 :class="['text-3xl md:text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                Available <span class="relative inline-block">
                  Grants
                  <svg class="absolute w-full h-3 -bottom-1 left-0 text-accent-400 opacity-40" viewBox="0 0 100 10" preserveAspectRatio="none">
                    <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
                  </svg>
                </span>
              </h2>
              <p class="text-slate-500 mt-4 text-lg">Explore opportunities closing soon</p>
            </div>
            <Link 
              href="/awards"
              class="hidden md:flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition-colors group"
            >
              View All Opportunities
              <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>

          <div class="grid md:grid-cols-3 gap-8">
            <AwardCard 
              v-for="award in awards" 
              :key="award.title"
              :award="award"
              :is-dark="isDark"
            />
          </div>
          
          <div class="mt-12 text-center md:hidden">
            <Link 
              href="/awards"
              class="inline-flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition-colors"
            >
              View All Opportunities
              <ArrowRight class="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      <!-- ABOUT SECTION -->
      <section id="about" :class="['py-24', isDark ? 'bg-slate-900' : 'bg-slate-50']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold font-display" :class="isDark ? 'text-white' : 'text-slate-900'">About Us</h2>
            <p class="mt-4 text-lg" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Gamji Foundation empowers students through scholarships, mentorship, and training programs.</p>
          </div>
          <div class="grid md:grid-cols-2 gap-8">
            <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md">
              <h3 class="text-xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-slate-900'">Our Mission</h3>
              <p :class="isDark ? 'text-slate-300' : 'text-slate-700'">To provide comprehensive support for academic success.</p>
            </div>
            <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md">
              <h3 class="text-xl font-bold mb-2" :class="isDark ? 'text-white' : 'text-slate-900'">Our Vision</h3>
              <p :class="isDark ? 'text-slate-300' : 'text-slate-700'">A world where every student can achieve their potential.</p>
            </div>
          </div>
        </div>
      </section>



      <!-- LATEST NEWS SECTION -->
      <section 
        v-if="latestNews && latestNews.length > 0"
        id="news" 
        :class="['py-24 relative transition-colors duration-500', isDark ? 'bg-slate-900' : 'bg-slate-50']"
      >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
              <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 text-xs font-bold tracking-wider uppercase mb-4 border border-brand-200">
                Insights & Updates
              </span>
              <h2 :class="['text-3xl md:text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                Latest <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-emerald-500">News</span>
              </h2>
            </div>
            <Link 
              href="/news"
              class="hidden md:flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition-colors group"
            >
              View All News
              <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>

          <div class="grid md:grid-cols-3 gap-8">
            <template v-for="news in latestNews" :key="news.id">
                <Link :href="route('news.show', news.slug)" class="group block h-full"> <!-- Make the whole card clickable -->
                    <article 
                        :class="[
                        'h-full rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col',
                        isDark ? 'bg-slate-800 border border-slate-700' : 'bg-white border border-slate-100'
                        ]"
                    >
                        <!-- Image Container -->
                        <div v-if="news.image" class="relative h-56 overflow-hidden">
                        <img 
                            :src="news.image" 
                            :alt="news.title" 
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60" />
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 text-xs font-bold text-white bg-brand-600/90 backdrop-blur-sm rounded-full shadow-sm">
                            {{ news.category }}
                            </span>
                        </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ news.date }}
                        </div>

                        <h3 :class="['text-xl font-bold mb-3 line-clamp-2 group-hover:text-brand-600 transition-colors', isDark ? 'text-white' : 'text-slate-900']">
                            {{ news.title }}
                        </h3>

                        <p :class="['text-sm mb-4 line-clamp-3 mb-auto', isDark ? 'text-slate-400' : 'text-slate-600']">
                            {{ news.excerpt }}
                        </p>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center text-brand-600 font-semibold text-sm">
                            Read Article
                            <ArrowRight class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" />
                        </div>
                        </div>
                    </article>
                </Link>
            </template>
          </div>

          <div class="mt-12 text-center md:hidden">
            <Link 
              href="/news"
              class="inline-flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition-colors"
            >
              View All News
              <ArrowRight class="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      <!-- CTA BANNER -->
      <section class="py-24 px-4">
        <div class="max-w-7xl mx-auto rounded-[2.5rem] overflow-hidden relative bg-gradient-to-br from-brand-900 to-brand-800 shadow-2xl transform hover:scale-[1.01] transition-transform duration-500">
          <!-- Decorative Elements -->
          <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]" />
          <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500 rounded-full blur-[120px] opacity-20 pointer-events-none animate-pulse-slow" />
          <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-accent-500 rounded-full blur-[100px] opacity-10 pointer-events-none" />
          
          <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between p-12 md:p-20 gap-12">
            <div class="text-left max-w-2xl">
              <h2 class="text-4xl md:text-5xl font-bold font-display text-white mb-6 leading-tight">
                Start Your <span class="text-accent-300">Excellence Journey</span> Today
              </h2>
              <p class="text-brand-100 text-xl leading-relaxed font-light">
                Create a profile to get matched with scholarships, mentors, and training programs tailored specifically to your unique skills and ambitions.
              </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 shrink-0 w-full lg:w-auto">
              <Link 
                href="/register"
                class="px-10 py-5 bg-white text-brand-900 rounded-xl font-bold hover:bg-brand-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 text-center"
              >
                Register Now
              </Link>
              <Link 
                href="/contact"
                class="px-10 py-5 bg-brand-800/50 backdrop-blur-sm text-white border border-brand-600 rounded-xl font-bold hover:bg-brand-700/50 transition-all hover:-translate-y-1 text-center"
              >
                Contact Support
              </Link>
            </div>
          </div>
        </div>
      </section>
      
    </main>
  </div>
</template>