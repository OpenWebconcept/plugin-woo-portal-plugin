<script setup>
import { computed, defineEmits, defineProps } from 'vue';

const props = defineProps({
	pagination: {
		type: Object,
		required: true,
	},
	label: {
		type: String,
		default: 'Pagination',
	},
	before_page_number: {
		type: String,
		default: 'Page',
	},
	prev_text: {
		type: String,
		default: 'Previous page',
	},
	next_text: {
		type: String,
		default: 'Next page',
	},
});

const emit = defineEmits(['change-page']);

const currentPage = computed(() => props.pagination.pages.current);
const totalPages = computed(() => props.pagination.pages.total);

const paginationItems = computed(() => {
	const current = currentPage.value;
	const total = totalPages.value;
	const midSize = 2;
	const links = [];

	// Add the first page link
	links.push({
		text: '1',
		page: 1,
		active: current === 1,
		disabled: current === 1,
	});

	// Determine the range of pages to show
	let start = Math.max(2, current - midSize);
	let end = Math.min(current + midSize, total - 1);

	// Add the dots if necessary
	if (start > 2) {
		links.push({
			text: '...',
			page: false,
			active: false,
			disabled: true,
		});
	}

	// Add the middle page links
	for (let i = start; i <= end; i++) {
		links.push({
			text: i.toString(),
			page: i,
			active: i === current,
			disabled: false,
		});
	}

	// Add the dots if necessary
	if (end < total - 1) {
		links.push({
			text: '...',
			page: false,
			active: false,
			disabled: true,
		});
	}

	// Add the last page link
	if (total > 1) {
		links.push({
			text: total.toString(),
			page: total,
			active: current === total,
			disabled: current === total,
		});
	}

	// Determine the previous and next pages
	const prev = current > 1 ? current - 1 : false;
	const next = current < total ? current + 1 : false;

	return {
		current,
		prev,
		next,
		links,
	};
});

function setPage(pageNumber) {
	if (pageNumber >= 1 && pageNumber <= totalPages.value) {
		emit('change-page', pageNumber);
	}
}
</script>

<template v-cloak>
	<nav :aria-label="label" class="woo-portal-search__pagination pagination">
		<ul class="woo-portal-search__pagination-list">
			<li>
				<button
					@click="setPage(currentPage - 1)"
					:disabled="currentPage === 1"
					class="prev woo-portal-search__page-numbers"
					:aria-disabled="currentPage === 1"
				>
					<svg
						aria-hidden="true"
						role="img"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 96 960 960"
					>
						<path
							d="M561 816 320 575l241-241 43 43-198 198 198 198-43 43Z"
							fill="currentColor"
						/>
					</svg>
					<span class="woo-portal-search__sr-only" v-html="prev_text" />
				</button>
			</li>
			<li v-for="(link, index) in paginationItems.links" :key="index">
				<button
					v-if="!link.disabled || !!link.active"
					@click.prevent="setPage(link.page)"
					class="woo-portal-search__page-numbers"
					:aria-current="currentPage === link.page ? 'page' : false"
					:class="{ current: link.active }"
					:disabled="currentPage === link.page"
				>
					<span
						class="woo-portal-search__sr-only"
						v-html="before_page_number"
					/>
					{{ link.text }}
				</button>
				<span
					v-else
					class="woo-portal-search__page-numbers dots"
					v-html="link.text"
				/>
			</li>
			<li>
				<button
					@click="setPage(currentPage + 1)"
					:disabled="currentPage === totalPages"
					:aria-disabled="currentPage === totalPages"
					class="next woo-portal-search__page-numbers"
				>
					<svg
						aria-hidden="true"
						role="img"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 96 960 960"
					>
						<path
							d="m375 816-43-43 198-198-198-198 43-43 241 241-241 241Z"
							fill="currentColor"
						/>
					</svg>
					<span class="woo-portal-search__sr-only" v-html="next_text" />
				</button>
			</li>
		</ul>
	</nav>
</template>

<style lang="scss">
.woo-portal-search__pagination {
	--owp-pagination-number-size: 32px;
  margin-top: var(--padding);
	display: flex;
	justify-content: center;
}

.woo-portal-search__pagination-list {
	display: flex;
	flex-direction: row;
	list-style: none;
	padding-inline: 0;
	margin-inline: auto;
}

.woo-portal-search__page-numbers {
	font: {
		size: 18px;
		weight: 500;
	}

	aspect-ratio: 1;
	background: transparent;
	color: var(--owp-search-color-gray);
	display: grid;
	line-height: 1;
	place-content: center;
	place-items: center;
	padding: 0;
	margin: 0;
	height: var(--owp-pagination-number-size);
	width: var(--owp-pagination-number-size);
	border: 0;
	transition: all 375ms ease-in-out;

	&:not(.prev):not(.next) {
		background-color: var(--owp-color-white);
	}

	&[disabled] {
		color: var(--owp-search-color-gray-3);
	}

	&:hover,
	&.current {
		color: var(--owp-color-search-button-text-color);
    background-color: var(--owp-color-search-button)!important;
	}

	svg {
		height: calc(var(--owp-pagination-number-size) - 8px);
		width: calc(var(--owp-pagination-number-size) - 8px);
	}
}
</style>
