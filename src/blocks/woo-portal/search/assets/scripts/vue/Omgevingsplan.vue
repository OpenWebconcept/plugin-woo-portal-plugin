<template v-cloak>
  <div class="woo-portal-detail__content" v-if="!loading">
    <div>
      <h1
          class="woo-portal-detail__title"
          v-if="detail.Titel"
          v-html="detail.Titel"
      />
    </div>

    <div class="woo-portal-detail__columns">
      <div class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Publicatiedatum</h2>
        <time
            v-if="detail.Publicatiedatum"
            v-html="dateReadable(detail.Publicatiedatum)"
            :datetime="detail.Publicatiedatum"
        />
      </div>
    </div>

    <!-- Samenvatting -->
    <div v-if="detail.Samenvatting">
      <h2 class="woo-portal-detail__subtitle">Samenvatting</h2>
      <p v-html="detail.Samenvatting"/>
    </div>

    <!-- Documenten -->
    <div>
      <h2 class="woo-portal-detail__subtitle" v-if="detail.Documenten">
        Documenten
      </h2>
      <div v-if="detail.Documenten" class="woo-portal-detail__downloads">
        <a
            v-for="download in detail.Documenten"
            :href="download.URL_Bijlage"
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
              v-if="download.Titel_Bijlage"
              v-html="download.Titel_Bijlage"
              class="woo-portal-detail__download-title"
          />
          <span
              v-if="detailDownLoadMeta(download)"
              v-html="detailDownLoadMeta(download)"
              class="woo-portal-detail__download-meta"
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

      <dt v-if="detail.ID">Kenmerk</dt>
      <dd v-if="detail.ID" v-html="detail.ID"></dd>
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
      default: {}
    },
    typeClass: {
      type: String,
      required: false,
      default: ''
    },
  },
  beforeMount() {
    if (this.detail.Titel && this.detail.Titel.length > 0) {
      document.title += ' - ' + this.detail.Titel;
    }
  },
  methods: {
    detailDownLoadMeta(download) {
      if (!download) {
        return null;
      }

      const array = [];

      if (!!download?.URL_Bijlage) {
        let str = download.URL_Bijlage.split('/').pop();
        array.push("(" + str.split('.').pop() + ")");
      }

      return array.join(" - ");
    },
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
