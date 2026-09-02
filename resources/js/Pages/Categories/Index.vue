<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Category {
    id: number;
    name: string;
    type: string;
    is_active: boolean;
}

const props = defineProps<{
    categories: Category[];
}>();

const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    type: 'income',
});

function startEdit(category: Category) {
    editingId.value = category.id;
    form.name = category.name;
    form.type = category.type;
}

function cancelEdit() {
    editingId.value = null;
    form.reset();
}

function submit() {
    if (editingId.value === null) {
        form.post(route('categories.store'), {
            onSuccess: () => form.reset(),
        });
    } else {
        form.put(route('categories.update', editingId.value), {
            onSuccess: () => cancelEdit(),
        });
    }
}

function destroy(id: number) {
    if (confirm('Tem certeza que deseja excluir esta conta?')) {
        useForm({}).delete(route('categories.destroy', id));
    }
}
</script>

<template>
    <Head title="Categorias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Categorias
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

                <!-- Formulário de criação/edição -->
                <div class="mb-6 bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-medium">
                        {{ editingId === null ? 'Nova categoria' : 'Editar categoria' }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            />
                            <p v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select v-model="form.type" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="income">Receita</option>
                                <option value="expense">Despesa</option>
                            </select>
                            <p v-if="form.errors.type" class="text-sm text-red-600">
                                {{ form.errors.type }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-md bg-gray-800 px-4 py-2 text-white"
                            >
                                {{ editingId === null ? 'Criar' : 'Salvar' }}
                            </button>
                            <button
                                v-if="editingId !== null"
                                type="button"
                                @click="cancelEdit"
                                class="rounded-md bg-gray-200 px-4 py-2"
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de contas -->
                <div class="bg-white shadow sm:rounded-lg">
                    <table class="w-full text-left">
                        <thead class="border-b bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nome</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in props.categories" :key="category.id" class="border-b">
                                <td class="px-6 py-4">{{ category.name }}</td>
                                <td class="px-6 py-4">{{ category.type }}</td>
                                <td class="px-6 py-4">
                                    <button @click="startEdit(category)" class="mr-3 text-blue-600">
                                        Editar
                                    </button>
                                    <button @click="destroy(category.id)" class="text-red-600">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>