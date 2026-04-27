<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';

interface SaleProduct {
    product_id: number;
    name: string;
    price: number;
    quantity: number;
}

interface SaleService {
    service_id: number;
    name: string;
    price: number;
}

interface Sale {
    id: number;
    client_id: number;
    client_name: string;
    created_at: string;
    updated_at: string;
    products: SaleProduct[];
    services: SaleService[];
    client_daily_purchase_number: number;
}

const props = defineProps<{
    sale: Sale;
}>();

const formattedDate = computed(() => {
    const date = new Date(props.sale.created_at);
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    const yyyy = date.getFullYear();
    const HH = String(date.getHours()).padStart(2, '0');
    const ii = String(date.getMinutes()).padStart(2, '0');
    const ss = String(date.getSeconds()).padStart(2, '0');

    return `${mm}/${dd}/${yyyy} ${HH}:${ii}:${ss}`;
});

const ordinalSuffix = (number: number): string => {
    const remainder100 = number % 100;
    const remainder10 = number % 10;

    if (remainder100 >= 11 && remainder100 <= 13) {
        return `${number}th`;
    }

    if (remainder10 === 1) {
return `${number}st`;
}

    if (remainder10 === 2) {
return `${number}nd`;
}

    if (remainder10 === 3) {
return `${number}rd`;
}

    return `${number}th`;
};

const dailyPurchaseLabel = computed(() =>
    `${ordinalSuffix(props.sale.client_daily_purchase_number)} purchase of the day`,
);

const productsTotal = computed(() =>
    props.sale.products.reduce((sum, product) => sum + product.price * product.quantity, 0),
);

const servicesTotal = computed(() => {
    console.log(props.sale.services);

    return props.sale.services.reduce((sum, service) => sum + service.price, 0);
});

console.log('Products Total:', productsTotal.value);
console.log('Services Total:', servicesTotal.value);

const total = computed(() => productsTotal.value + servicesTotal.value);

const formatCurrency = (value: number): string =>
    new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);

const goBack = () => router.visit('/sales');
</script>

<template>
    <Head :title="`Sale #${sale.id}`" />

    <InternalBaseLayout :title="`Sale #${sale.id}`">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <button
                    class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm text-slate-600 ring-1 ring-slate-200 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-400"
                    type="button"
                    @click="goBack"
                >
                    <i class="pi pi-arrow-left text-xs"></i>
                    Back to Sales
                </button>
            </div>

            <!-- Sale Info Card -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Sale ID</p>
                        <p class="mt-0.5 text-xl font-semibold text-slate-900">#{{ sale.id }}</p>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Client</p>
                        <p class="mt-0.5 text-base font-medium text-slate-900">{{ sale.client_name }}</p>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Date</p>
                        <p class="mt-0.5 text-base text-slate-900">{{ formattedDate }}</p>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800"
                        >
                            <i class="pi pi-calendar text-xs"></i>
                            {{ dailyPurchaseLabel }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div v-if="sale.products.length > 0">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Products</h2>
                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">Name</th>
                                <th class="px-4 py-3 text-right font-medium text-slate-600">Unit Price</th>
                                <th class="px-4 py-3 text-right font-medium text-slate-600">Qty</th>
                                <th class="px-4 py-3 text-right font-medium text-slate-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="product in sale.products"
                                :key="product.product_id"
                                class="hover:bg-slate-50"
                            >
                                <td class="px-4 py-3 text-slate-900">{{ product.name }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ formatCurrency(product.price) }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ product.quantity }}</td>
                                <td class="px-4 py-3 text-right font-medium text-slate-900">
                                    {{ formatCurrency(product.price * product.quantity) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-slate-700">
                                    Products Total
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">
                                    {{ formatCurrency(productsTotal) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Services Section -->
            <div v-if="sale.services.length > 0">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Services</h2>
                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">Name</th>
                                <th class="px-4 py-3 text-right font-medium text-slate-600">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="service in sale.services"
                                :key="service.service_id"
                                class="hover:bg-slate-50"
                            >
                                <td class="px-4 py-3 text-slate-900">{{ service.name }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ formatCurrency(service.price) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-slate-700">
                                    Services Total
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">
                                    {{ formatCurrency(servicesTotal) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Grand Total -->
            <div class="flex justify-end">
                <div class="rounded-lg border border-slate-200 bg-slate-900 px-6 py-4 text-white">
                    <div class="flex items-center gap-8">
                        <span class="text-sm font-medium text-slate-300">Total</span>
                        <span class="text-xl font-bold">{{ formatCurrency(total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </InternalBaseLayout>
</template>
