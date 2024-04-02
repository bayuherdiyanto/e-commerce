<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, router } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    product: Object,
    search: String,
    status_online: String,
    status_atas_value: Object,
    status_online_value: Object,
    status_atas_value: Object,
    page:String
});

const form = useForm({
    id: '',
    image1: '',
    image2: '',
    image3: '',
    image4: '',
    image_name1: '',
    image_name2: '',
    image_name3: '',
    image_name4: '',
    status_online: props.status_online,
    search: props.search,
    page:props.page,
    status_online_value : props.status_online_value,
    status_atas_value : props.status_atas_value
});

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


function changeStatusOnline(id){
    Inertia.post(
        "/changeStatusOnline",
        { 
            id: id,
            status_online: props.status_online,
            search: props.search,
            page:props.page
        },
        {
            onFinish: () => {
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                })
            },
            preserveState: false,
        }
    );
}


function changeStatusAtas(id){
    Inertia.post(
        "/changeStatusAtas",
        { 
            id: id,
            status_online: props.status_online,
            search: props.search,
            page:props.page
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


function saveImage(){
    form.post(route('saveProductImage'), {
        onFinish: () => {
            Swal.fire({
              icon: "success",
              title: "Success",
              text: "Data Berhasil Disimpan !",
            });
            form.reset('id', 'image1', 'image2', 'image3', 'image4','image_name1','image_name2','image_name3','image_name4');
            modalStatus.value = '0';
        },
    });
}

let search = ref(props.search);
let status_online = ref(props.status_online);
watch([search, status_online], (value) => {

Inertia.get(
    "/product",
    { 
        search: value[0],
        status_online: value[1]
    },
    {
        preserveState: false,
    }
  );
});
const modalStatus = ref('0');

function deleteContent(id, nama){
    Swal.fire({
        title: 'Yakin hapus konten '+nama+' ?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        denyButtonText: `Tidak`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            router.post(
                "/content/delete",
                { id: id },
                {
                    preserveState: true,

                    onSuccess: () => {
                        Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Data Berhasil Dihapus !",
                        });
                    }
                }
            );
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
        }
    });
}
</script>

