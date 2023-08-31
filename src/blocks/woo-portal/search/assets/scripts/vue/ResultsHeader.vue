<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
	count: {
		type: Number,
		default: 0,
		required: true,
	},
	searchTerm: {
		type: String,
		default: '',
	},
});

const displayNumber = computed(() => {
	/**
	 * Return display number in localized string.
	 * @return {string}
	 */
	return props.count.toLocaleString(
		document.documentElement.lang || navigator.language
	);
});

/**
 * Return count label, checks on singular or plural label.
 * @return {string}
 */
const countLabel = computed(() => {
	if (props.searchTerm) {
		return props.count === 1
			? `${displayNumber.value} resultaat voor \`${props.searchTerm}\``
			: `${displayNumber.value} resultaten voor \`${props.searchTerm}\``;
	}

	return props.count === 1
		? `${displayNumber.value} resultaat`
		: `${displayNumber.value} resultaten`;
});
</script>

<template>
	<header class="woo-portal-search__results-header">
		<div class="woo-portal-search__results-counter" aria-live="polite">
			<span v-html="countLabel" />
			<span class="woo-portal-search__sr-only">gevonden</span>
		</div>

		<slot></slot>
	</header>
</template>

<style lang="scss">
.woo-portal-search__results-header {
	color: var(--owp-color);
	display: flex;
	justify-content: space-between;
	gap: 1rem;
	grid-area: header;
	font-size: 16px;
	line-height: 1;
	padding-block-end: 18px;
	border-bottom: 1px solid var(--owp-search-border-opacity);
}

.woo-portal-search__results-counter {
	align-items: center;
	display: flex;
}
</style>
