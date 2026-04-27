<script setup lang="ts">
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';

interface Product {
    id: number;
    name: string;
}

interface Props {
    service?: {
        id: number;
        name: string;
        price: number;
        available: boolean;
        product: { id: number; name: string } | null;
    };
    products: Product[];
}

const props = defineProps<Props>();

const page = usePage();

const isEditMode = computed(() => !!props.service);

const form = reactive<{
    name: string;
    price: string;
    available: boolean;
    productId: number | null;
}>({
    name: props.service?.name ?? '',
    price: props.service?.price?.toString() ?? '',
    available: props.service?.available ?? true,
    productId: props.service?.product?.id ?? null,
});

const selectedProduct = ref<Product | null>(props.service?.product ?? null);

watch(selectedProduct, (product) => {
    form.productId = product?.id ?? null;
});

const query = ref('');

const filteredProducts = computed<Product[]>(() => {
    if (query.value === '') {
        return props.products;
    }

    return props.products.filter((p) =>
        p.name.toLowerCase().includes(query.value.toLowerCase()),
    );
});

const getDisplayValue = (product: Product | null): string =>
    product?.name ?? '';

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
</script>

<template>
    <Head :title="isEditMode ? 'Edit Service' : 'Create Service'" />

    <InternalBaseLayout :title="isEditMode ? 'Edit Service' : 'Create Service'">
        <form
            :action="
                isEditMode ? `/services/${props.service!.id}` : '/services'
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
                    placeholder="Service name"
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

            <div>
                <label for="available" class="flex items-center">
                    <input
                        type="hidden"
                        name="available"
                        :value="form.available ? '1' : '0'"
                    />
                    <input
                        id="available"
                        v-model="form.available"
                        type="checkbox"
                        class="mr-2 h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                        @change="resetFieldError('available')"
                    />
                    <span class="text-sm font-medium text-slate-700"
                        >Available</span
                    >
                </label>
                <p
                    v-if="page.props.errors?.available"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.available }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Dependent On Product
                </label>
                <input
                    type="hidden"
                    name="product_id"
                    :value="form.productId ?? ''"
                />
                <Combobox v-model="selectedProduct" nullable>
                    <div class="relative mt-1">
                        <ComboboxInput
                            :display-value="
                                (item: unknown) =>
                                    getDisplayValue(item as Product | null)
                            "
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                            placeholder="Search product..."
                            @change="
                                query = ($event.target as HTMLInputElement)
                                    .value
                            "
                            @focus="query = ''"
                        />
                        <ComboboxButton
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <svg
                                class="h-4 w-4 text-slate-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4"
                                />
                            </svg>
                        </ComboboxButton>
                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg focus:outline-none"
                        >
                            <ComboboxOption
                                :value="null"
                                v-slot="{ active, selected }"
                            >
                                <li
                                    :class="[
                                        'cursor-pointer px-3 py-2 text-sm italic',
                                        active
                                            ? 'bg-sky-600 text-white'
                                            : 'text-slate-400',
                                        selected
                                            ? 'font-semibold'
                                            : 'font-normal',
                                    ]"
                                >
                                    None
                                </li>
                            </ComboboxOption>
                            <ComboboxOption
                                v-for="product in filteredProducts"
                                :key="product.id"
                                :value="product"
                                v-slot="{ active, selected }"
                            >
                                <li
                                    :class="[
                                        'cursor-pointer px-3 py-2 text-sm',
                                        active
                                            ? 'bg-sky-600 text-white'
                                            : 'text-slate-900',
                                        selected
                                            ? 'font-semibold'
                                            : 'font-normal',
                                    ]"
                                >
                                    {{ product.name }}
                                </li>
                            </ComboboxOption>
                            <li
                                v-if="query && filteredProducts.length === 0"
                                class="px-3 py-2 text-sm text-slate-500"
                            >
                                No products found.
                            </li>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <p
                    v-if="page.props.errors?.product_id"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.product_id }}
                </p>
            </div>

            <input v-if="isEditMode" type="hidden" name="_method" value="PUT" />

            <hr class="my-4 border-slate-200" />

            <div class="flex space-x-3">
                <button
                    type="button"
                    @click="router.visit('/services')"
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
    </InternalBaseLayout>
</template>
