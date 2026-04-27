<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';
import RegisterStockModal from '@/components/Product/RegisterStockModal.vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';

interface StockLedgerEntry {
    id: number;
    product_id: number;
    reason: string;
    quantity: number;
    created_at: string;
}

interface Props {
    product?: { id: number; name: string; price: number; stock_count: number | null };
    stockLedgerEntries?: StockLedgerEntry[];
}

const props = defineProps<Props>();

const page = usePage();

const isEditMode = computed(() => !!props.product);

const form = reactive({
    name: props.product?.name || '',
    price: props.product?.price?.toString() || '',
    stockCount: props.product?.stock_count?.toString() ?? '',
});

const isRegisterStockModalOpen = ref(false);

const onSubmit = (event: SubmitEvent) => {
    const target = event.target as HTMLFormElement;

    if (!target.checkValidity()) {
        target.reportValidity();

        return;
    }

    target.submit();
};

const resetFieldError = (field: string) => {
    if (page.props.errors) {
        delete page.props.errors[field];
    }
};

const formatDate = (isoDate: string): string => {
    return new Date(isoDate).toLocaleString();
};
</script>

<template>
    <Head :title="isEditMode ? 'Edit Product' : 'Create Product'" />

    <InternalBaseLayout :title="isEditMode ? 'Edit Product' : 'Create Product'">
        <form
            :action="
                isEditMode ? `/products/${props.product!.id}` : '/products'
            "
            method="post"
            class="max-w-md space-y-4"
            @submit.prevent="onSubmit"
        >
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-slate-700"
                >
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    name="name"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="Product name"
                    @input="resetFieldError('name')"
                    required
                />
                <p
                    v-if="page.props.errors?.name"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.name }}
                </p>
            </div>

            <div>
                <label
                    for="price"
                    class="block text-sm font-medium text-slate-700"
                >
                    Price
                </label>
                <input
                    id="price"
                    v-model="form.price"
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="0.00"
                    @input="resetFieldError('price')"
                    required
                />
                <p
                    v-if="page.props.errors?.price"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.price }}
                </p>
            </div>

            <div v-if="isEditMode">
                <label for="stock-count" class="block text-sm font-medium text-slate-700">
                    Current Stock
                </label>
                <input
                    id="stock-count"
                    :value="props.product!.stock_count ?? 0"
                    type="number"
                    class="mt-1 block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-500 outline-none"
                    readonly
                />
            </div>

            <div v-else>
                <label for="stock-count" class="block text-sm font-medium text-slate-700">
                    Initial Stock
                </label>
                <input
                    id="stock-count"
                    v-model="form.stockCount"
                    type="number"
                    name="stock_count"
                    min="0"
                    step="1"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="0"
                    @input="resetFieldError('stock_count')"
                />
                <p v-if="page.props.errors?.stock_count" class="mt-1 text-sm text-red-500">
                    {{ page.props.errors.stock_count }}
                </p>
            </div>

            <input v-if="isEditMode" type="hidden" name="_method" value="PUT" />

            <hr class="my-4 border-slate-200" />

            <div class="flex space-x-3">
                <button
                    type="button"
                    @click="router.visit('/products')"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:cursor-pointer hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                >
                    Save
                </button>
            </div>
        </form>

        <template v-if="isEditMode">
            <hr class="my-8 border-slate-200" />

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-800">Stock Ledger</h2>
                    <button
                        type="button"
                        @click="isRegisterStockModalOpen = true"
                        class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                    >
                        Register Stock
                    </button>
                </div>

                <div v-if="stockLedgerEntries && stockLedgerEntries.length > 0" class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">Date</th>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">Reason</th>
                                <th class="px-4 py-3 text-right font-medium text-slate-600">Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="entry in stockLedgerEntries" :key="entry.id" class="bg-white">
                                <td class="px-4 py-3 text-slate-600">{{ formatDate(entry.created_at) }}</td>
                                <td class="px-4 py-3 text-slate-800">{{ entry.reason }}</td>
                                <td
                                    class="px-4 py-3 text-right font-medium"
                                    :class="entry.quantity >= 0 ? 'text-emerald-600' : 'text-red-600'"
                                >
                                    {{ entry.quantity >= 0 ? '+' : '' }}{{ entry.quantity }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-else class="text-sm text-slate-500">No stock entries yet.</p>
            </div>

            <RegisterStockModal
                :is-open="isRegisterStockModalOpen"
                :product-id="props.product!.id"
                @close="isRegisterStockModalOpen = false"
            />
        </template>
    </InternalBaseLayout>
</template>
