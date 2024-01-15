<script setup>
import { computed, defineProps, onMounted, ref, watch } from 'vue';
import BaseAlert from './BaseAlert.vue';
import BasePagination from './BasePagination.vue';
import BaseLoader from './BaseLoader.vue';
import TheFilters from './TheFilters.vue';
import TheResult from './TheResult.vue';
import ResultsHeader from './ResultsHeader.vue';
import FilterOrder from './FilterOrder.vue';
import FilterDate from './FilterDate.vue';
import TheHero from './TheHero.vue';
import TheDetail from "./TheDetail.vue";

/**
 * Props.
 * @type {Readonly<ExtractPropTypes<{image: {default: number, type: *}, per_page: {default: number, type: *}, endpoint: {default: string, type: *, required: boolean}, placeholder: {default: string, type: *}, title: {default: string, type: *}, excerpt: {default: string, type: *}}>>}
 */
const props = defineProps({
	endpoint: {
		type: String,
		default: '',
		required: true,
	},
	title: {
		type: String,
		default: '',
	},
	excerpt: {
		type: String,
		default: '',
	},
	placeholder: {
		type: String,
		default: '',
	},
	image: {
		type: String,
		default: 0,
	},
	per_page: {
		type: String,
		default: 10,
	},
});

const error = ref(null);
const loading = ref(false);
const loadingFilters = ref(false);
const showFilters = ref(false);

// Set data
const searchTerm = ref('');
const selectedFilters = ref({});
const page = ref(1);
const order = ref('desc,relevance');
const beforeDate = ref(''); // YYYY-MM-DD
const afterDate = ref(''); // YYYY-MM-DD

// Fetched data
const links = ref([]);
const filters = ref([]);
const results = ref([]);
const pagination = ref({
	total: 0,
	limit: 0,
	pages: {
		total: 1,
		current: 1,
	},
});

/**
 * Computed properties.
 *
 * @returns {Promise<void>}
 */
async function getResults() {
	loading.value = true;
  const url = new URL(`${props.endpoint}items`);
	if (!!page.value && 1 !== page.value) {
		url.searchParams.set('page', page.value);
	}

	if (10 !== parseFloat(props.per_page)) {
		url.searchParams.set('per_page', per_page.value);
	}

	// set date parameters if filtered on posts published before and/or after a given ISO8601 compliant date.
	if (!!beforeDate.value) {
		url.searchParams.set('before', `${beforeDate.value}T23:59:59`);
	}
	if (!!afterDate.value) {
		url.searchParams.set('after', `${afterDate.value}T00:00:00`);
	}

  if ( Object.keys( selectedFilters.value ).length > 0 ) {
    Object.keys( selectedFilters.value ).forEach( (key ) => {
        // Append comma-seperated.
        url.searchParams.set(
            `${ key }`,
            selectedFilters.value[ key ]
        );
    } );
  }

	// Append order & orderBy parameter.
	if (!!order.value && 'desc,relevance' !== order.value) {
		const splittedOrder = order.value.split(',');
		if ('desc' !== splittedOrder[0]) {
			url.searchParams.set('order', splittedOrder[0]);
		}
		if ('date' !== splittedOrder[1]) {
			url.searchParams.set('orderby', splittedOrder[1]);
		}
	}

	if (!!searchTerm.value) {
		url.searchParams.set('search', searchTerm.value);
	}

	await fetch(url.toString())
		.then((response) => response.json())
		.then((response) => {
			results.value = response.Results;
			links.value = response.Links;
			pagination.value = response.Pagination;
			loading.value = false;
		})
		.catch((error) => {
			error.value = error;
			loading.value = false;
		});
}

async function getFilters() {
	loadingFilters.value = true;
	const url = new URL(`${props.endpoint}filters`);
	await fetch(url.toString())
		.then((response) => response.json())
		.then((response) => {
			filters.value = response.Filters.sort((a, b) =>
				a.label.localeCompare(b.label)
			);
			loadingFilters.value = false;
		})
		.catch((error) => {
			error.value = error;
			loadingFilters.value = false;
		});
}

