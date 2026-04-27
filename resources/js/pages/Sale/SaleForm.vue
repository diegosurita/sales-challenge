<script setup lang="ts">
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';

interface Client {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    price: number;
    stock_count: number;
}

interface Service {
    id: number;
    name: string;
    price: number;
    available: boolean;
    product?: { id: number; name: string } | null;
}

const props = defineProps<{
    clients: Client[];
    products: Product[];
    services: Service[];
}>();

const form = useForm({
    client_id: null as number | null,
    products: [] as { product_id: number }[],
    services: [] as { service_id: number }[],
});

const selectedClient = ref<Client | null>(null);
const selectedProduct = ref<Product | null>(null);
const selectedService = ref<Service | null>(null);
const serviceProductError = ref<string | null>(null);

watch(selectedClient, (client) => {
    form.client_id = client?.id ?? null;
});

const clientQuery = ref('');
const productQuery = ref('');
const serviceQuery = ref('');

watch(serviceQuery, () => {
    serviceProductError.value = null;
});

const addedProductIds = computed(() => new Set(form.products.map((product) => product.product_id)));
const addedServiceIds = computed(() => new Set(form.services.map((service) => service.service_id)));

const availableProducts = computed(() => props.products.filter((product) => !addedProductIds.value.has(product.id)));
const availableServices = computed(() => props.services.filter((service) => !addedServiceIds.value.has(service.id)));

const filteredClients = computed(() =>
    clientQuery.value === ''
        ? props.clients
        : props.clients.filter((c) => c.name.toLowerCase().includes(clientQuery.value.toLowerCase())),
);

const filteredAvailableProducts = computed(() =>
    productQuery.value === ''
        ? availableProducts.value
        : availableProducts.value.filter((product) => product.name.toLowerCase().includes(productQuery.value.toLowerCase())),
);

const filteredAvailableServices = computed(() =>
    serviceQuery.value === ''
        ? availableServices.value
        : availableServices.value.filter((service) => service.name.toLowerCase().includes(serviceQuery.value.toLowerCase())),
);

const addedProducts = computed(() =>
    form.products
        .map((fp) => props.products.find((p) => p.id === fp.product_id))
        .filter((product): product is Product => product !== undefined),
);

const addedServices = computed(() =>
    form.services
        .map((fs) => props.services.find((s) => s.id === fs.service_id))
        .filter((service): service is Service => service !== undefined),
);

const addProduct = () => {
    if (selectedProduct.value === null) {
        return;
    }

    if (addedProductIds.value.has(selectedProduct.value.id)) {
        return;
    }

    form.products.push({ product_id: selectedProduct.value.id });
    selectedProduct.value = null;
    productQuery.value = '';
};

const removeProduct = (productId: number) => {
    form.products = form.products.filter((product) => product.product_id !== productId);
};

const addService = () => {
    if (selectedService.value === null) {
        return;
    }

    if (addedServiceIds.value.has(selectedService.value.id)) {
        return;
    }

    serviceProductError.value = null;

    const requiredProduct = selectedService.value.product ?? null;

    if (requiredProduct !== null && !addedProductIds.value.has(requiredProduct.id)) {
        const inStock = props.products.find((product) => product.id === requiredProduct.id);

        if (!inStock) {
            serviceProductError.value = `Service "${selectedService.value.name}" requires "${requiredProduct.name}" which is out of stock.`;

            return;
        }

        form.products.push({ product_id: requiredProduct.id });
    }

    form.services.push({ service_id: selectedService.value.id });
    selectedService.value = null;
    serviceQuery.value = '';
};

const removeService = (serviceId: number) => {
    form.services = form.services.filter((service) => service.service_id !== serviceId);
};

const productSubtotal = computed(() =>
    addedProducts.value.reduce((sum, product) => sum + product.price, 0),
);

const serviceSubtotal = computed(() =>
    addedServices.value.reduce((sum, service) => sum + service.price, 0),
);

const total = computed(() => productSubtotal.value + serviceSubtotal.value);

