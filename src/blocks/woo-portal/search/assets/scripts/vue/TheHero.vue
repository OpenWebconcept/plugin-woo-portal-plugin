<template v-cloak>
	<section class="woo-portal-search__hero" aria-live="assertive">
		<div class="woo-portal-search__hero-container">
			<h1 v-if="title" class="woo-portal-search__hero-title" v-html="title"/>
			<p
				v-if="description"
				class="woo-portal-search__hero-description"
				v-html="description"
			/>
			<form class="woo-portal-search__form">
				<div class="woo-portal-search__form-group">
					<label for="search" class="sr-only">Zoek op onderwerp</label>
					<input
						type="text"
						id="search"
						v-model="searchTerm"
						class="woo-portal-search__form-input"
						:placeholder="placeholder"
						aria-label="Zoekveld"
					/>
				</div>

				<button
					class="woo-portal-button search"
					@click.prevent="submitTerm"
					:disabled="searchTerm.length < 1"
					aria-label="Zoek"
				>
					<svg
						aria-hidden="true"
						role="img"
						width="17"
						height="16"
						viewBox="0 0 17 16"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
						focusable="false"
					>
						<path
							d="M11.9351 10.0629H11.2124L10.9563 9.81589C11.8528 8.77301 12.3925 7.4191 12.3925 5.94625C12.3925 2.66209 9.73042 0 6.44625 0C3.16209 0 0.5 2.66209 0.5 5.94625C0.5 9.23042 3.16209 11.8925 6.44625 11.8925C7.9191 11.8925 9.27301 11.3528 10.3159 10.4563L10.5629 10.7124V11.4351L15.1369 16L16.5 14.6369L11.9351 10.0629ZM6.44625 10.0629C4.16838 10.0629 2.32962 8.22413 2.32962 5.94625C2.32962 3.66838 4.16838 1.82962 6.44625 1.82962C8.72413 1.82962 10.5629 3.66838 10.5629 5.94625C10.5629 8.22413 8.72413 10.0629 6.44625 10.0629Z"
							fill="none"
						/>
					</svg>
					<span>Zoek</span>
				</button>
				<button
					v-if="searchTerm.length > 0"
					@click.prevent="clearTerm"
					class="woo-portal-search__form-clear"
				>
					<svg
						width="24"
						height="24"
						viewBox="0 0 24 24"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
						aria-label="Leeg zoekveld"
					>
						<path
							d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"
							fill="black"
						/>
					</svg>
				</button>
			</form>

			<img :src="image" class="woo-portal-search__hero-image" alt=""/>
		</div>
	</section>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted } from 'vue';
import apiFetch from '@wordpress/api-fetch';

const props = defineProps({
	title: {
		type: String,
		required: true,
	},
	placeholder: {
		type: String,
		required: true,
	},
	excerpt: {
		type: String,
		required: true,
	},
	image: {
		type: [String, Number], // Allow string or number type for image prop
		required: true,
	},
});

const emit = defineEmits(['change-term']);

const searchTerm = ref('');
const title = ref('');
const placeholder = ref('');
const description = ref('');
const image = ref('');

onMounted(() => {
	title.value = props.title;
	placeholder.value = props.placeholder;
	description.value = props.excerpt;
	fetchImage();
});

function clearTerm() {
	searchTerm.value = [];
	submitTerm();
}

function submitTerm() {
	emit('change-term', searchTerm.value);
}

async function fetchImage() {
	try {
		const response = await apiFetch({
			path: `/wp-json/wp/v2/media/${parseInt(props.image, 10)}`,
		});
		if (response?.media_details?.sizes?.hasOwnProperty('medium_large')) {
			image.value = response.media_details.sizes.medium_large.source_url;
		} else if (response?.media_details?.sizes?.hasOwnProperty('large')) {
			image.value = response.media_details.sizes.large.source_url;
		} else {
			image.value = response?.source_url;
		}
	} catch (error) {
		image.value = '';
		console.error('Fetch Hero Image', error);
	}
}
</script>

<style lang="scss">
.woo-portal-search__hero {
	background: var(--owp-color-white);
	box-shadow: var(--owp-search-box-shadow);
	padding-block: 32px;
}

.woo-portal-search__hero-container {
	display: grid;
	gap: 10px;
	grid-template-columns: 1fr;
	grid-template-areas: 'hero-image' 'hero-title' 'hero-excerpt' 'hero-form';
	margin-inline: auto;
	width: min(
			calc(100% - (var(--owp-search-inline-gap) * 2)),
			var(--owp-search-container-width)
	);
}

@container (min-width: 990px) {
	.woo-portal-search__hero {
		padding-block: 40px;
	}

	.woo-portal-search__hero-container {
		align-items: center;
		grid-template-columns: 2fr 1fr;
		grid-template-areas:
			'hero-title hero-image'
			'hero-excerpt hero-image'
			'hero-form hero-image';
		column-gap: percentage(1/12);
	}
}

.woo-portal-search__hero-image {
	height: auto;
	width: 100%;
	grid-area: hero-image;
}

.woo-portal-search__hero-title {
	grid-area: hero-title;
	font-size: 2rem;
	margin-block: 0;
@container (min-width:990 px) {
	font-size: 3rem;
}
}

.woo-portal-search__hero-description {
	grid-area: hero-excerpt;
	margin-block: 0;
	font-size: var(--owp-search-font-size);
}

.woo-portal-search__form {
	align-items: flex-end;
	grid-area: hero-form;
	&-group {
		flex-direction: column;
	}
}

.woo-portal-button {
	align-items: center;
	background: var(--owp-color-search-button);
	border: 0;
	border-radius: var(--owp-search-radius);
	color: var(--owp-color-search-button-text-color);
	display: flex;
	flex-direction: row;
	font-style: normal;
	font-weight: 400;
	font-size: 12px;
	gap: 14px;
	justify-content: center;
	line-height: 1.5;
	padding: 8px 16px;
	text-decoration: none;
	width: fit-content;
	transition: background-color 375ms ease-in-out;

	svg path {
		fill: var(--owp-color-search-button-text-color);
	}
	&:hover {
		background: var(--owp-color-search-button-tint);
	}

	&:focus, &:active {
		background: var(--owp-color-search-button-shade);
	}

	&:disabled {
		background: var(--owp-search-color-gray-2);
		color: var(--owp-color-white);

		svg path {
			fill: var(--owp-color-white);
		}
	}
	&.search {
		padding: 16px;
		@container (min-width: 480px) {
			padding: 23px;
		}
	}
}

.woo-portal-button--filter {
	border: inset 1px solid var(--owp-search-color-gray-2);
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
	bottom: 8px;
	position: sticky;
	width: 100%;
	color: var(--owp-color-black);
	background: var(--owp-color-white);
	font-size: 16px;
	padding-block: 0.75em;
	padding-inline: 1.5em;
	z-index: 9;
	&:hover {
		color: var(--owp-color-white);
	}
	.woo-portal-search-container:has(.woo-portal-search__aside--visible)
	.woo-portal-search__results
	& {
		display: none;
		pointer-events: none;
	}

	.woo-portal-search__results & {
		margin-block-start: 8px;
	}
}

@container (min-width: 375px) {
	.woo-portal-button--filter {
		margin-inline: auto;
		width: fit-content;
	}
}

@container (min-width: 990px) {
	.woo-portal-button--filter {
		display: none;
		pointer-events: none;
	}
}
</style>
