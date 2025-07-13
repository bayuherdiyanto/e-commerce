<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import { watch, ref, onMounted, onUnmounted } from 'vue';
import Header from '@/Components/Header.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import BottomNav from '@/Components/BottomNav.vue';

const tidakTersedia = new URL("../../../images/tidak_tersedia.jpg", import.meta.url);

const props = defineProps({
    category: Object,
    product: Object,
    search: String,
    status_online: String,
    category_id: String,
    smaSetting: Object,
    totalKeranjang: Object
});

const menuToogle = ref(false);

// Tambahkan data lokal untuk search
let searchLocal = ref(props.search);

// Infinite scroll state
const isLoading = ref(false);
const hasMoreData = ref(true);
const currentPage = ref(1);
const allProducts = ref([...props.product.data]);

// Watch perubahan searchLocal dan trigger pencarian
watch(searchLocal, (value) => {
    // Reset infinite scroll state when search changes
    allProducts.value = [];
    currentPage.value = 1;
    hasMoreData.value = true;
    isLoading.value = false;
    
    Inertia.get(
        "/",
        {
            search: value,
            category_id: props.category_id,
        },
        {
            preserveState: true,
            onSuccess: (page) => {
                // Update products after search
                if (page.props.product && page.props.product.data) {
                    allProducts.value = [...page.props.product.data];
                }
            }
        }
    );
});

// Popup state
const showPopup = ref(false);
const selectedProduct = ref(null);
const selectedQty = ref(1);
const selectedSatuan = ref(null);
const productData = ref(null);
const satuanOptions = ref([]);
const dataHarga = ref({});

// Toast notification
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

// Function to load more data
function loadMoreData() {
    if (isLoading.value || !hasMoreData.value) return;
    
    isLoading.value = true;
    const nextPage = currentPage.value + 1;
    
    Inertia.get(
        "/",
        {
            page: nextPage,
            search: searchLocal.value,
            category_id: props.category_id || ''
        },
        {
            onSuccess: (page) => {
                if (page.props.product && page.props.product.data && page.props.product.data.length > 0) {
                    allProducts.value = [...allProducts.value, ...page.props.product.data];
                    currentPage.value = nextPage;
                    
                    // Check if there's more data
                    if (page.props.product.current_page >= page.props.product.last_page) {
                        hasMoreData.value = false;
                    }
                } else {
                    hasMoreData.value = false;
                }
                isLoading.value = false;
            },
            onError: (errors) => {
                console.error('Error loading more data:', errors);
                hasMoreData.value = false;
                isLoading.value = false;
            },
            preserveState: true,
            preserveScroll: true,
            replace: false
        }
    );
}

// Scroll event handler
function handleScroll() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    
    // Load more when user is near bottom (100px from bottom)
    if (documentHeight - scrollTop - windowHeight < 100) {
        loadMoreData();
    }
}

// Mount and unmount scroll listener
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Function to open popup and load product data
async function openAddToCartPopup(product) {
    selectedProduct.value = product;
    selectedQty.value = 1;
    showPopup.value = true;
    
    // Load detailed product data
    try {
        const response = await fetch(`/detail-product-data/${product.id}`);
        const data = await response.json();
        
        if (data.success) {
            productData.value = data.product;
            satuanOptions.value = data.satuan || [];
            dataHarga.value = data.dataHarga || {};
            
            // Set default satuan
            if (data.product && data.product.id_satuan) {
                selectedSatuan.value = data.product.id_satuan;
            } else if (satuanOptions.value.length > 0) {
                selectedSatuan.value = satuanOptions.value[0].id;
            }
        } else {
            console.error('Failed to load product data:', data.message);
            // Fallback to basic product data
            productData.value = {
                ...product,
                brand_name: '',
                category_name: '',
                price: "Rp. " + number_format(product.price, 0, '.', '.')
            };
            selectedSatuan.value = null;
        }
    } catch (error) {
        console.error('Error loading product data:', error);
        // Fallback to basic product data
        productData.value = {
            ...product,
            brand_name: '',
            category_name: '',
            price: "Rp. " + number_format(product.price, 0, '.', '.')
        };
        selectedSatuan.value = null;
    }
}

// Function to close popup
function closePopup() {
    showPopup.value = false;
    selectedProduct.value = null;
    selectedQty.value = 1;
    selectedSatuan.value = null;
    productData.value = null;
    satuanOptions.value = [];
    dataHarga.value = {};
}

// Function to change quantity
function changeQty() {
    selectedQty.value = number_format(selectedQty.value, 0, '', '');
}

function tambahQty() {
    selectedQty.value = parseInt(selectedQty.value) + 1;
}

function kurangQty() {
    if (selectedQty.value > 1) {
        selectedQty.value = parseInt(selectedQty.value) - 1;
    }
}

// Function to change satuan
function changeSatuan() {
    // Update price based on selected satuan
    if (dataHarga.value[selectedSatuan.value]) {
        productData.value.price = dataHarga.value[selectedSatuan.value].hargaRp;
    }
}

