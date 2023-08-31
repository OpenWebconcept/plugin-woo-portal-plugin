<script setup>
import { defineEmits, defineProps, ref } from 'vue';

defineProps({
	type: {
		type: String,
		required: true,
	},
	id: {
		type: String,
		required: true,
	}
});
const emit = defineEmits(['change-order']);
const selected = ref('desc,relevance');

const onChange = () => {
	emit('change-order', selected.value);
};
</script>

<template v-cloak>
	<label :for="id" class="sr-only">Sorteer op</label>
	<select :id="id" @change="onChange" v-model="selected" class="woo-portal-search__select" aria-label="Sorteer zoekresultaten">
		<option value="asc,relevance">
			Sorteren op minst relevanste eerst
		</option>
		<option value="desc,relevance">Sorteren op relevanste eerst</option>
		<option value="asc,date">Sorteren op datum nieuwste eerst</option>
		<option value="desc,date">Sorteren op datum oudste eerst</option>
		<option value="asc,title">Sorteren op alfabetisch oplopend</option>
		<option value="desc,title">Sorteren op alfabetisch aflopend</option>
	</select>
</template>

<style lang="scss">
.woo-portal-search__select {
	background-color: transparent;
	border: none;
	color: var(--owp-color);
	font-size: 16px;
	font-weight: 700;
	line-height: 1;
	padding-inline-end: 1.125em;
	text-align: right;
  max-width: 100%;
	.woo-portal-search__fieldset & {
		background: var(--owp-color-white);
		border: 1px solid var(--owp-search-color-gray-3);
		border-radius: var(--owp-search-radius);
		text-align: left;
		padding-block: 10px;
		padding-inline-start: 10px;
	}
}

@container (max-width: 990px) {
	.woo-portal-search__select {
		.woo-portal-search__results-header & {
			display: none;
			pointer-events: none;
		}
	}
}
</style>
