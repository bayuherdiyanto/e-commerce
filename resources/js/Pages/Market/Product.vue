<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
const props = defineProps({
    category: Object,
    product: Object,
    search: String,
    status_online: String,
    category_name: String,
    category_id: String,
    smaSetting: Object
});

const tidakTersedia = new URL("../../../images/tidak_tersedia.jpg", import.meta.url);

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
            search: props.search,
            category_id: category_id,
        },
        {
            onFinish: () => {
                
            },
            preserveState: true,
        }
    );
}
let search = ref(props.search);
let status_online = ref(props.status_online);
let category_id = ref(props.category_id);
watch([search, status_online], (value) => {

    Inertia.get(
        "/produk",
        { 
            search: value[0],
            status_online: value[1],
            category_id: value[2],
        },
        {
            preserveState: true,
        }
    );
});

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
    <Head title="Produk" />
    <Header :category_id="category_id" :search="props.search" :smaSetting="smaSetting" />
    
    <!-- Produk -->
    <div class="md:hidden w-full px-5 md:px-20 md:pt-14 pb-3 ">
        <div class="flex md:hidden mt-8 bg-[#d9d9d9] rounded-lg pl-2 py-0" style="border-radius:var(--Default-Border-Raius, 4px); border:1px solid var(--Light-Border, #E2E4EA); background:var(--Dominant, #FFF);">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-black mt-1"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input v-model.lazy="search" type="text" placeholder="Cari Produk" class="w-full rounded-lg h-8 pt-0 pb-0 pl-2 focus:outline-0 searchbar-input border-none focus:ring-0">
        </div>
    </div>
    <div class="md:hidden w-full px-5 md:px-20 md:pt-14 pb-3 ">
        <select v-model="props.category_id" @change="changeKategory($event.target.value)" class="w-full  rounded-md shadow-md">
            <option value="">Semua Kategori</option>
            <option v-for="(item, index) in category" :key="item.id" :value="item.id">{{ item.name }}</option>
        </select>
    </div>
    <div class="w-full px-5 md:px-20 md:pt-14 pb-3 bg-[#f0f0f0] ">
        <div class="flex items-center mb-2 px-3 py-2 text-[15px] rounded-md gap-5 bg-white bg-clip-border text-gray-700 shadow-md">
            <div>Produk</div>
            <div v-if="category_name">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06l6.22-6.22H3a.75.75 0 0 1 0-1.5h16.19l-6.22-6.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div v-if="category_name">
                {{ category_name }}
            </div>

        </div>
    </div>
    
    <div class="flex gap-5 w-full px-5 md:px-20 pb-20 bg-[#f0f0f0]">
        <div class="w-full md:w-3/4">
            <div v-if="search" class="mb-4 font-bold">
                Hasil pencarian '{{ search }}'
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 grid-flow-row gap-4 gap-4">
                <div v-for="(item, index) in product.data" :key="item.id" @click="changeProduct(item.id)" class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
                    <div class="relative mx-3 mt-3 h-32 md:h-48 overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                        <img  v-if="item.image1" :src="route('image', { 'jenis' : 'product1' , 'id' : item.id })" class="h-full w-full object-cover" />
                        <img v-else :src="tidakTersedia.href" >
                    </div>
                    <div class="p-3">
                        <div >
                            <div class="block text-[12px] md:text-[14px] h-12 font-sans text-base font-medium leading-relaxed text-blue-gray-900 antialiased">
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
        <div class="hidden md:inline w-1/4">
            <h3 class="font-bold text-[20px] mb-2">Kategori</h3>

            <div class="grid grid-cols-1 md:grid-cols-1 grid-flow-row gap-2">
                <div :class="(category_name=='') ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'" class="p-2 relative flex flex-col rounded-xl bg-clip-border text-gray-700 shadow-md">
                    <div @click="changeKategory('')" class="pl-3 font-bold">
                        Semua Kategori
                    </div>
                </div>
                <div :class="(category_name==item.name) ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'" v-for="(item, index) in category" :key="item.id" class="p-2 relative flex flex-col rounded-xl bg-clip-border shadow-md">
                    <div @click="changeKategory(item.id)" class="pl-3 font-bold">
                        {{ item.name }}
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</template>

