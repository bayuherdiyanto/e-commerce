
<template>
  <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t shadow flex justify-between items-center px-2 py-1" style="display: flex;">
    <!-- Home -->
    <Link :href="route('market')" class="flex flex-col items-center flex-1 py-2" :class="{ 'text-blue-600': isActive('market') }">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4.5 10.5v9a1.5 1.5 0 001.5 1.5h3a1.5 1.5 0 001.5-1.5v-6h3v6a1.5 1.5 0 001.5 1.5h3a1.5 1.5 0 001.5-1.5v-9"/></svg>
      <span class="text-xs">HOME</span>
    </Link>
    <!-- Produk -->
    <Link :href="route('produk')" class="flex flex-col items-center flex-1 py-2" :class="{ 'text-blue-600': isActive('produk') }">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
      <span class="text-xs">PRODUK</span>
    </Link>
    <!-- Floating WhatsApp Button -->
    <div class="relative flex-1 flex justify-center">
      <a :href="waLink" target="_blank" rel="noopener" class="absolute -top-7 left-1/2 -translate-x-1/2 bg-orange-500 rounded-full shadow-lg flex items-center justify-center w-14 h-14 border-4 border-white z-50">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51-.173-.007-.372-.009-.571-.009-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.099 3.2 5.077 4.363.71.306 1.263.489 1.695.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.288.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
      </a>
    </div>
    <!-- Promo -->
    <Link :href="route('promo')" class="flex flex-col items-center flex-1 py-2" :class="{ 'text-blue-600': isActive('promo') }">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17M9 7a2 2 0 11-4 0 2 2 0 014 0zm10 10a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <span class="text-xs">PROMO</span>
    </Link>
    <!-- Cart -->
    <Link :href="route('keranjang')" class="flex flex-col items-center flex-1 py-2 relative" :class="{ 'text-blue-600': isActive('keranjang') }">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.5 17h9a1 1 0 00.85-1.53L17 13M7 13V6a1 1 0 011-1h6a1 1 0 011 1v7"/></svg>
      <span class="text-xs">CART</span>
      <span v-if="cartCount > 0" class="absolute -top-1 right-3 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ cartCount }}</span>
    </Link>
  </nav>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

const waNumber = '6281234567890'; // Ganti dengan nomor admin
const waLink = `https://wa.me/${waNumber}`;

const page = usePage();
const cartCount = computed(() => page.props.value.totalKeranjang || 0);

function isActive(routeName) {
  return page.url === route(routeName);
}
</script>

<style scoped>
nav {
  box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
}
</style> 