// Function to add to cart
function addToCart() {
    if (!selectedProduct.value || selectedQty.value <= 0) {
        return;
    }

    let harga = 0;
    let id_satuan = null;

    // If there are satuan options, use selected satuan
    if (satuanOptions.value.length > 0 && selectedSatuan.value) {
        harga = dataHarga.value[selectedSatuan.value]?.harga || 0;
        id_satuan = selectedSatuan.value;
    } else {
        // Use default product price
        harga = selectedProduct.value.price;
        id_satuan = productData.value?.id_satuan || null;
    }
    
    Inertia.post(
        "/addKeranjang",
        { 
            qty: selectedQty.value,
            product_id: selectedProduct.value.id,
            id_satuan: id_satuan,
            harga: harga
        },
        {
            onSuccess: () => {
                Toast.fire({
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan ke keranjang!',
                });
                closePopup();
            },
            onError: (errors) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menambahkan ke keranjang',
                });
            },
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
}
</script>

<template>
    <Head title="Home" />
    <Header :category_id="category_id" :totalKeranjang="totalKeranjang" :search="search" :smaSetting="smaSetting" />
    
    <!-- Search bar di bawah header -->
    <div class="w-full px-5 md:px-20 pt-5 bg-[#f0f0f0] flex justify-center">
        <div class="w-full relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-gray-400"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input
                type="text"
                v-model.lazy="searchLocal"
                placeholder="Cari Produk..."
                class="w-full pl-14 pr-4 py-3 rounded-2xl shadow-lg bg-white border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none text-lg transition-all duration-200 placeholder-gray-400"
            >
        </div>
    </div>
    
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

        <div class="grid grid-cols-2 md:grid-cols-5 grid-flow-row gap-4 gap-4">
            <div v-for="(item, index) in allProducts" :key="item.id" @click="openAddToCartPopup(item)" class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md cursor-pointer hover:shadow-lg transition-shadow duration-200">
                <div class="relative mx-3 mt-3 h-32 md:h-48 overflow-hidden rounded-xl bg-white bg-clip-border text-gray-700">
                    <img v-if="item.image1" :src="route('image', { 'jenis' : 'product1' , 'id' : item.id })" class="h-full w-full object-cover" />
                    <img v-else :src="tidakTersedia.href" />
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
        
        <!-- Loading indicator for infinite scroll -->
        <div v-if="isLoading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="ml-3 text-gray-600">Memuat produk...</span>
        </div>
        
        <!-- End of data indicator -->
        <div v-if="!hasMoreData && allProducts.length > 0" class="text-center py-8 text-gray-500">
            <p>Tidak ada produk lagi untuk ditampilkan</p>
        </div>
        
        <!-- Empty state -->
        <div v-if="!isLoading && allProducts.length === 0" class="text-center py-12">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada produk</h3>
                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian Anda.</p>
            </div>
        </div>
        
        <!-- Pagination hidden -->
        <!-- <div class="md:px-5">
            <pagination class="mt-10 w-full flex justify-center mt-5" :kata_kunci="search" :status_online="status_online" :links="product.links" />
        </div> -->
    </div>

    <!-- Popup Modal untuk Add to Cart -->
    <div v-if="showPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah ke Keranjang</h3>
                <button @click="closePopup" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-4" v-if="selectedProduct">
                <!-- Product Image -->
                <div class="flex justify-center mb-4">
                    <img 
                        v-if="selectedProduct.image1" 
                        :src="route('image', { 'jenis' : 'product1' , 'id' : selectedProduct.id })" 
                        class="w-32 h-32 object-cover rounded-lg"
                    />
                    <img v-else :src="tidakTersedia.href" class="w-32 h-32 object-cover rounded-lg" />
                </div>

                <!-- Product Info -->
                <div class="text-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ selectedProduct.name }}</h4>
                    <p v-if="productData && productData.brand_name" class="text-sm text-gray-600 mb-2">{{ productData.brand_name }} - {{ productData.category_name }}</p>
                    <p class="text-xl font-bold text-blue-600">{{ productData ? productData.price : 'Rp. ' + number_format(selectedProduct.price, 0, '.', '.') }}</p>
                </div>

                <!-- Satuan Selection -->
                <div class="mb-4" v-if="satuanOptions.length > 0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Satuan:</label>
                    <select 
                        v-model="selectedSatuan" 
                        @change="changeSatuan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option v-for="satuan in satuanOptions" :key="satuan.id" :value="satuan.id">
                            {{ satuan.name }}
                        </option>
                    </select>
                </div>

                <!-- Quantity Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                    <div class="flex items-center justify-center">
                        <button 
                            @click="kurangQty" 
                            class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 border rounded-l-lg w-10 h-10 transition-colors"
                        >
                            -
                        </button>
                        <input 
                            type="text" 
                            v-model="selectedQty" 
                            @change="changeQty"
                            class="w-20 h-10 border-t border-b border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button 
                            @click="tambahQty" 
                            class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 border rounded-r-lg w-10 h-10 transition-colors"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button 
                        @click="closePopup"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        @click="addToCart"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
    <BottomNav />
</template>

