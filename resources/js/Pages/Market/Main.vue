<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

const tidakTersedia = new URL("../../../images/tidak_tersedia.jpg", import.meta.url);


defineProps({
    category: Object,
    product: Object,
    search: String,
    status_online: String,
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

function changeProduct(id){
    Inertia.get(
        "/detail/"+id,
        { 
           
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
    <Head title="Home" />
    <Header :category_id="category_id" :search="search" />
    
    
    <!-- Kategori -->
    <div class="w-full px-5 md:px-20 pt-14 bg-[#f0f0f0]">
        <h2 class="font-bold text-[30px]">Kategori</h2>

        <div class="grid grid-cols-2 md:grid-cols-5 grid-flow-row gap-4">
            <div v-for="(item, index) in category" :key="item.id" >
                <div @click="changeKategory(item.id)" class="mb-5 md:mb-0 p-4 relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md" >
                    <div :style="'background-color:'+item.warna_bg+';'" class="flex text-white items-center text-center font-bold h-28 overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                        <div class="w-full text-center text-[15px] md:text-[20px]">{{ item.name }}</div>
                    </div>
                    <div class="text-center font-bold mt-5">
                        {{ item.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Produk -->
    <div class="w-full px-5 md:px-20 pt-14 pb-20 bg-[#f0f0f0]">
        <h2 class="font-bold text-[30px]">Produk</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 grid-flow-row gap-4 gap-4">
            <div v-for="(item, index) in product.data" :key="item.id" @click="changeProduct(item.id)" class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                <div class="relative mx-3 mt-3 h-48 overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                    <img v-if="item.image1" :src="route('image', { 'jenis' : 'product1' , 'id' : item.id })" class="h-full w-full object-cover" />
                    <img v-else :src="tidakTersedia.href" />
                </div>
                <div class="p-3">
                    <div >
                        <div class="block text-[14px]  h-12 font-sans text-base font-medium leading-relaxed text-blue-gray-900 antialiased">
                            {{ item.name }}
                        </div>
                        <div class="font-bold block font-sans text-base leading-relaxed text-blue-gray-900 antialiased">
                            Rp. {{ number_format(item.price,0,'.','.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:px-5">
            <pagination class="mt-10 w-full flex justify-center mt-5" :kata_kunci="search" :status_online="status_online" :links="product.links" />
        </div>
    </div>
</template>