function toggleFilters() {
	showFilters.value = !showFilters.value;

	if (showFilters.value) {
		// this.$refs.container.scrollIntoView({ behavior: 'smooth' });
    window.scrollTo(0, 350)
	}
}

/**
 * Set new values.
 * @param {array|object} value
 */

function updateFilters(value) {
  selectedFilters.value[Object.keys(value).toString()] = value[Object.keys(value).toString()]
}

/**
 * Set value for the order on.
 * @param value
 */
function updateOrder(value) {
	order.value = value;
}

/**
 * Update the date from.
 * @param value
 */
function updateFromDate(value) {
	afterDate.value = value;
}

/**
 * Update the date to.
 * @param value
 */
function updateToDate(value) {
	beforeDate.value = value;
}

/**
 * Set new pagination page.
 * @param {int} value
 */
function setPage(value) {
	page.value = value;
}

/**
 * Set the searchTerm.
 * @param {string} value
 */
function updateTerm(value) {
	searchTerm.value = value;
}

/**
 * Checks if it has pagination.
 * @return {boolean}
 */
const hasPagination = computed(() => {
	return !(loading.value || pagination.value.pages.total <= 1);
});

const noResultsMessage = computed(() => {
	return `Er zijn geen resultaten gevonden met deze filters.`;
});

/**
 * Return number of amount of results.
 * @return {number}
 */
const resultsCounter = computed(() => {
	if (pagination.value?.total) {
		return pagination.value.total;
	}

	if (results.value) {
		return results.value.length;
	}

	return 0;
});

const pageTitle = computed(() => {
	return document.title;
});
const getRestURL = () => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const woo_portal_type = urlParams.get('owc_type');
  const woo_portal_item = urlParams.get('owc_item');
  if (!woo_portal_type || !woo_portal_item) {
    return false;
  }
  if (!loading && !links.value.hasOwnProperty(woo_portal_type)) {
   return false;
  }
  return links.value[woo_portal_type] ? links.value[woo_portal_type].replace('{owc_item}', woo_portal_item) : console.log('No restUrl found');
};

onMounted(() => {
	getFilters();
	getResults();
});

watch(
	[
		order,
		page,
		selectedFilters,
		beforeDate,
		afterDate,
		searchTerm,
	],

	() => {
		getResults();
	},
	{ deep: true }
);
</script>

