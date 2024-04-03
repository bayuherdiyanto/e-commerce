<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    promo: Object,
})
</script>

<template>
    <Head title="Kategori" />
    <Header :category_id="category_id" :search="search" />
    <!-- Kategori -->
    <div class="w-full px-5 md:px-20 pt-14 bg-[#f0f0f0] pb-20">
        <h2 class="font-bold text-[30px]">Voucher</h2>

        <div class="grid grid-cols-2 md:grid-cols-5 grid-flow-row gap-4">
            <div v-for="(item, index) in promo" :key="item.id" >
                <div class="mb-5 md:mb-0 p-4 relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                    <div class="flex items-center justify-center text-center font-bold overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                        <svg class="w-40 h-40" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <div>Kode Voucer : </div>
                    <div class="w-full text-[15px] md:text-[20px] font-bold">{{ item.name }}</div>
                    <div class=" font-bold mt-2">
                        Berlaku sampai : {{ item.end_date }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

