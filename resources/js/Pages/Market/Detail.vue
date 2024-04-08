<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Header from '@/Components/Header.vue';
import { Inertia } from '@inertiajs/inertia';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import { defineComponent, onMounted } from 'vue';

const props = defineProps({
    product: Object,
    qty : String
});

const main   = ref();
const thumbs = ref();

const mainOptions = {
    type      : 'loop',
    perPage   : 1,
    perMove   : 1,
    gap       : '1rem',
    pagination: false,
};

const thumbsOptions = {
    type     : 'loop',
    rewind      : true,
    gap         : '1rem',
    pagination  : false,
    fixedWidth  : 110,
    fixedHeight : 50,
    cover       : true,
    focus       : 'center',
    isNavigation: true,
    updateOnMove: true,
    autoplay: true,
};

onMounted( () => {
    const thumbsSplide = thumbs.value?.splide;

    if ( thumbsSplide ) {
    main.value?.sync( thumbsSplide );
    }
} );

const menuToogle = ref(false);
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    // var n = !isFinite(+number) ? 0 : +number,
    //     prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    //     sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    //     dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    //     s = '',
    //     toFixedFix = function (n, prec) {
    //         var k = Math.pow(10, prec);
    //         return '' + Math.round(n * k) / k;
    //     };
    // // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    // s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    // if (s[0].length > 3) {
    //     s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    // }
    // if ((s[1] || '').length < prec) {
    //     s[1] = s[1] || '';
    //     s[1] += new Array(prec - s[1].length + 1).join('0');
    // }
    return number;
}

let qty = ref(props.qty);
// watch(qty, (value) => {
//     alert(value);
//     qty.value = number_format(value, 2, '.', '.');    
// });

function changeQty(){
    qty.value = number_format(qty.value, 0, '', '');    
}

function tambahQty(){
    qty.value = qty.value+1;
}

function kurangQty(){
    if(qty.value>0){
        qty.value = qty.value-1;
    }
}

var Toast = Swal.mixin({
    toast: true,
    icon: 'success',
    title: 'General Title',
    animation: false,
    position: 'top-right',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

function addKeranjang(){
    Inertia.post(
        "/addKeranjang",
        { 
            qty: qty.value,
            product_id: props.product.id
        },
        {
            onFinish: () => {
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                })
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

        <div class="grid grid-cols-2 md:grid-cols-3 grid-flow-row gap-10">
            <div class="col-span-2">
                <div class="mb-5">
                    <Splide aria-labelledby="thumbnail-example-heading" :options="mainOptions" ref="main" >
                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product1' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product2' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product3' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product4' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>
                    </Splide>
                </div>
                <div>
                    <Splide
                        aria-label="The carousel with thumbnails. Selecting a thumbnail will change the main carousel"
                        :options="thumbsOptions"
                        ref="thumbs"
                    >
                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product1' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product2' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product3' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>

                        <SplideSlide >
                            <img :src="route('image', { 'jenis' : 'product4' , 'id' : product.id })" class="h-full w-full object-cover" />
                        </SplideSlide>
                    </Splide>
                </div>
            </div>
            <div>
                <div class="text-[20px]">
                    {{ product.name }}
                </div>
                <div class="font-bold text-[30px]">
                    {{ product.price }}
                </div>
                <div>
                    <div>Merk : {{ product.brand_name }}</div>
                    <div>Kategori : {{ product.category_name }}</div>
                </div>
                <div v-html="product.product_details" class="h-[460px] overflow-auto"></div>
                <div >
                    <div>Jumlah Produk</div>
                    <div class="flex">
                        <div @click="kurangQty" class="flex items-center justify-center bg-white border rounded-l-lg border-[#c9c9c9] w-10">
                            -
                        </div>
                        <div class=" w-20">
                            <input type="text" @change="changeQty" v-model.lazy="qty" class="w-full border-[#c9c9c9] text-center">
                        </div>
                        <div @click="tambahQty" class="flex items-center justify-center bg-white border rounded-r-lg border-[#c9c9c9] w-10">
                            +
                        </div>
                    </div>
                    <div>
                        <button @click="addKeranjang" class="flex gap-4 px-5 bg-blue-500 font-bold py-3 text-white mt-2  rounded-lg">
                            Tambah Keranjang
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</template>