<template>
    <Head>
        <title>Content</title>
        <link rel="icon" type="image/svg+xml" :href="logo" />
    </Head>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">List Produk</h2>
        </template>
        
        <div class="py-12">
            <div class="md:flex px-6 lg:px-8 mb-4 gap-5">
                <div class="w-full md:w-1/5 mb-5">
                    <div>Cari Produk</div>
                    <div class="flex bg-white rounded-lg pl-3 py-1" style="border-style: solid;border-color: #e5e7eb;">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-black mt-1"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" v-model.lazy="search" placeholder="Cari Produk" class="w-full rounded-lg h-8 pt-0 pb-0 pl-2 focus:outline-0 searchbar-input border-none focus:ring-0">
                    </div>
                </div>

                <div class="w-full md:w-1/5 mb-5">
                    <div>Status Online</div>
                    <div>
                        <select v-model="status_online" class="w-full h-10 border-[0px] pl-2">
                            <option value="All"> -- Semua Status -- </option>
                            <option value="1">Online</option>
                            <option value="0">Tidak Online</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mx-auto px-6 lg:px-8 space-y-6">
                <div class="mb-3 overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative" style="height: 405px;">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead class="border-b font-medium dark:border-neutral-500 sticky top-0  z-20">
                            <tr class="bg-[#179A3C] text-white">
                                <th scope="col" class="border-r px-4 py-1">No</th>
                                <th scope="col" class="border-r px-4 py-1">IMG</th>
                                <th scope="col" class="border-r px-4 py-1">Kode</th>
                                <th scope="col" class="border-r px-4 py-1">Nama</th>
                                <th scope="col" class="border-r px-4 py-1">Brand</th>
                                <th scope="col" class="border-r px-4 py-1">Kategori</th>
                                <th scope="col" class="border-r px-4 py-1">Status Online</th>
                                <th scope="col" class="border-r px-4 py-1">Atas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b " v-for="(item, index) in product.data" :key="item.id">
                                <td class="border-r px-3 py-1 font-medium ">
                                    {{ product.from+index }}
                                </td>
                                <td class="border-r px-3 py-1 ">
                                    <div @click="modalStatus='1', form.id=item.id, form.image_name1 = item.image1, form.image_name2 = item.image2, form.image_name3 = item.image3, form.image_name4 = item.image4" class="flex justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="border-r px-3 py-1 ">
                                    {{ item.code }}
                                </td>
                                <td class="border-r px-3 py-1 ">
                                    {{ item.name }}
                                </td>
                                <td class="border-r px-3 py-1 ">
                                    {{ item.brand_name }}
                                </td>
                                <td class="border-r px-3 py-1 ">
                                    {{ item.category_name }}
                                </td>
                                <td class="border-r px-3 py-1 text-center ">
                                    <div v-if="item.online_category=='1'">
                                       
                                        <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            @change="changeStatusOnline(item.id)"
                                            type="checkbox"
                                            v-model="form.status_online_value"
                                            :value="item.id"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                                        ></div>
                                        </label>
                                    </div>
                                </td>
                                <td class="border-r px-3 py-1 text-center ">
                                    <div v-if="item.online_category=='1'">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            @change="changeStatusAtas(item.id)"
                                            type="checkbox"
                                            v-model="form.status_atas_value"
                                            :value="item.id"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                                        ></div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                <div class="md:px-5">
                    <pagination class="mt-10 w-full flex justify-center mt-5" :kata_kunci="search" :status_online="status_online" :links="product.links" />
                </div>
            </div>
        </div>
        <div :class="(modalStatus=='1' ? '' : 'opacity-0 pointer-events-none')" class="modal z-30 fixed w-full h-full top-0 left-0 flex items-center justify-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            
                <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                
                

                <!-- Add margin if you want to see some of the overlay behind the modal-->
                <div class="modal-content py-4 text-left px-6 overflow-auto h-[500px]">
                    <!--Title-->
                    <div class="flex justify-between items-center pb-3">
                        <p class="text-2xl font-bold">Gambar</p>
                        <div @click="modalStatus='0'" class="modal-close cursor-pointer z-50">
                            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div>Gambar 1</div>
                        <div>
                            <input @input="form.image1 = $event.target.files[0]" v-on-change="form.image1" type="file">
                        </div>
                        <div v-if="form.image_name1">
                            <img class="w-full" :src="route('image', { 'jenis' : 'product1' , 'id' : form.id })" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <div>Gambar 2</div>
                        <div>
                            <input @input="form.image2 = $event.target.files[0]" type="file">
                        </div>
                        <div v-if="form.image_name2">
                            <img class="w-full" :src="route('image', { 'jenis' : 'product2' , 'id' : form.id })" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <div>Gambar 3</div>
                        <div>
                            <input @input="form.image3 = $event.target.files[0]" type="file">
                        </div>
                        <div v-if="form.image_name3">
                            <img class="w-full" :src="route('image', { 'jenis' : 'product3' , 'id' : form.id })" />
                        </div>
                    </div>
                    <div>
                        <div>Gambar 4</div>
                        <div>
                            <input @input="form.image4 = $event.target.files[0]" type="file">
                        </div>
                        <div v-if="form.image_name4">
                            <img class="w-full" :src="route('image', { 'jenis' : 'product4' , 'id' : form.id })" />
                        </div>
                    </div>
                    

                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                    <button @click="modalStatus='0'" class="px-4 bg-transparent p-3 rounded-lg text-indigo-500 hover:bg-gray-100 hover:text-indigo-400 mr-2">
                        TUTUP
                    </button>
                    <button @click="saveImage" class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">
                        SIMPAN
                    </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