const formatCurrency = (value: number) => `$${value.toFixed(2)}`;

const submit = () => {
    form.post('/sales');
};
</script>

<template>
    <Head title="Create Sale" />

    <InternalBaseLayout title="Create Sale">
        <form class="space-y-6" @submit.prevent="submit">
            <!-- Client -->
            <div>
                <label for="client_combobox" class="block text-sm font-medium text-slate-700">
                    Client <span class="text-red-500">*</span>
                </label>
                <div class="relative mt-1">
                    <Combobox v-model="selectedClient" nullable by="id">
                        <div class="relative">
                            <ComboboxInput
                                id="client_combobox"
                                class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-10 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                :display-value="(client) => (client as Client | null)?.name ?? ''"
                                placeholder="Select a client"
                                @change="clientQuery = ($event.target as HTMLInputElement).value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="pi pi-angle-down text-slate-400" />
                            </ComboboxButton>
                        </div>
                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                            <ComboboxOption
                                v-for="client in filteredClients"
                                :key="client.id"
                                :value="client"
                                as="template"
                                v-slot="{ active, selected }"
                            >
                                <li
                                    :class="[
                                        'cursor-pointer select-none px-4 py-2 text-sm',
                                        active ? 'bg-sky-50 text-sky-900' : 'text-slate-900',
                                    ]"
                                >
                                    <span :class="selected ? 'font-medium' : 'font-normal'">{{ client.name }}</span>
                                </li>
                            </ComboboxOption>
                            <li v-if="filteredClients.length === 0" class="px-4 py-2 text-sm text-slate-500">
                                No clients found.
                            </li>
                        </ComboboxOptions>
                    </Combobox>
                </div>
            </div>

            <!-- Products -->
            <div>
                <h3 class="mb-2 text-sm font-medium text-slate-700">Products</h3>

                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <Combobox v-model="selectedProduct" nullable by="id">
                            <div class="relative">
                                <ComboboxInput
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-10 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                    :display-value="(product) => (product as Product | null)?.name ?? ''"
                                    placeholder="Select a product to add"
                                    @change="productQuery = ($event.target as HTMLInputElement).value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="pi pi-angle-down text-slate-400" />
                                </ComboboxButton>
                            </div>
                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                <ComboboxOption
                                    v-for="product in filteredAvailableProducts"
                                    :key="product.id"
                                    :value="product"
                                    as="template"
                                    v-slot="{ active, selected }"
                                >
                                    <li
                                        :class="[
                                            'cursor-pointer select-none px-4 py-2 text-sm',
                                            active ? 'bg-sky-50 text-sky-900' : 'text-slate-900',
                                        ]"
                                    >
                                        <span :class="selected ? 'font-medium' : 'font-normal'">
                                            {{ product.name }} — {{ formatCurrency(product.price) }}
                                        </span>
                                    </li>
                                </ComboboxOption>
                                <li v-if="filteredAvailableProducts.length === 0" class="px-4 py-2 text-sm text-slate-500">
                                    No products available.
                                </li>
                            </ComboboxOptions>
                        </Combobox>
                    </div>
                    <button
                        type="button"
                        :disabled="selectedProduct === null"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 hover:cursor-pointer"
                        @click="addProduct"
                    >
                        Add
                    </button>
                </div>

                <div v-if="addedProducts.length > 0" class="mt-3 overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-slate-600">Name</th>
                                <th class="px-4 py-2 text-right font-medium text-slate-600">Price</th>
                                <th class="px-4 py-2 text-center font-medium text-slate-600">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="product in addedProducts" :key="product.id">
                                <td class="px-4 py-2 text-slate-900">{{ product.name }}</td>
                                <td class="px-4 py-2 text-right text-slate-700">
                                    {{ formatCurrency(product.price) }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded p-1 text-red-600 hover:cursor-pointer hover:bg-red-100 hover:text-red-900 focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        title="Remove product"
                                        @click="removeProduct(product.id)"
                                    >
                                        <i class="pi pi-trash" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Services -->
            <div>
                <h3 class="mb-2 text-sm font-medium text-slate-700">Services</h3>

                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <Combobox v-model="selectedService" nullable by="id">
                            <div class="relative">
                                <ComboboxInput
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-10 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                    :display-value="(service) => (service as Service | null)?.name ?? ''"
                                    placeholder="Select a service to add"
                                    @change="serviceQuery = ($event.target as HTMLInputElement).value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="pi pi-angle-down text-slate-400" />
                                </ComboboxButton>
                            </div>
                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                <ComboboxOption
                                    v-for="service in filteredAvailableServices"
                                    :key="service.id"
                                    :value="service"
                                    as="template"
                                    v-slot="{ active, selected }"
                                >
                                    <li
                                        :class="[
                                            'cursor-pointer select-none px-4 py-2 text-sm',
                                            active ? 'bg-sky-50 text-sky-900' : 'text-slate-900',
                                        ]"
                                    >
                                        <span :class="selected ? 'font-medium' : 'font-normal'">
                                            {{ service.name }} — {{ formatCurrency(service.price) }}
                                        </span>
                                    </li>
                                </ComboboxOption>
                                <li v-if="filteredAvailableServices.length === 0" class="px-4 py-2 text-sm text-slate-500">
                                    No services available.
                                </li>
                            </ComboboxOptions>
                        </Combobox>
                    </div>
                    <button
                        type="button"
                        :disabled="selectedService === null"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 hover:cursor-pointer"
                        @click="addService"
                    >
                        Add
                    </button>
                </div>

                <p v-if="serviceProductError" class="mt-2 text-sm text-red-600">
                    {{ serviceProductError }}
                </p>

                <div v-if="addedServices.length > 0" class="mt-3 overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-slate-600">Name</th>
                                <th class="px-4 py-2 text-right font-medium text-slate-600">Price</th>
                                <th class="px-4 py-2 text-center font-medium text-slate-600">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="service in addedServices" :key="service.id">
                                <td class="px-4 py-2 text-slate-900">{{ service.name }}</td>
                                <td class="px-4 py-2 text-right text-slate-700">
                                    {{ formatCurrency(service.price) }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded p-1 text-red-600 hover:cursor-pointer hover:bg-red-100 hover:text-red-900 focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        title="Remove service"
                                        @click="removeService(service.id)"
                                    >
                                        <i class="pi pi-trash" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sale Summary -->
            <div
                v-if="addedProducts.length > 0 || addedServices.length > 0"
                class="rounded-lg border border-slate-200 bg-slate-50 p-4"
            >
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Sale Summary</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Products</span>
                        <span>{{ formatCurrency(productSubtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Services</span>
                        <span>{{ formatCurrency(serviceSubtotal) }}</span>
                    </div>
                    <div class="mt-2 flex justify-between border-t border-slate-300 pt-2 font-semibold text-slate-900">
                        <span>Total</span>
                        <span>{{ formatCurrency(total) }}</span>
                    </div>
                </div>
            </div>

            <!-- Validation Errors -->
            <div
                v-if="form.errors.client_id || form.errors.products || form.errors.services || (form.errors as Record<string, string>).items"
                class="rounded-lg border border-red-200 bg-red-50 p-4"
            >
                <ul class="space-y-1 text-sm text-red-600">
                    <li v-if="form.errors.client_id">{{ form.errors.client_id }}</li>
                    <li v-if="form.errors.products">{{ form.errors.products }}</li>
                    <li v-if="form.errors.services">{{ form.errors.services }}</li>
                    <li v-if="(form.errors as Record<string, string>).items">{{ (form.errors as Record<string, string>).items }}</li>
                </ul>
            </div>

            <hr class="border-slate-200" />

            <!-- Actions -->
            <div class="flex space-x-3">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:cursor-pointer hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                    @click="router.visit('/sales')"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Save
                </button>
            </div>
        </form>
    </InternalBaseLayout>
</template>
