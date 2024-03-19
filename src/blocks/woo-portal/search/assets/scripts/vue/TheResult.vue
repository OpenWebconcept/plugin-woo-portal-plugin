<script setup>
import { computed, defineProps } from 'vue';
// import TheDownloads from './TheDownloads.vue';
import TheDecree from "./TheDecree.vue";

const props = defineProps({
	type: {
		type: String,
		default: 'openwoo-item',
	},
	title: {
		type: String,
		required: true,
	},
	link: {
		type: String,
		required: true,
	},
	date: {
		type: String,
		default: null,
	},
	excerpt: {
		type: String,
		default: null,
	},
	decree: {
		type: String,
		default: null,
	},
	downloads: {
		type: Object,
		default: {},
	},
	parties: {
		type: Object,
		default: [],
	},
	filters: {
		type: Array,
		default: [],
	},
	searchTerm: {
		type: String,
		default: '',
	},
});

/**
 * Returns readable date for the user.
 * @return {string|null}
 */
const dateReadable = computed(() => {
	if (!props.date) {
		return null;
	}

	return new Date(formatDate(props.date)).toLocaleDateString(
		document.documentElement.lang || navigator.language,
		{
			year: 'numeric',
			month: 'long',
			day: 'numeric',
		}
	);
});

/**
 * Returns card description. Based on the post_type it may differ what to display.
 * @return {*|string}
 */
const description = computed(() => {
	if ('openconvenant-item' === props.type && props.parties.length > 0) {
		const parties = props.parties.map((p) => p.Naam);

		if (parties.length > 2) {
			return `${parties.slice(0, -2).join(', ')}, ${parties
				.slice(-2)
				.join(' & ')}`;
		}
		if (2 === parties.length) {
			return parties.join(' & ');
		}
		return parties[0];
	}

	return props.excerpt;
});

/**
 * Get postType label based on the provided label from the endpoint.
 * @return {*}
 */
const postTypeLabel = computed(() => {
	if (!props.filters) {
		return null;
	}

	const item = props.filters
		.flatMap((g) => g.items)
		.find((f) => f.key === props.type);

	if (!item) {
		return null;
	}

	return item.label;
});

/**
 * Return highlighted Title with searchTerm.
 * @return {*}
 */
const higlightedTitle = computed(() => {
	if (
		!!props.searchTerm &&
		props.title.toLowerCase().includes(props.searchTerm.toLowerCase())
	) {
		return props.title.replace(
			new RegExp(props.searchTerm, 'gi'),
			`<strong>${props.searchTerm}</strong>`
		);
	}

	return props.title;
});

/**
 * Return highlighted Description with searchTerm.
 * @return {*}
 */
const higlightedDescription = computed(() => {
	if (
		!!props.searchTerm &&
		description.value.toLowerCase().includes(props.searchTerm.toLowerCase())
	) {
		return description.value.replace(
			new RegExp(props.searchTerm, 'gi'),
			`<strong>${props.searchTerm}</strong>`
		);
	}
	return description.value;
});

function formatDate(date) {
	const regex = /^\d{2}-\d{2}-\d{4}$/;
	if (regex.test(date)) {
		return date.split('-').reverse().join('-');
	}
	return date;
}
</script>

<template v-cloak>
	<article class="woo-portal-result">
		<a v-if="link && title" :href="link" class="woo-portal-result__link">
			<h3 v-if="title" v-html="props.title" class="woo-portal-result__title" />
		</a>
		<h3 v-else-if="title" v-html="props.title" class="woo-portal-result__title" />
		<p
			v-if="description"
			v-html="props.excerpt"
			class="woo-portal-result__excerpt"
		/>
		<div class="woo-portal-result__meta">
			<span
				v-if="type"
				v-html="postTypeLabel"
				class="woo-portal-result__type"
				:class="`woo-portal-result__type--${type}`"
			/>
			<time
				class="woo-portal-result__date"
				v-if="date"
				v-html="dateReadable"
				:datetime="date"
			/>
		</div>
		<TheDecree :decree="decree" />
	</article>
</template>

<style lang="scss">
.woo-portal-result {
	align-self: stretch;
	align-items: flex-start;
	background: var(--owp-color-white);
	box-shadow: var(--owp-search-box-shadow);
	border-radius: var(--owp-search-radius);
	box-sizing: border-box;
	color: var(--owp-color);
	display: flex;
	flex: none;
	flex-direction: column;
	flex-grow: 0;
	padding: 24px;
	gap: 10px;
	width: 100%;
	order: 0;
	overflow: hidden;
	text-align: left;
	transition: box-shadow 375ms ease-in-out;
	position: relative;

	&:hover {
		box-shadow: var(--owp-search-box-shadow);
	}

	&:active {
		box-shadow: 0 0 0 transparent;
	}
}

.woo-portal-result__link {
	color: inherit !important;
	&:hover {
		text-decoration: underline;
	}
	&,
	&:focus {
		text-decoration: none;
	}

	&:after {
		background: transparent;
		bottom: 0;
		content: '';
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
	}
}

.woo-portal-result__title {
	font-weight: 500;
	font-size: 20px;
	line-height: 130%;
	margin-block: 0;
}

.woo-portal-result__excerpt {
	font: {
		size: 16px;
		weight: 300;
	}
	line-height: 1.5;
	margin-block: 0;
	display: -webkit-box;
	display: -moz-box;
	display: flex;
	-webkit-line-clamp: 3; /* Number of lines to show */
	-webkit-box-orient: vertical;
	-moz-box-orient: vertical;
	box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
	max-height: calc(1.5em * 3); /* Number of lines * line-height */
}

.woo-portal-result__meta {
	align-items: flex-start;
	order: -1;
	display: flex;
	flex-direction: column;
	gap: 8px;
}

@container (min-width: 400px) {
	.woo-portal-result__title {
		font-size: 24px;
	}
	.woo-portal-result__meta {
		align-items: center;
		flex-direction: row;
	}
}

.woo-portal-result__type {
	background-color: var(--owp-color-primary);
	border-radius: 20px;
	font-style: normal;
	font-weight: 300;
	font-size: 12px;
	line-height: 100%;
	/* identical to box height, or 12px */

	padding: 4px 8px;
	color: var(--owp-color);

	&--openconvenant-item {
		background-color: var(--owp-color-secondary);
	}

	&--openwoo-item {
		background-color: var(--owp-color-primary-tint);
	}

	.woo-portal-detail__aside & {
		width: fit-content;
	}
}

.woo-portal-result__date {
	font-style: normal;
	font-weight: 300;
	font-size: 14px;
	line-height: 100%;
}

.woo-portal-search__results {
	grid-area: results;
	display: flex;
	flex-direction: column;
	gap: 8px;

	&--loading {
		display: flex;
		place-items: center;
    min-height: 30dvh;
    justify-content: center;
		svg {
			max-width: 3rem;
		}
	}
}
</style>
