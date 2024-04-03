<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    category: Object,
    product: Object,
    category_id: String,
    search: String
})
const menuToogle = ref(false);
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
</script>

<template>
    <Head title="Kategori" />
    <Header :category_id="category_id" :search="search" />
    <!-- Kategori -->
    <div class="w-full px-5 md:px-20 pt-14 bg-[#f0f0f0] pb-20">
        <h2 class="font-bold text-[30px]">Kategori</h2>

        <div class="grid grid-cols-2 md:grid-cols-5 grid-flow-row gap-4">
            <div v-for="(item, index) in category" :key="item.id" >
                <div @click="changeKategory(item.id)" class="mb-5 md:mb-0 p-4 relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                    <div  :style="'background-color:'+item.warna_bg+';'" class="flex text-white items-center text-center font-bold h-28 overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                        <div class="w-full text-center text-[15px] md:text-[20px]">{{ item.name }}</div>
                    </div>
                    <div class="text-center font-bold mt-5">
                        {{ item.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

