<template v-cloak>
  <div class="woo-portal-detail__content" v-if="!loading">
    <div>
      <h1
          class="woo-portal-detail__title"
          v-if="detail.Titel"
          v-html="detail.Titel"
      />
    </div>

    <!-- Generic fields, provided and ordered by the endpoint. -->
    <template v-for="(field, index) in fields" :key="index">
      <div v-if="field.value">
        <h2
            class="woo-portal-detail__subtitle"
            v-if="field.label"
            v-html="field.label"
        />
        <time
            v-if="'date' === field.kind"
            :datetime="field.value"
            v-html="dateReadable(field.value)"
        />
        <div v-else-if="'html' === field.kind" v-html="field.value" />
        <p v-else>{{ field.value }}</p>
      </div>
    </template>

    <!-- Downloads -->
    <div v-if="downloads.length">
      <h2 class="woo-portal-detail__subtitle">Documenten</h2>
      <div class="woo-portal-detail__downloads">
        <a
            v-for="(download, index) in downloads"
            :key="index"
            :href="download.URL"
            class="woo-portal-detail__download"
            download
        >
          <div class="woo-portal-detail__download-circle">
            <svg
                aria-hidden="true"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
              <path
                  d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15"
                  stroke="white"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
              />
              <path
                  d="M7 10L12 15L17 10"
                  stroke="white"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
              />
              <path
                  d="M12 15V3"
                  stroke="white"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
              />
            </svg>
          </div>
          <h3
              v-if="download.Titel"
              v-html="download.Titel"
              class="woo-portal-detail__download-title"
          />
        </a>
      </div>
    </div>
  </div>

  <aside class="woo-portal-detail__aside" v-if="!loading">
    <dl>
      <dt v-if="detail.type">Documentsoort</dt>
      <dd
          v-if="detail.type"
          v-html="detail.type"
          :class="typeClass"
      />
    </dl>
  </aside>
</template>

<script>
export default {
  components: {},
  props: {
    loading: {
      type: Boolean,
      required: true,
      default: false
    },
    detail: {
      type: Object,
      required: false,
      default: () => ({})
    },
    typeClass: {
      type: String,
      required: false,
      default: ''
    },
  },
  computed: {
    /**
     * Normalized field list provided by the endpoint.
     * Each entry: { label, value, kind: 'text' | 'date' | 'html' }.
     * @return {Array}
     */
    fields() {
      return Array.isArray(this.detail.fields) ? this.detail.fields : [];
    },
    /**
     * Normalized download list provided by the endpoint.
     * Each entry: { Titel, URL }.
     * @return {Array}
     */
    downloads() {
      return Array.isArray(this.detail.downloads) ? this.detail.downloads : [];
    },
  },
  beforeMount() {
    if (this.detail.Titel && this.detail.Titel.length > 0) {
      document.title += ' - ' + this.detail.Titel;
    }
  },
  methods: {
    dateReadable(date) {
      let d = new Date(date);
      d = 'Invalid Date' != d ? d : new Date(date.split('-').reverse().join('-'))
      return d.toLocaleDateString("nl-NL", {
        year: "numeric",
        month: "long",
        day: "numeric"
      });
    },
  }
}
</script>
