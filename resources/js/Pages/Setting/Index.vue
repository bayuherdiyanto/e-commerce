<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, router } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    gudang: String,
    setting: String
});

const form = useForm({
    gudang: props.setting.gudang,
    no_wa: props.setting.no_wa
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


function saveSetting(){
    form.post(route('storeSetting'), {
        onFinish: () => {
            Toast.fire({
                icon: 'success',
                title: 'Success',
            })
            
        },
        preserveState: true,
        preserveScroll: true,
    });
}


let search = ref(props.search);
let status_online = ref(props.status_online);

</script>

<template>
    <Head>
        <title>Setting</title>
    </Head>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Setting</h2>
        </template>
        
        <div class="py-12">
            <div class="md:flex px-6 lg:px-8 mb-4 gap-5">
                <div class="w-full md:w-1/5 mb-5">
                    <div>No Telepon</div>
                    <div class="flex bg-white rounded-lg pl-3 py-1" style="border-style: solid;border-color: #e5e7eb;">
                        <input type="text" v-model="form.no_wa" placeholder="No Telepon" class="w-full rounded-lg h-8 pt-0 pb-0 pl-2 focus:outline-0 searchbar-input border-none focus:ring-0">
                    </div>
                </div>

                <div class="w-full md:w-1/5 mb-5">
                    <div>Gudang</div>
                    <div>
                        <select v-model="form.gudang" class="w-full h-10 border-[0px] pl-2">
                            <option value=""> -- Pilih Gudang -- </option>
                            <option v-for="(item, index) in gudang" :key="item.id" :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/5 mb-5 pt-5">
                    <button @click="saveSetting" class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">
                        SIMPAN
                    </button>
                </div>
            </div>
            
            
        </div>
        
    </BreezeAuthenticatedLayout>
</template>
