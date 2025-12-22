<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Sun, Moon, Menu, X, Facebook, Twitter, Instagram, Linkedin, Mail, Phone, MapPin } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import logo from '@/../images/logo.jpeg'; 

const { isDark, toggleTheme } = useTheme();
const scrollY = ref(0);
const mobileMenuOpen = ref(false);

const navItems = ['Features', 'Awards', 'Events', 'About'];

const footerLinks = {
  platform: [
    { name: 'Scholarship Portal', href: '/scholarships' },
    { name: 'Mentorships', href: '/mentorship' },
    { name: 'Success Stories', href: '/stories' }
  ],
  resources: [
    { name: 'Exam Prep', href: '/exam-prep' },
    { name: 'Career Guide', href: '/career' },
    { name: 'Blog', href: '/blog' }
  ],
  contact: [
    { name: 'Abuja, Nigeria', href: null, icon: MapPin },
    { name: 'support@gamji.org', href: 'mailto:support@gamji.org', icon: Mail },
    { name: '+234 800 123 4567', href: 'tel:+2348001234567', icon: Phone }
  ]
};

const handleScroll = () => {
  scrollY.value = window.scrollY;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const navClasses = computed(() => {
  const base = 'fixed w-full z-50 transition-all duration-300 border-b';
  if (scrollY.value > 10) {
    return isDark.value 
      ? `${base} bg-slate-900/80 backdrop-blur-md border-slate-800 shadow-lg` 
      : `${base} bg-white/80 backdrop-blur-md border-white/20 shadow-sm`;
  }
  return `${base} bg-transparent border-transparent`;
});
</script>

<template>
  <div 
    :class="[
      'min-h-screen transition-colors duration-500 font-sans selection:bg-brand-500 selection:text-white', 
      isDark ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-800'
    ]"
  >
    <!-- NAVIGATION -->
    <nav :class="navClasses">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          
          <!-- Logo -->
          <Link href="/" class="flex items-center space-x-3 group cursor-pointer">
            <div class="relative w-11 h-11 flex items-center justify-center bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl shadow-glow group-hover:shadow-glow-lg transition-all duration-300 group-hover:scale-110">
              <span class="text-white font-bold text-xl font-display"><img :src="logo" alt="Gamji Foundation Logo"></span>
            </div>
            <div class="flex flex-col">
              <span :class="['text-xl font-bold tracking-tight font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                GAMJI
              </span>
              <span class="text-[10px] uppercase tracking-wider font-bold text-brand-600 -mt-1">
                Foundation
              </span>
            </div>
          </Link>

          <!-- Desktop Links -->
          <div class="hidden md:flex items-center space-x-8">
            <a 
              v-for="item in navItems" 
              :key="item" 
              :href="`#${item.toLowerCase()}`" 
              :class="[
                'text-sm font-medium transition-colors hover:text-brand-500 relative group', 
                isDark ? 'text-slate-300' : 'text-slate-600'
              ]"
            >
              {{ item }}
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
            </a>
          </div>

          <!-- Actions -->
          <div class="hidden md:flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button 
              @click="toggleTheme" 
              :class="[
                'p-2 rounded-full transition-colors', 
                isDark ? 'bg-slate-800 text-accent-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              ]"
            >
              <Sun v-if="isDark" class="w-5 h-5" />
              <Moon v-else class="w-5 h-5" />
            </button>
            
            <Link 
              href="/login"
              :class="[
                'px-5 py-2 rounded-lg text-sm font-semibold transition-all', 
                isDark ? 'text-slate-200 hover:bg-slate-800' : 'text-slate-700 hover:bg-slate-100'
              ]"
            >
              Log In
            </Link>
            
            <Link 
              href="/register"
              class="px-5 py-2 bg-brand-600 text-white rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/20 hover:bg-brand-700 hover:shadow-brand-600/40 transition-all transform hover:-translate-y-0.5"
            >
              Get Started
            </Link>
          </div>

          <!-- Mobile Toggle -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="md:hidden p-2 text-slate-500"
          >
            <X v-if="mobileMenuOpen" class="w-6 h-6" />
            <Menu v-else class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <transition 
        enter-active-class="transition duration-200 ease-out" 
        enter-from-class="transform -translate-y-2 opacity-0" 
        enter-to-class="transform translate-y-0 opacity-100" 
        leave-active-class="transition duration-150 ease-in" 
        leave-from-class="transform translate-y-0 opacity-100" 
        leave-to-class="transform -translate-y-2 opacity-0"
      >
        <div 
          v-if="mobileMenuOpen" 
          :class="[
            'md:hidden border-t px-4 py-6 space-y-4 shadow-xl backdrop-blur-xl', 
            isDark ? 'bg-slate-900/95 border-slate-800' : 'bg-white/95 border-slate-100'
          ]"
        >
          <a 
            v-for="item in navItems" 
            :key="item" 
            :href="`#${item.toLowerCase()}`"
            :class="['block font-medium text-lg', isDark ? 'text-slate-200' : 'text-slate-800']"
            @click="mobileMenuOpen = false"
          >
            {{ item }}
          </a>
          
          <div :class="['h-px my-4', isDark ? 'bg-slate-700' : 'bg-slate-200']"></div>
          
          <div class="flex items-center justify-between">
             <span :class="isDark ? 'text-slate-400' : 'text-slate-600'">Theme</span>
             <button 
              @click="toggleTheme" 
              :class="[
                'p-2 rounded-full transition-colors', 
                isDark ? 'bg-slate-800 text-accent-400' : 'bg-slate-100 text-slate-600'
              ]"
            >
              <Sun v-if="isDark" class="w-5 h-5" />
              <Moon v-else class="w-5 h-5" />
            </button>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-4">
            <Link 
              href="/login"
              :class="[
                'block w-full py-3 rounded-lg text-center font-semibold border',
                isDark ? 'border-slate-700 text-slate-200' : 'border-slate-200 text-slate-700'
              ]"
              @click="mobileMenuOpen = false"
            >
              Log In
            </Link>
            <Link 
              href="/register"
              class="block w-full py-3 rounded-lg text-center font-bold bg-brand-600 text-white shadow-lg"
              @click="mobileMenuOpen = false"
            >
              Get Started
            </Link>
          </div>
        </div>
      </transition>
    </nav>
    
    <!-- Page Content -->
    <slot />
    
    <!-- FOOTER -->
    <footer class="pt-24 pb-12 border-t bg-slate-950 border-slate-900 text-slate-300 relative overflow-hidden">
        <!-- Footer Background Decoration -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-900 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-brand-900 rounded-full blur-3xl"></div>
        </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          <!-- Brand -->
          <div class="col-span-1 lg:col-span-1">
            <div class="flex items-center space-x-2 mb-6">
              <div class="w-10 h-10 bg-gradient-to-br from-brand-600 to-brand-800 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                G
              </div>
              <span class="text-2xl font-bold text-white font-display">GAMJI</span>
            </div>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
              Building bridges to a brighter future for Nigerian students through accessible education, mentorship, and financial aid.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="text-slate-400 hover:text-brand-400 transition-colors"><Twitter class="w-5 h-5" /></a>
                <a href="#" class="text-slate-400 hover:text-brand-400 transition-colors"><Facebook class="w-5 h-5" /></a>
                <a href="#" class="text-slate-400 hover:text-brand-400 transition-colors"><Instagram class="w-5 h-5" /></a>
                <a href="#" class="text-slate-400 hover:text-brand-400 transition-colors"><Linkedin class="w-5 h-5" /></a>
            </div>
          </div>
          
          <!-- Platform Links -->
          <div>
            <h4 class="text-white font-bold mb-6 font-display">Platform</h4>
            <ul class="space-y-3 text-sm text-slate-400">
              <li v-for="link in footerLinks.platform" :key="link.name">
                <Link :href="link.href" class="hover:text-brand-400 transition-colors flex items-center gap-2 group">
                  <span class="w-1.5 h-1.5 rounded-full bg-brand-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                  {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>
          
          <!-- Resources Links -->
          <div>
            <h4 class="text-white font-bold mb-6 font-display">Resources</h4>
            <ul class="space-y-3 text-sm text-slate-400">
              <li v-for="link in footerLinks.resources" :key="link.name">
                <Link :href="link.href" class="hover:text-brand-400 transition-colors flex items-center gap-2 group">
                  <span class="w-1.5 h-1.5 rounded-full bg-brand-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                  {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>
          
          <!-- Contact -->
          <div>
            <h4 class="text-white font-bold mb-6 font-display">Contact</h4>
            <ul class="space-y-4 text-sm text-slate-400">
              <li v-for="item in footerLinks.contact" :key="item.name" class="flex items-start gap-3">
                <component :is="item.icon" class="w-5 h-5 text-brand-500 shrink-0" />
                <a 
                  v-if="item.href" 
                  :href="item.href" 
                  class="hover:text-brand-400 transition-colors"
                >
                  {{ item.name }}
                </a>
                <span v-else>{{ item.name }}</span>
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
          <p>&copy; 2025 GAMJI Foundation. All rights reserved.</p>
          <div class="flex space-x-8 mt-4 md:mt-0">
            <Link href="/privacy" class="hover:text-white transition-colors">
              Privacy Policy
            </Link>
            <Link href="/terms" class="hover:text-white transition-colors">
              Terms of Service
            </Link>
            <Link href="/cookies" class="hover:text-white transition-colors">
              Cookie Policy
            </Link>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
