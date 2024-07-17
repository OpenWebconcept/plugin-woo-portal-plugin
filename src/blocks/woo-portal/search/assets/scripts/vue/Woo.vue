<template v-cloak>
  <div class="woo-portal-detail__content" v-if="!loading">
    <div>
      <h1
          class="woo-portal-detail__title"
          v-if="detail.Titel"
          v-html="detail.Titel"
      />
    </div>
    <!-- Kenmerk -->
    <div v-if="detail.ID">
      <h2 class="woo-portal-detail__subtitle">Kenmerk</h2>
      <p v-html="detail.ID"/>
    </div>
    <!-- Verzoek + Besluit -->
    <div class="woo-portal-detail__columns">
      <div class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Ontvangstdatum</h2>
        <time
            v-if="detail.Ontvangstdatum"
            v-html="dateReadable(detail.Ontvangstdatum)"
            :datetime="detail.Ontvangstdatum"
        />
      </div>

      <div class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Besluitdatum</h2>
        <time
            v-if="detail.Besluitdatum"
            v-html="dateReadable(detail.Besluitdatum)"
            :datetime="detail.Besluitdatum"
        />
        <!--        <button v-if="!detail.URL_besluit" class="btn" disabled>-->
        <!--          Bekijk besluit in PDF-->
        <!--        </button>-->
      </div>
    </div>

    <!-- Samenvatting -->
    <div v-if="detail.Samenvatting">
      <h2 class="woo-portal-detail__subtitle">Samenvatting</h2>
      <p v-html="detail.Samenvatting"/>
    </div>

    <!-- informatieverzoek-->
    <div v-if="detail.URL_informatieverzoek">
      <h2>Informatieverzoek</h2>
      <h3 class="woo-portal-detail__subtitle">URL informatieverzoek</h3>
      <a
          :href="detail.URL_informatieverzoek"
          class="btn"
          target="_blank"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <g>
            <path
                d="M4.83063 11.1696C4.95069 11.2898 5.11355 11.3573 5.28339 11.3573C5.45329 11.3573 5.61615 11.2898 5.73621 11.1696L11.1762 5.7296C11.338 5.56783 11.4012 5.33205 11.3419 5.11105C11.2827 4.89012 11.1101 4.71751 10.8892 4.65829C10.6682 4.59907 10.4324 4.66225 10.2706 4.82403L4.83063 10.264C4.71043 10.3841 4.64293 10.5469 4.64293 10.7168C4.64293 10.8867 4.71043 11.0495 4.83063 11.1696ZM7.65143 11.2L5.28023 13.584C4.61143 14.2528 3.42743 14.1552 2.64023 13.3696C1.85305 12.584 1.74742 11.3968 2.41622 10.728L4.80022 8.34884C4.96199 8.18707 5.02517 7.9513 4.96595 7.7303C4.90674 7.50931 4.73413 7.3367 4.51314 7.27748C4.29221 7.21826 4.05637 7.28144 3.89459 7.44322L1.51219 9.82242C0.344168 10.9904 0.440221 12.9872 1.72657 14.2736H1.72662C2.36626 14.9248 3.23795 15.2959 4.15062 15.3056C4.52562 15.3101 4.89786 15.2403 5.24578 15.1003C5.5937 14.9602 5.91047 14.7527 6.17781 14.4896L8.55701 12.1104C8.71961 11.9486 8.78357 11.7124 8.72482 11.4907C8.66602 11.2689 8.49342 11.0954 8.27201 11.0355C8.05059 10.9755 7.81398 11.0382 7.65138 11.2L7.65143 11.2ZM14.2738 1.72642C12.9938 0.446416 10.9906 0.344016 9.82263 1.51043L7.44343 3.88963C7.28083 4.0514 7.21687 4.28766 7.27562 4.50936C7.33442 4.73108 7.50702 4.90463 7.72843 4.96458C7.94985 5.02452 8.18646 4.96182 8.34906 4.80004L10.7283 2.41604C11.3971 1.74724 12.5811 1.84484 13.3683 2.63042C14.1554 3.41604 14.253 4.60322 13.5842 5.28002L11.2002 7.65122C11.0385 7.81299 10.9753 8.04876 11.0345 8.26976C11.0937 8.49075 11.2663 8.66336 11.4873 8.72258C11.7083 8.78179 11.9441 8.71862 12.1059 8.55684L14.4883 6.17764C15.6562 5.00962 15.5602 3.01284 14.2738 1.72644L14.2738 1.72642Z"
                fill="white"/>
          </g>
        </svg>
        Bekijk informatieverzoek
      </a>
      <h3 class="woo-portal-detail__subtitle">Bijlage informatieverzoek</h3>
      <a
          :href="detail.URL_informatieverzoek"
          class="woo-portal-detail__download"
          :download="fileName(detail.URL_informatieverzoek)"
          target="_blank"
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
            v-html="fileName(detail.URL_informatieverzoek)"
            class="woo-portal-detail__download-title"
        />
      </a>
    </div>
    <!--        <button v-if="!detail.URL_informatieverzoek" class="btn" disabled>-->
    <!--          Bekijk informatieverzoek in PDF-->
    <!--        </button>-->

    <!-- Inventariseatielijst -->
    <div v-if="detail.URL_inventarisatielijst">
      <h2>Inventarisatielijst</h2>
      <h3 class="woo-portal-detail__subtitle">URL Inventarisatielijst</h3>
      <a
          :href="detail.URL_inventarisatielijst"
          class="btn"
          target="_blank"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <g>
            <path
                d="M4.83063 11.1696C4.95069 11.2898 5.11355 11.3573 5.28339 11.3573C5.45329 11.3573 5.61615 11.2898 5.73621 11.1696L11.1762 5.7296C11.338 5.56783 11.4012 5.33205 11.3419 5.11105C11.2827 4.89012 11.1101 4.71751 10.8892 4.65829C10.6682 4.59907 10.4324 4.66225 10.2706 4.82403L4.83063 10.264C4.71043 10.3841 4.64293 10.5469 4.64293 10.7168C4.64293 10.8867 4.71043 11.0495 4.83063 11.1696ZM7.65143 11.2L5.28023 13.584C4.61143 14.2528 3.42743 14.1552 2.64023 13.3696C1.85305 12.584 1.74742 11.3968 2.41622 10.728L4.80022 8.34884C4.96199 8.18707 5.02517 7.9513 4.96595 7.7303C4.90674 7.50931 4.73413 7.3367 4.51314 7.27748C4.29221 7.21826 4.05637 7.28144 3.89459 7.44322L1.51219 9.82242C0.344168 10.9904 0.440221 12.9872 1.72657 14.2736H1.72662C2.36626 14.9248 3.23795 15.2959 4.15062 15.3056C4.52562 15.3101 4.89786 15.2403 5.24578 15.1003C5.5937 14.9602 5.91047 14.7527 6.17781 14.4896L8.55701 12.1104C8.71961 11.9486 8.78357 11.7124 8.72482 11.4907C8.66602 11.2689 8.49342 11.0954 8.27201 11.0355C8.05059 10.9755 7.81398 11.0382 7.65138 11.2L7.65143 11.2ZM14.2738 1.72642C12.9938 0.446416 10.9906 0.344016 9.82263 1.51043L7.44343 3.88963C7.28083 4.0514 7.21687 4.28766 7.27562 4.50936C7.33442 4.73108 7.50702 4.90463 7.72843 4.96458C7.94985 5.02452 8.18646 4.96182 8.34906 4.80004L10.7283 2.41604C11.3971 1.74724 12.5811 1.84484 13.3683 2.63042C14.1554 3.41604 14.253 4.60322 13.5842 5.28002L11.2002 7.65122C11.0385 7.81299 10.9753 8.04876 11.0345 8.26976C11.0937 8.49075 11.2663 8.66336 11.4873 8.72258C11.7083 8.78179 11.9441 8.71862 12.1059 8.55684L14.4883 6.17764C15.6562 5.00962 15.5602 3.01284 14.2738 1.72644L14.2738 1.72642Z"
                fill="white"/>
          </g>
        </svg>
        Bekijk Inventarisatielijst
      </a>
      <h3 class="woo-portal-detail__subtitle">Bijlage Inventarisatielijst</h3>
      <a
          :href="detail.URL_inventarisatielijst"
          class="woo-portal-detail__download"
          :download="fileName(detail.URL_inventarisatielijst)"
          target="_blank"
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
            v-html="fileName(detail.URL_inventarisatielijst)"
            class="woo-portal-detail__download-title"
        />
      </a>
    </div>
    <!--      <button v-if="!detail.URL_inventarisatielijst" class="btn" disabled>-->
    <!--        Bekijk Inventarisatielijst in PDF-->
    <!--      </button>-->

    <div v-if="detail.URL_besluit">
      <h2>Besluit</h2>
      <h3 class="woo-portal-detail__subtitle">URL Besluit</h3>
      <a
          v-if="detail.URL_besluit"
          :href="detail.URL_besluit"
          class="btn"
          target="_blank"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <g>
            <path
                d="M4.83063 11.1696C4.95069 11.2898 5.11355 11.3573 5.28339 11.3573C5.45329 11.3573 5.61615 11.2898 5.73621 11.1696L11.1762 5.7296C11.338 5.56783 11.4012 5.33205 11.3419 5.11105C11.2827 4.89012 11.1101 4.71751 10.8892 4.65829C10.6682 4.59907 10.4324 4.66225 10.2706 4.82403L4.83063 10.264C4.71043 10.3841 4.64293 10.5469 4.64293 10.7168C4.64293 10.8867 4.71043 11.0495 4.83063 11.1696ZM7.65143 11.2L5.28023 13.584C4.61143 14.2528 3.42743 14.1552 2.64023 13.3696C1.85305 12.584 1.74742 11.3968 2.41622 10.728L4.80022 8.34884C4.96199 8.18707 5.02517 7.9513 4.96595 7.7303C4.90674 7.50931 4.73413 7.3367 4.51314 7.27748C4.29221 7.21826 4.05637 7.28144 3.89459 7.44322L1.51219 9.82242C0.344168 10.9904 0.440221 12.9872 1.72657 14.2736H1.72662C2.36626 14.9248 3.23795 15.2959 4.15062 15.3056C4.52562 15.3101 4.89786 15.2403 5.24578 15.1003C5.5937 14.9602 5.91047 14.7527 6.17781 14.4896L8.55701 12.1104C8.71961 11.9486 8.78357 11.7124 8.72482 11.4907C8.66602 11.2689 8.49342 11.0954 8.27201 11.0355C8.05059 10.9755 7.81398 11.0382 7.65138 11.2L7.65143 11.2ZM14.2738 1.72642C12.9938 0.446416 10.9906 0.344016 9.82263 1.51043L7.44343 3.88963C7.28083 4.0514 7.21687 4.28766 7.27562 4.50936C7.33442 4.73108 7.50702 4.90463 7.72843 4.96458C7.94985 5.02452 8.18646 4.96182 8.34906 4.80004L10.7283 2.41604C11.3971 1.74724 12.5811 1.84484 13.3683 2.63042C14.1554 3.41604 14.253 4.60322 13.5842 5.28002L11.2002 7.65122C11.0385 7.81299 10.9753 8.04876 11.0345 8.26976C11.0937 8.49075 11.2663 8.66336 11.4873 8.72258C11.7083 8.78179 11.9441 8.71862 12.1059 8.55684L14.4883 6.17764C15.6562 5.00962 15.5602 3.01284 14.2738 1.72644L14.2738 1.72642Z"
                fill="white"/>
          </g>
        </svg>
        Bekijk besluit
      </a>
      <h3 class="woo-portal-detail__subtitle">Bijlage Besluit</h3>
      <a
          :href="detail.URL_besluit"
          class="woo-portal-detail__download"
          :download="fileName(detail.URL_besluit)"
          target="_blank"
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
            v-html="fileName(detail.URL_besluit)"
            class="woo-portal-detail__download-title"
        />
      </a>
    </div>

    <!-- Postcode gebied + Geografisch gebied -->
    <div class="woo-portal-detail__columns">
      <div v-if="detail.Postcodegebied" class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Postcodegebied</h2>
        <time
            v-if="detail.Postcodegebied"
            v-html="detail.Postcodegebied"
        />
      </div>

      <div v-if="detail.Geografisch_gebied" class="woo-portal-detail__column">
        <h2 class="woo-portal-detail__subtitle">Geografisch gebied</h2>
        <time
            v-if="detail.Geografisch_gebied"
            v-html="detail.Geografisch_gebied"
        />
      </div>
    </div>

    <!-- Adres + Geografisch gebied -->
    <div v-if="detail.Adres">
      <h2 style="margin-bottom: -35px;">Adres</h2>
    </div>
    <div class="woo-portal-detail__columns">
      <div class="woo-portal-detail__column">
        <h3 v-if="detail.Adres && detail.Adres.Adres" class="woo-portal-detail__subtitle">Staat + Huisnummer</h3>
        <div
            v-if="detail.Adres && detail.Adres.Adres"
            v-html="detail.Adres.Adres"
        />
        <h3 v-if="detail.Adres && detail.Adres.Stad" class="woo-portal-detail__subtitle">Stad</h3>
        <div
            v-if="detail.Adres && detail.Adres.Stad"
            v-html="detail.Adres.Stad"
        />
      </div>

      <div class="woo-portal-detail__column">
        <h3 v-if="detail.Adres && detail.Adres.Postcode" class="woo-portal-detail__subtitle">Postcode</h3>
        <div
            v-if="detail.Adres && detail.Adres.Postcode"
            v-html="detail.Adres.Postcode"
        />
      </div>
    </div>

    <div v-if="detail.Geografische_positie">
      <h2>Geografische positie</h2>
      <div id="map" class="woo-portal-detail__map"></div>
    </div>

    <!-- Downloads -->
    <div>
      <h3 class="woo-portal-detail__subtitle" v-if="detail.Bijlagen">
        Bijlagen
      </h3>
      <div v-if="detail.Bijlagen" class="woo-portal-detail__downloads">
        <a
            v-for="download in detail.Bijlagen"
            :href="download.URL_Bijlage"
            class="woo-portal-detail__download"
            :download="fileName(download.URL_Bijlage)"
            target="_blank"
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

      <dt v-if="detail.Ontvanger_informatieverzoek">Ontvanger informatieverzoek</dt>
      <dd v-if="detail.Ontvanger_informatieverzoek" v-html="detail.Ontvanger_informatieverzoek"></dd>

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
    if (this.detail.Titel.length > 0) {
      document.title += ' - ' + this.detail.Titel;
    }
    if (this.detail.Geografische_positie) {
      this.addScriptToHead('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js')
      this.addStylesheetToHead('https://unpkg.com/leaflet@1.7.1/dist/leaflet.css')
    }
  },
  mounted() {
    setTimeout(() => {
      if (document.getElementById('map') && this.detail.Geografische_positie) {
        this.initmap(this.detail.Geografische_positie);
      }
    }, 500)
  },
  methods: {
    fileName(url) {
      return url.substring(url.lastIndexOf('/') + 1)
    },
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
      return themes.map((obj) => {
        return Object.values(obj).map(value => ' ' + value)
      }).join(',');
    },
    //leaflet script for openstreetmap
    addScriptToHead(src) {
      const script = document.createElement('script');
      script.src = src;
      script.id = 'leaflet-js';
      script.async = true;
      document.head.appendChild(script);
    },
    //leaflet stylesheet for openstreetmap
    addStylesheetToHead(src) {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.id = 'leaflet_style-css';
      link.href = src;
      link.media = 'all';
      document.head.appendChild(link);
    },
    initmap(location) {
      let mapOptions = {
        center: [location.Latitude, location.Longitude],
        zoom: 13
      }
      let map = new L.map('map', mapOptions);
      let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
      map.addLayer(layer);
      let marker = new L.Marker([location.Latitude, location.Longitude]);
      marker.addTo(map);
    },
  }
}
</script>
<style lang="scss" scoped>
#map {
  height: 222px;
  width: 100%;
}
</style>
