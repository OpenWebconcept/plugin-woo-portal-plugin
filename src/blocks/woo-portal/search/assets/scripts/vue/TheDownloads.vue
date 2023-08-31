<script setup>
import { computed, defineProps} from "vue";

const props = defineProps({
  downloads: {
    type: Object,
    default: {}
  },
  searchTerm: {
    type: String,
    default: ''
  }
});

/**
 * Filter based on searchTerm.
 * @return {*|*[]}
 */
const filteredDownloads = computed(() => {
  // if ( ! props.searchTerm ) {
  //   return [];
  // }

  const items = props.downloads.filter( item => item.URL_Bijlage !== false && item.Titel_Bijlage !== false && item.Titel_Bijlage.toLowerCase().includes( props.searchTerm.toLowerCase() ) );

  return items.map( item => {
    const extension = item.URL_Bijlage.split( '.' ).pop();

    return {
      title: item.Titel_Bijlage.replace( new RegExp( props.searchTerm, "gi" ), `<strong>${ props.searchTerm }</strong>` ),
      url: item.URL_Bijlage,
      extension: extension.toLowerCase()
    }
  } );
});
</script>

<template v-cloak>
  <ul v-if="filteredDownloads.length > 0" class="woo-portal-downloads">
    <li v-for="(file, index) in filteredDownloads" :index="index" class="woo-portal-download">
      <a :href="file.url" class="woo-portal-download__link">
        <svg
            aria-hidden="true"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
          <path
              d="M3.18535 14.6922L13.4827 4.62459C15.135 3.01037 17.8135 2.98413 19.4226 4.55709C21.0228 6.12147 20.9965 8.71389 19.3539 10.3197L11.5761 17.9233L9.69788 19.7591C8.56242 20.8691 6.72128 20.887 5.61788 19.8088C4.52382 18.7392 4.54194 16.9664 5.6685 15.8646L11.3541 10.3068L15.1351 6.61016C15.655 6.10212 16.5 6.09398 17.0003 6.58305C17.491 7.06337 17.4829 7.86235 16.9714 8.36289L9.11103 16.0467C8.83549 16.3161 8.83049 16.7578 9.09986 17.0333C9.36916 17.3089 9.81088 17.3139 10.0864 17.0445L17.947 9.3603C19.0134 8.3167 19.0307 6.6179 17.9758 5.5855C16.9288 4.5619 15.2178 4.5784 14.1596 5.61222L10.3786 9.30902L4.69305 14.8668C3.01117 16.5115 2.98383 19.185 4.6425 20.8066C6.2925 22.4192 8.9999 22.3927 10.6731 20.7569L12.5514 18.9212L20.3292 11.3174C22.5272 9.16855 22.5626 5.67555 20.3979 3.55915C18.2423 1.45195 14.6977 1.48675 12.5073 3.62657L2.20966 13.6944C1.9342 13.9637 1.9292 14.4054 2.19856 14.6809C2.46793 14.9565 2.90966 14.9614 3.18512 14.6921L3.18535 14.6922Z"
              fill="black"
          />
        </svg>
        <span class="woo-portal-download__title" v-if="file.title" v-html="file.title" />
        <span class="woo-portal-download__extension" v-if="file.extension" v-html="file.extension" />
      </a>
    </li>

  </ul>
</template>

<style lang="scss">
.woo-portal-downloads {
  display: flex;
  flex-direction: column;
  background: var(--owp-search-background-opacity);
  list-style: none;
  padding-inline: 0;
  width: calc(100% + 48px);
  margin-inline: -24px;
  margin-block-start: 0;
  margin-block-end: -24px;
  z-index: 3;
}

.woo-portal-download__link {
  align-items: center;
  box-sizing: border-box;
  background: var(--owp-search-background-opacity);
  border-bottom: 1px solid var(--owp-search-background);
  color: var(--owp-color) !important;
  display: flex;
  flex-direction: row;
  gap: 16px;
  width: 100%;
  padding-inline: 24px;
  padding-block: 18px;
  text-decoration: none;
}

.woo-portal-download__title {}

.woo-portal-download__extension {
  color: var(--owp-color);
}
</style>
