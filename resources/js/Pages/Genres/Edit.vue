<template>
    <AdminLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Genre Edit
            </h2>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full flex mb-4 p-2">
                        <Link :href="route('admin.genres.index')"
                                    class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Genre Index</Link>
                    </div>

    <div class="w-full mb-8 sm:max-w-md p-6 overflow-hidden rounded-lg shadow-lg bg-white">
        <form @submit.prevent="submitGenre">
            <div>
                <JetLabel for="title" value="Title" />
                <JetInput
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="title"
                />
                <JetInputError class="mt-2" :message="form.errors.title" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update
                </JetButton>
            </div>
        </form>
    </div>
</section>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref, watch, defineProps } from "vue";
import JetButton from '@/Components/Button.vue';
import JetInput from '@/Components/Input.vue';
import JetInputError from '@/Components/InputError.vue';
import JetCheckbox from '@/Components/Checkbox.vue';
import JetLabel from '@/Components/Label.vue';

const props = defineProps({
    genre: Object,
});

const form = useForm({
    title: props.genre.title,
});

function submitGenre() {
    form.put("/admin/genres/" + props.genre.id);
}
</script>

<style>

</style>