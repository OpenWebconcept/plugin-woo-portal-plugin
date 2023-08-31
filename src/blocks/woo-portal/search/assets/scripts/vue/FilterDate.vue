<script setup>
import { computed, defineEmits, defineProps, ref } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';

const props = defineProps({
	label: {
		type: String,
		required: true,
	},
	placeholder: {
		type: String,
		required: false,
		default: 'Begin datum',
	},
	property: {
		type: String,
		required: true,
		default: 'afterDate',
		enum: ['afterDate', 'beforeDate'],
	},
	afterDate: {
		type: String,
		required: false,
		default: '',
	},
	beforeDate: {
		type: String,
		required: false,
		default: '',
	},
});

const emit = defineEmits(['change-date']);

const date = ref('');

const dateFormat = (date) => {
	if (!!date) {
		const year = date.getFullYear();
		const month = (date.getMonth() + 1).toString().padStart(2, '0');
		const day = date.getDate().toString().padStart(2, '0');
		return `${year}-${month}-${day}`;
	}
	return '';
};

const validateDate = computed(() => {
	const regex = /^\d{4}-\d{2}-\d{2}$/;

	if (!regex.test(dateFormat(date.value))) {
		return false;
	}

	const inputDate = new Date(date.value);
	const currentDate = new Date();

	return inputDate <= currentDate;
});

// Date of birth of the internet.
const minDate = computed(() => {
	if (props.property === 'beforeDate' && props.afterDate) {
		return new Date(props.afterDate);
	}

	return `1983-01-01`;
});

const maxDate = computed(() => {
	if (props.property === 'afterDate' && props.beforeDate) {
		return new Date(props.beforeDate);
	}

	return new Date();
});

const language = computed(
	() => document.documentElement.lang || navigator.language
);

const onBlur = () => {
	emit('change-date', validateDate.value ? dateFormat(date.value) : '');
};
</script>

<template v-cloak>
	<div
		class="woo-portal-search__fieldset woo-portal-search__fieldset--datepicker woo-portal-search__fieldset--small"
	>
		<label v-if="label" :for="`datum-${ label.toLowerCase() }`" v-html="label" class="woo-portal-search__filter-label" />
		<VueDatePicker
			:id="`datum-${ label.toLowerCase() }`"
			v-model="date"
			auto-apply
			close-on-scroll
			close-on-auto-apply
			locale="language"
			prevent-min-max-navigation
			:enable-time-picker="false"
			:text-input="false"
			:placeholder="placeholder"
			:min-date="minDate"
			:max-date="maxDate"
			format="dd/MM/yyyy"
			@cleared="onBlur"
			@closed="onBlur"
		/>
	</div>
</template>
