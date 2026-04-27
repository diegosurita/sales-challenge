<script setup lang="ts">
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';

interface ReasonOption {
    value: string;
    label: string;
}

const props = defineProps<{
    isOpen: boolean;
    productId: number;
}>();

const emit = defineEmits<{
    close: [];
}>();

const page = usePage();

const reasonOptions: ReasonOption[] = [
    { value: 'sale', label: 'Sale' },
    { value: 'correction', label: 'Correction' },
    { value: 'purchase_received', label: 'Purchase Received' },
];

const selectedReason = ref<ReasonOption | null>(null);
const reasonQuery = ref('');
const reasonError = ref('');

const filteredReasons = computed<ReasonOption[]>(() => {
    if (reasonQuery.value === '') {
        return reasonOptions;
    }

    return reasonOptions.filter((option) =>
        option.label.toLowerCase().includes(reasonQuery.value.toLowerCase()),
    );
});

const form = reactive({
    quantity: '',
});

const resetForm = () => {
    selectedReason.value = null;
    reasonQuery.value = '';
    reasonError.value = '';
    form.quantity = '';
};

const resetFieldError = (field: string) => {
    if (page.props.errors) {
        delete page.props.errors[field];
    }
};

watch(
    () => props.isOpen,
    (isOpen) => {
        if (!isOpen) {
            resetForm();
        }
    },
);

const onClose = () => {
    emit('close');
};

const getDisplayValue = (option: unknown): string =>
    (option as ReasonOption | null)?.label ?? '';

const onSubmit = (event: SubmitEvent) => {
    reasonError.value = '';

    if (!selectedReason.value) {
        reasonError.value = 'Please select a reason.';

        return;
    }

    const target = event.target as HTMLFormElement;

    if (!target.checkValidity()) {
        target.reportValidity();

        return;
    }

    router.post(
        `/products/${props.productId}/stock-ledger`,
        {
            reason: selectedReason.value.value,
            quantity: parseInt(form.quantity, 10),
        },
        {
            onSuccess: () => {
                emit('close');
            },
        },
    );
};
</script>

<template>
    <Dialog :open="isOpen" @close="onClose" class="relative z-50">
        <div class="fixed inset-0 bg-black/30" aria-hidden="true" />

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <DialogPanel class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <DialogTitle class="text-lg font-semibold text-slate-900">
                    Register Stock
                </DialogTitle>

                <form class="mt-4 space-y-4" @submit.prevent="onSubmit">
                    <div>
                        <label for="stock-reason" class="block text-sm font-medium text-slate-700">
                            Reason
                        </label>
                        <Combobox v-model="selectedReason" @update:modelValue="reasonError = ''">
                            <div class="relative mt-1">
                                <ComboboxInput
                                    id="stock-reason"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pr-10 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                    :display-value="getDisplayValue"
                                    placeholder="Select a reason"
                                    @change="reasonQuery = ($event.target as HTMLInputElement).value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="pi pi-angle-down text-slate-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 w-full mt-1 max-h-60 overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg focus:outline-none"
                                >
                                    <ComboboxOption
                                        v-for="option in filteredReasons"
                                        :key="option.value"
                                        :value="option"
                                        as="template"
                                        v-slot="{ active, selected }"
                                    >
                                        <li
                                            :class="[
                                                'cursor-pointer px-3 py-2 text-sm select-none',
                                                active ? 'bg-sky-50 text-sky-900' : 'text-slate-900',
                                            ]"
                                        >
                                            <span :class="selected ? 'font-semibold' : 'font-normal'">
                                                {{ option.label }}
                                            </span>
                                        </li>
                                    </ComboboxOption>
                                    <li
                                        v-if="filteredReasons.length === 0"
                                        class="px-3 py-2 text-sm text-slate-500"
                                    >
                                        No options found.
                                    </li>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <p v-if="reasonError" class="mt-1 text-sm text-red-500">
                            {{ reasonError }}
                        </p>
                        <p v-if="page.props.errors?.reason" class="mt-1 text-sm text-red-500">
                            {{ page.props.errors.reason }}
                        </p>
                    </div>

                    <div>
                        <label for="stock-quantity" class="block text-sm font-medium text-slate-700">
                            Quantity
                            <span class="ml-1 font-normal text-slate-500">(use negative to remove stock)</span>
                        </label>
                        <input
                            id="stock-quantity"
                            v-model="form.quantity"
                            type="number"
                            name="quantity"
                            step="1"
                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                            placeholder="e.g. 50 or -10"
                            @input="resetFieldError('quantity')"
                            required
                        />
                        <p v-if="page.props.errors?.quantity" class="mt-1 text-sm text-red-500">
                            {{ page.props.errors.quantity }}
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2">
                        <button
                            type="button"
                            @click="onClose"
                            class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:cursor-pointer hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                        >
                            Register
                        </button>
                    </div>
                </form>
            </DialogPanel>
        </div>
    </Dialog>
</template>
