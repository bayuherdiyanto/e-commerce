<script setup>
    defineProps({
        links: Array,
        kata_kunci: String,
        status_online: String
    });
    import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 bg-white px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />
                <!-- :only="['userLdap']" -->
                <!-- preserve-state  -->
                <Link as="button" v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white"
                    :class="(link.active) ? 'bg-blue-700 text-white': 'bg-white'" :href="link.url+'&search='+kata_kunci+'&status_online='+status_online" v-html="link.label" preserve-scroll ></Link>
            </template>
        </div>
    </div>
</template>
