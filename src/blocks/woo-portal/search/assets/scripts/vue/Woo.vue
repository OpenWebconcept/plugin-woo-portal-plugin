
<template v-cloak>
  <div class="woo-portal-detail__content" v-if="!loading">
    <div>
      <h1
          class="woo-portal-detail__title"
          v-if="detail.Titel"
          v-html="detail.Titel"
      />
    </div>

    <!-- Verzoek + Besluit -->
    <div class="woo-portal-detail__columns">
      <div class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Verzoek</h2>
        <time
            v-if="detail.Ontvangstdatum"
            v-html="dateReadable(detail.Ontvangstdatum)"
            :datetime="detail.Ontvangstdatum"
        />
        <a
            v-if="detail.URL_informatieverzoek"
            :href="detail.URL_informatieverzoek"
            class="btn"
            target="_blank"
        >
          Bekijk informatieverzoek in PDF
        </a>

<!--        <button v-if="!detail.URL_informatieverzoek" class="btn" disabled>-->
<!--          Bekijk informatieverzoek in PDF-->
<!--        </button>-->
      </div>

      <div class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Besluit</h2>
        <time
            v-if="detail.Besluitdatum"
            v-html="dateReadable(detail.Besluitdatum)"
            :datetime="detail.Besluitdatum"
        />
        <a
            v-if="detail.URL_besluit"
            :href="detail.URL_besluit"
            class="btn"
            target="_blank"
        >
          Bekijk besluit in PDF
        </a>

<!--        <button v-if="!detail.URL_besluit" class="btn" disabled>-->
<!--          Bekijk besluit in PDF-->
<!--        </button>-->
      </div>
    </div>

    <!-- Samenvatting -->
    <div v-if="detail.Samenvatting">
      <h2 class="woo-portal-detail__subtitle">Samenvatting</h2>
      <p v-html="detail.Samenvatting" />
    </div>

    <div v-if="detail.Beschrijving">
      <h2 class="woo-portal-detail__subtitle">Inhoud</h2>
      <div v-html="detail.Beschrijving" />
    </div>

    <!-- Inventariseatielijst -->

    <div>
      <h2 v-if="detail.URL_inventarisatielijst" class="woo-portal-detail__subtitle">Inventarisatielijst</h2>
      <a
          v-if="detail.URL_inventarisatielijst"
          :href="detail.URL_informatieverzoek"
          class="btn"
          target="_blank"
      >
        Bekijk Inventarisatielijst in PDF
      </a>

<!--      <button v-if="!detail.URL_inventarisatielijst" class="btn" disabled>-->
<!--        Bekijk Inventarisatielijst in PDF-->
<!--      </button>-->
    </div>

    <!-- Downloads -->
    <div>
      <h2 class="woo-portal-detail__subtitle" v-if="detail.Bijlagen">
        Bijlagen
      </h2>
      <div v-if="detail.Bijlagen" class="woo-portal-detail__downloads">
        <a
            v-for="download in detail.Bijlagen"
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

      <dt v-if="detail.Object_ID">Referentienummer</dt>
      <dd v-if="detail.Object_ID" v-html="detail.Object_ID"></dd>

      <dt v-if="detail.Behandelstatus">Behandelstatus</dt>
      <dd v-if="detail.Behandelstatus" v-html="detail.Behandelstatus"></dd>

      <dt v-if="detail.Behandelend_bestuursorgaan">
        Behandelend bestuursorgaan
      </dt>
      <dd
          v-if="detail.Behandelend_bestuursorgaan"
          v-html="detail.Behandelend_bestuursorgaan"
      ></dd>
      <dt v-if="detail.Termijnoverschrijding">Termijnoverschrijding</dt>
      <dd
          v-if="detail.Termijnoverschrijding"
          v-html="detail.Termijnoverschrijding"
      ></dd>
      <dt v-if="detail.Besluit">Besluit</dt>
      <dd v-if="detail.Besluit" v-html="detail.Besluit"></dd>

      <dt v-if="detail.Themas">Thema’s</dt>
      <dd v-if="detail.Themas" v-html="themeNames(detail.Themas)" class="themas"></dd>
      <dt v-if="detail.Besluitdatum">Datum ondertekening</dt>
      <dd
          v-if="detail.Besluitdatum"
          v-html="dateReadable(detail.Besluitdatum)"
      ></dd>
      <dt v-if="detail.Partijen">Partijen</dt>
      <dd v-if="detail.Partijen" v-html="detail.Partijen"></dd>
    </dl>
  </aside>
</template>
<script>
export default {
  components: {

  },
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
		if ( this.detail.Titel.length > 0 ) {
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
        array.push(
            "(" +
            str.split('.').pop() +
            // ", XXX kb" +
            ")"
        );
      }

      if (!!download?.Status_Bijlage) {
        array.push('Status: "' + download.Status_Bijlage + '"');
      }

      if (!!download?.Tijdstip_laatste_wijziging_bijlage) {
        array.push(
            this.dateReadable(download.Tijdstip_laatste_wijziging_bijlage)
        );
      }

      return array.join(" - ");
    },
    dateReadable(date) {
      const d = new Date(date);
      return d.toLocaleDateString("nl-NL", {
        year: "numeric",
        month: "long",
        day: "numeric"
      });
    },

    themeNames(themes) {
      return themes.map((obj) => Object.values(obj)).join(", ");
    },
  }
}
</script>