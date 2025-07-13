<script setup>
import { watch, ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    category_id: String,
    search: String,
    smaSetting: Object,
    totalKeranjang: String
});

function changeKategory(category_id){
    Inertia.get(
        "/produk",
        { 
            category_id: category_id,
        },
        {
            onFinish: () => {
                
            },
            preserveState: true,
        }
    );
}

const menuToogle = ref(false);
</script>

<template>
    <div class="flex item-center  justify-between w-full bg-blue-500 px-5 md:px-20 py-5">
        <div class="w-1/2 flex items-center gap-10 ">
            <div class="font-bold text-white">
                <Link :href="route('market')">
                    {{ smaSetting.site_name }}
                </Link>
            </div>
        </div>
        <div class="hidden md:block w-1/2">
            <nav class="flex-col flex-grow pb-4 md:pb-0 hidden md:flex md:justify-end md:flex-row">
            <Link :href="route('market')" :class="(route().current('market')) ? 'bg-white' : 'text-white bg-transparent'" class="px-4 py-2 mt-2 text-sm font-semibold text-gray-900 flex items-center rounded-lg md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-white focus:bg-gray-200 focus:outline-none focus:shadow-outline" >
                Home
            </Link>
            <Link :href="route('kategori')" :class="(route().current('kategori')) ? 'bg-white' : 'text-white bg-transparent'" class="px-4 py-2 mt-2  text-sm font-semibold bg-transparent rounded-lg flex items-center md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-white focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">
                Kategori
            </Link>
            <Link :href="route('produk')" :class="(route().current('produk')) ? 'bg-white' : 'text-white bg-transparent'" class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg flex items-center md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-white focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">
                Produk
            </Link>
            <Link :href="route('promo')" :class="(route().current('promo')) ? 'bg-white' : 'text-white bg-transparent'"  class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg flex items-center md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-white focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">
                Promo
            </Link>
            <Link :href="route('keranjang')" class="relative px-4 py-2 mt-2 text-white text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-white focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">
                <div class="absolute top-0 right-1 text-[12px] font-bold h-5 w-5 text-center bg-red-500 rounded-full">
                    {{totalKeranjang}}
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </Link>
            
            </nav>
        </div>
        <div class="md:hidden">
            <svg fill="currentColor" v-show="!menuToogle" @click="menuToogle=!menuToogle" viewBox="0 0 20 20" class="w-6 h-6">
                <path  fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            <svg v-show="menuToogle" @click="menuToogle=!menuToogle" fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                <path   fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>

    <div class="sticky md:hidden top-0 item-center justify-between w-full bg-blue-500 px-5 md:px-20 py-5 z-10">
        <div class="flex bg-[#d9d9d9] rounded-lg pl-2 py-0 w-full" style="border-radius:var(--Default-Border-Raius, 4px); border:1px solid var(--Light-Border, #E2E4EA); background:var(--Dominant, #FFF);">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-black mt-1"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari Produk" class="w-full rounded-lg h-8 pt-0 pb-0 pl-2 focus:outline-0 searchbar-input border-none focus:ring-0">
        </div>
    </div>
    
    <div v-show="menuToogle" class="md:hidden w-full bg-blue-400">
        <nav class="flex flex-col flex-grow p-5 md:pb-0 ">
            <Link :href="route('market')" :class="(route().current('market')) ? 'bg-blue-600' : 'bg-transparent'" class="px-2 text-white font-bold py-2 mt-2 text-sm font-semibold text-gray-900 rounded-lg  " >
                Home
            </Link>
            <Link :href="route('kategori')" :class="(route().current('kategori')) ? 'bg-blue-600' : 'bg-transparent'" class="px-2 text-white font-bold py-2 mt-2 text-sm font-semibold rounded-lg " href="#">
                Kategori
            </Link>
            <Link :href="route('produk')" :class="(route().current('produk')) ? 'bg-blue-600' : 'bg-transparent'" class="px-2 text-white font-bold py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg " href="#">
                Produk
            </Link>
            <a class="px-2 text-white font-bold py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg " href="#">
                Promo
            </a>
            <a :href="route('keranjang')" class="relative px-2 text-white font-bold py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg" href="#">
                <div class="absolute top-0 right-1 text-[12px] font-bold h-5 w-5 text-center bg-red-500 rounded-full">
                    {{totalKeranjang}}
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </a>
        </nav>
    </div>
</template>
