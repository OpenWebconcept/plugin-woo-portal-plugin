<script setup>
import { computed, defineEmits, defineProps, ref } from 'vue';

const props = defineProps({
	filters: {
		type: Object,
		required: true,
		default: {},
	},
	label: {
		type: String,
		default: null,
		required: false,
	},
	type: {
		type: String,
		default: 'post_type',
		required: true,
	},
});

const emit = defineEmits(['change-filter']);

const selected = ref([]);

/**
 * Remove empty filters.
 * @return {*}
 */
const filteredFilters = computed(() => {
	if (!props.filters || typeof props.filters !== 'object') {
		return []; // Return an empty array or handle the error condition accordingly
	}

	return props.filters.filter((obj) =>
		Object.entries(obj).every(([key, label]) => key && label)
	);
});

/**
 * Fire onchange event.
 */
const onChange = () => {
	emit('change-filter', { [props.type]: selected.value });
};
</script>

<template v-cloak>
	<fieldset class="woo-portal-search__fieldset" v-if="filteredFilters.length > 0">
		<legend v-if="label" v-html="label" class="woo-portal-search__legend" />
		<label
			v-for="(filter, index) in filteredFilters"
			:key="index"
			class="woo-portal-search__filter"
		>
			<input
				v-if="filter.key"
				type="checkbox"
				:name="props.type"
				:value="filter.key"
				v-model="selected"
				@change="onChange"
			/>
			{{ 'OpenWOO' === filter.label ? 'Woo-verzoek' : filter.label }}
		</label>
	</fieldset>
</template>

<style lang="scss">
.woo-portal-search__filter {
	font: {
		family: var(--owp-search-font-family);
		size: 12px;
		weight: 300;
	}
	border: 1px solid var(--owp-search-color-gray-2);
	border-radius: 40px;
	color: var(--owp-color);
	display: flex;
	gap: 8px;
	line-height: 1;
	padding: 10px;
	flex: none;
	order: 0;
	flex-grow: 0;
	transition:
		color 375ms ease-in-out,
		border 375ms ease-in-out,
		width 375ms ease-in-out;

	// Text ellipsis, make sure it fits in one line.
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;

	&:before {
		content: '';
		clip-path: path(
			'M4.87516 9.87498L0.208496 5.20831L1.10433 4.31248L4.87516 8.08331L12.8752 0.083313L13.771 0.979146L4.87516 9.87498Z'
		);
		background: var(--owp-color-search-button);
		display: inline-block;
		height: 10px;
		width: 14px;
		transition: transform 250ms ease-in-out;
		transform: scale(0);
		position: absolute;
	}

	&:hover,
	&:has(:checked) {
		border-color:var(--owp-color-search-button);
    color: var(--owp-color-search-button);
	}

	&:has(:checked) {
		color: var(--owp-color-search-button);
    background: var(--owp-color-white);

		&:before {
			position: static;
			transform: scale(1);
		}
	}

	input[type='checkbox'] {
		position: absolute;
		width: 1px;
		height: 1px;
		padding: 0;
		margin: -1px;
		overflow: hidden;
		clip: rect(0, 0, 0, 0);
		white-space: nowrap;
		border-width: 0;
	}
}

.woo-portal-search__filter:has(input[type='checkbox']:focus-visible) {
	outline: var(--owp-search-focus);
	outline-offset: 2px;
}

.woo-portal-search__filter-label, .woo-portal-search__legend {
	font: {
		family: var(--owp-search-font-family);
		size: 16px;
		weight: 500;
	}
	line-height: 1.5;
}
</style>
