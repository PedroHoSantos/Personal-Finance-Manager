<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Account {
    id: number;
    name: string;
    type: string;
    initial_balance: string;
    is_active: boolean;
}

const props = defineProps<{
    accounts: Account[];
}>();

const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    type: 'CHECKING',
    initial_balance: 0,
});

function startEdit(account: Account) {
    editingId.value = account.id;
    form.name = account.name;
    form.type = account.type;
    form.initial_balance = Number(account.initial_balance);
}

function cancelEdit() {
    editingId.value = null;
    form.reset();
}

function submit() {
    if (editingId.value === null) {
        form.post(route('accounts.store'), {
            onSuccess: () => form.reset(),
        });
    } else {
        form.put(route('accounts.update', editingId.value), {
            onSuccess: () => cancelEdit(),
        });
    }
}

function destroy(id: number) {
    if (confirm('Tem certeza que deseja excluir esta conta?')) {
        useForm({}).delete(route('accounts.destroy', id));
    }
}
</script>

<template>
    <Head title="Contas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Contas
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

                <!-- Formulário de criação/edição -->
                <div class="mb-6 bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-medium">
                        {{ editingId === null ? 'Nova conta' : 'Editar conta' }}
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
                                <option value="CHECKING">Corrente</option>
                                <option value="SAVINGS">Poupança</option>
                                <option value="CASH">Dinheiro</option>
                            </select>
                            <p v-if="form.errors.type" class="text-sm text-red-600">
                                {{ form.errors.type }}
                            </p>
                        </div>

                        <div v-if="editingId === null">
                            <label class="block text-sm font-medium text-gray-700">Saldo inicial</label>
                            <input
                                v-model.number="form.initial_balance"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            />
                            <p v-if="form.errors.initial_balance" class="text-sm text-red-600">
                                {{ form.errors.initial_balance }}
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
                                <th class="px-6 py-3">Saldo inicial</th>
                                <th class="px-6 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="account in props.accounts" :key="account.id" class="border-b">
                                <td class="px-6 py-4">{{ account.name }}</td>
                                <td class="px-6 py-4">{{ account.type }}</td>
                                <td class="px-6 py-4">{{ account.initial_balance }}</td>
                                <td class="px-6 py-4">
                                    <button @click="startEdit(account)" class="mr-3 text-blue-600">
                                        Editar
                                    </button>
                                    <button @click="destroy(account.id)" class="text-red-600">
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