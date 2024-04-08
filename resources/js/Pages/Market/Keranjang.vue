<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    keranjang: Object,
    keranjangDetail: Object
});

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
</script>

<template>
    <Head title="Kategori" />
    <Header :category_id="category_id" :search="search" />
    <!-- Kategori -->
    <div class="w-full px-5 md:px-20 pt-14 bg-[#f0f0f0] pb-20">
        <h2 class="font-bold text-[30px]">Keranjang Belanja</h2>

        <div class="flex gap-10">
            <div class="w-2/3">
                <div v-for="(item, index) in keranjangDetail" :key="item.id" class="w-full  mb-5 p-4 relative rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                    <div class="flex gap-5 mb-5">
                         <div class="w-40">
                            <img :src="route('image', { 'jenis' : 'product1' , 'id' : item.product.id })" class="h-full w-full object-cover" />
                        </div>
                        <div class="">Nama : {{ item.item_name }}</div>
                        <div class="">Harga : {{ number_format(item.product.price,2, ',','.') }}</div>
                        <div class="">Jumlah : {{ number_format(item.qty,2, ',','.') }}</div>
                        <div class="">Total : {{ number_format(item.total,2, ',','.') }}</div>
                    </div>
                    <div class="">
                        <div>Catatan</div>
                        <div class="flex">
                            <textarea class="w-full"></textarea>
                            <button class="w-20 flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 mb-5 md:mb-0 p-4 relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                <div class="flex justify-between">
                    <div>Total Belanja : </div>
                    <div>{{ keranjang.total }}</div>
                </div>
                <div>
                    <button @click="addKeranjang" class="w-full gap-4 px-5 bg-blue-500 font-bold py-3 text-white mt-2  rounded-lg">
                        Lanjut Pembelian
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