<template v-if="endpoint">
  <TheDetail v-if="!loading" :rest-url="getRestURL()" :page-title="pageTitle"/>
	<TheHero
		:title="title"
		:placeholder="placeholder"
		:excerpt="excerpt"
		:image="image"
		@change-term="updateTerm"
	/>
	<div class="woo-portal-search-container" ref="container">
		<BaseAlert v-if="error" type="error" :message="error" />
		<aside
			class="woo-portal-search__aside"
			ref="aside"
			:class="{
				'woo-portal-search__aside--loading': loadingFilters,
				'woo-portal-search__aside--visible': showFilters,
			}"
		>
			<div class="woo-portal-search__aside-block">
				<h2
					v-if="filters"
					v-html="`Filter`"
					class="woo-portal-search__aside-heading"
				/>
				<BaseLoader :loading="loadingFilters" />
				<TheFilters
					v-if="!loadingFilters && filters"
					v-for="(group, key) in filters"
					:key="key"
					:filters="group.items"
					:label="group.label"
					:type="group.param"
					@change-filter="updateFilters"
				/>
				<FilterDate
					v-if="!loadingFilters"
					property="afterDate"
					label="Van"
					after-date="afterDate"
					before-date="beforeDate"
					@change-date="updateFromDate"
				/>
				<FilterDate
					v-if="!loadingFilters"
					property="beforeDate"
					label="Tot"
					placeholder="Eind datum"
					@change-date="updateToDate"
				/>
				<div class="woo-portal-search__fieldset woo-portal-search__fieldset--order">
					<FilterOrder id="aside-sort" type="order" @change-order="updateOrder" />
				</div>
				<div class="woo-portal-search__fieldset woo-portal-search__fieldset--filter">
					<button
						class="woo-portal-button woo-portal-button--filter"
						@click.prevent="toggleFilters"
					>
						Filter en sorteer
					</button>
				</div>
			</div>
		</aside>
		<ResultsHeader :count="resultsCounter">
			<FilterOrder id="header-sort" type="order" @change-order="updateOrder" />
		</ResultsHeader>
		<section
			class="woo-portal-search__results"
			:class="{ 'woo-portal-search__results--loading': loading }"
			aria-live="polite"
			aria-atomic="true"
		>
			<BaseLoader :loading="loading" />
			<TheResult
				v-for="(result, index) in results"
				v-if="!loading"
				:title="result.Titel"
				:type="result.Type"
				:date="result.Datum"
				:link="result._links.self"
				:excerpt="result.Samenvatting"
				:parties="
					result.hasOwnProperty('Partijen') ? result.Partijen : []
				"
				:downloads="
					result.hasOwnProperty('Bijlagen') ? result.Bijlagen : []
				"
				:filters="filters"
				:search-term="searchTerm"
				:index="index"
			/>
			<BaseAlert
				v-if="!loading && 0 === resultsCounter"
				type="warning"
				:message="noResultsMessage"
			/>
      <BasePagination
        v-if="hasPagination"
        :pagination="pagination"
        @change-page="setPage"
      />
      <button
          class="woo-portal-button woo-portal-button--filter"
          @click.prevent="toggleFilters"
      >
        Filter en sorteer
      </button>
    </section>
	</div>

</template>

<style lang="scss">
.sr-only {
	clip: rect(0,0,0,0);
	border: 0;
	height: 1px;
	margin: -1px;
	overflow: hidden;
	padding: 0;
	position: absolute;
	width: 1px;
}
#woo-portal-search {
  background: var(--owp-search-background);
  container-type: inline-size;
	font-family: var(--owp-search-font-family);
	font-style: normal;
	max-width: 100%;

	* > :has(&) {
		margin-inline: 0;
		padding-inline: 0;
	}
}

.woo-portal-search-container {
	width: min(
		#{calc(100% - (var(--owp-search-inline-gap) * 2))},
		var(--owp-search-container-width)
	);
	margin-inline: auto;
	display: grid;
	gap: 24px;

	grid-template-areas: var(--owp-search-layout);

	grid-template-columns: var(--owp-search-columns);
	padding-block: 48px;
}

#main-content:has(.woo-portal-detail-wrapper), main:has(.woo-portal-detail-wrapper) {
  .woo-portal-search__hero, .woo-portal-search-container {
    display: none;
  }
}

#main-content .woo-portal-detail-wrapper + .woo-portal-search__hero, #main-content .woo-portal-detail-wrapper + .woo-portal-search__hero + .woo-portal-search-container,
main .woo-portal-detail-wrapper + .woo-portal-search__hero, main .woo-portal-detail-wrapper + .woo-portal-search__hero + .woo-portal-search-container
{
  display: none;
}

@container (min-width: 990px) {
  .woo-portal-search-container {
		--owp-search-columns: 1fr 2fr;
	}
}

@container (min-width: 1280px) {
 .woo-portal-search-container {
		--owp-search-columns: 1fr 3fr;
	}
}

.woo-portal-search *:focus-visible {
	outline-offset: 0.25em;
	outline: var(--owp-search-focus);
}

.woo-portal-search__pagination {
	grid-area: pagination;
}

.woo-portal-search__sr-only {
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

@container (max-width: 990px) {
	.woo-portal-search__results,
	.woo-portal-search__results-header,
	.woo-portal-search__pagination {
		grid-column: 1 / span 2;
	}
}

.woo-portal-search__aside {
	grid-area: aside;
	transition: transform 375ms ease-in-out;
	transform: translateX(-100%);

	&--loading {
		display: grid;
		place-items: center;

		svg {
			max-width: 3rem;
		}
	}

	&--visible {
    background: var(--owp-search-background);
    transform: translateX(0);
		grid-column: 1 / span 3;
		z-index: 9;
    overflow: hidden;
    margin-left: -20px;
    padding-left: 20px;
	}
}

.woo-portal-search__aside-block {
	width: 0;

	.woo-portal-search__aside--visible & {
		width: 100%;
	}
}

@container (min-width: 1024px) {
	.woo-portal-search__aside {
		transform: translateX(0);
	}

	.woo-portal-search__aside-block {
		position: sticky;
		top: 32px;
		width: 100%;
	}
}

.woo-portal-search__aside-heading {
	color: var(--owp-color);
	font: {
		family: var(--owp-search-font-family);
		size: 20px;
		weight: 700;
	}
	line-height: 1;
	margin-block: 0;
}

.woo-portal-search__form {
	container-type: inline-size;
	display: flex;

	.woo-portal-button {
		border-radius: 0 var(--owp-search-radius) var(--owp-search-radius) 0;
		font-size: 16px;
		font-weight: 700;
		line-height: 1;
		& > span {
			display: none;
		}
	}
}

.woo-portal-search__form-input {
	border: 1px solid var(--owp-search-color-gray-2);
	border-radius: var(--owp-search-radius) 0 0 var(--owp-search-radius);
	color: var(--owp-search-color-gray);
	font: {
		family: var(--owp-search-font-family);
		size: 16px;
		weight: 700;
		style: normal;
	}
	line-height: 1;
	padding: 13px 56px 13px 16px;
	text-overflow: ellipsis;
	width: 100%;

	&::placeholder {
		font-style: italic;
		font-weight: 400;
		color: var(--owp-search-color-gray-3);
	}
}

.woo-portal-search__form-group {
	display: flex;
	position: relative;
	width: 100%;
}

.woo-portal-search__form-clear {
	background: transparent;
	border: none;
	position: absolute;
	right: calc(135px + 14px);
	padding: 0;
	aspect-ratio: 1;
	height: auto;
	width: 34px;
	top: 50%;
	transform: translateY(-50%);
}

@container (min-width: 380px) {
	.woo-portal-search__form .woo-portal-button > span {
		display: block;
	}
}

@container (min-width: 480px) {
	.woo-portal-search__form .woo-portal-button {
		width: 135px;
	}

	.woo-portal-search__form-input {
		padding: 20px 60px 20px 20px;
	}

	.woo-portal-search__form-clear {
    right: calc(135px + 22px);
	}
}

.woo-portal-search__fieldset {
	align-items: flex-start;
	border: none;
	display: flex;
	//flex-direction: row;
	gap: 8px;
	margin-inline: 0;
	padding: 0;

	.woo-portal-search__legend {
		margin: 0 0 16px;
	}

	.woo-portal-search__filter {
		margin: 0;
	}

	& + & {
		margin-block-start: 32px;
	}

	.woo-portal-search__aside-heading + & {
		margin-block-start: 32px;
	}

	&--small + &,
	& + &--small {
		margin-block-start: 32px;
	}

	&--datepicker {
		width: fit-content;
    flex-direction: column;
    input:hover {
      border-color: var(--owp-color-search-button);
    }
    .dp__active_date, .dp__today {
      border-color: var(--owp-color-search-button);
      //background: #;
    }
    .dp__active_date {
      border-color: var(--owp-color-search-button);
      background: var(--owp-color-search-button);
    }
    .dp__date_hover:hover {
      color: var(--owp-color-search-button);
      background: var(--colour-white);
    }
		svg {
			color: var(--owp-color);
		}
	}
}

@container (min-width: 990px) {
	.woo-portal-search__fieldset--order {
		display: none;
		pointer-events: none;
	}
}

.woo-portal-search__legend {
	font: {
		family: var(--owp-search-font-family);
		size: 16px;
		weight: 700;
	}

	line-height: 1;
}
</style>
