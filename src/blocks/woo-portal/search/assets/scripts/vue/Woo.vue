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
      <template v-if="!detail.URL_informatieverzoek__is_upload">
        <h3 class="woo-portal-detail__subtitle">URL informatieverzoek</h3>
        <a
            :href="detail.URL_informatieverzoek"
            class="btn"
            target="_blank"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.8 11.2a.6.6 0 0 0 1 0l5.4-5.5a.6.6 0 1 0-1-.9l-5.4 5.5a.6.6 0 0 0 0 .9Zm2.9 0-2.4 2.4c-.7.7-1.9.6-2.7-.2-.7-.8-.9-2-.2-2.7l2.4-2.4a.6.6 0 1 0-1-.9L1.6 9.8c-1.2 1.2-1 3.2.2 4.5.7.6 1.5 1 2.5 1a2.8 2.8 0 0 0 2-.8L8.6 12a.6.6 0 0 0-1-.9Zm6.6-9.5C13 .4 11 .3 9.8 1.5L7.4 4a.6.6 0 0 0 1 .9l2.3-2.4c.7-.7 1.9-.6 2.7.2.8.8.9 2 .2 2.7l-2.4 2.4a.6.6 0 0 0 1 .9l2.3-2.4c1.2-1.2 1-3.2-.2-4.5Z" fill="#fff"/></svg>
          Bekijk informatieverzoek
        </a>
      </template>
      <template v-else>
      <h3 class="woo-portal-detail__subtitle">Bijlage informatieverzoek</h3>
      <a
          :href="detail.URL_informatieverzoek"
          class="woo-portal-detail__download"
          :download="fileName(detail.URL_informatieverzoek)"
          target="_blank"
      >
        <div class="woo-portal-detail__download-circle">
          <svg aria-hidden="true" width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h3
            v-html="fileName(detail.URL_informatieverzoek)"
            class="woo-portal-detail__download-title"
        />
      </a>
      </template>
    </div>
    <!--        <button v-if="!detail.URL_informatieverzoek" class="btn" disabled>-->
    <!--          Bekijk informatieverzoek in PDF-->
    <!--        </button>-->

    <!-- Inventariseatielijst -->
    <div v-if="detail.URL_inventarisatielijst">
      <h2>Inventarisatielijst</h2>
      <template v-if="!detail.URL_inventarisatielijst__is_upload">
        <h3 class="woo-portal-detail__subtitle">URL Inventarisatielijst</h3>
        <a
            :href="detail.URL_inventarisatielijst"
            class="btn"
            target="_blank"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.8 11.2a.6.6 0 0 0 1 0l5.4-5.5a.6.6 0 1 0-1-.9l-5.4 5.5a.6.6 0 0 0 0 .9Zm2.9 0-2.4 2.4c-.7.7-1.9.6-2.7-.2-.7-.8-.9-2-.2-2.7l2.4-2.4a.6.6 0 1 0-1-.9L1.6 9.8c-1.2 1.2-1 3.2.2 4.5.7.6 1.5 1 2.5 1a2.8 2.8 0 0 0 2-.8L8.6 12a.6.6 0 0 0-1-.9Zm6.6-9.5C13 .4 11 .3 9.8 1.5L7.4 4a.6.6 0 0 0 1 .9l2.3-2.4c.7-.7 1.9-.6 2.7.2.8.8.9 2 .2 2.7l-2.4 2.4a.6.6 0 0 0 1 .9l2.3-2.4c1.2-1.2 1-3.2-.2-4.5Z" fill="#fff"/></svg>
          Bekijk Inventarisatielijst
        </a>
      </template>
      <template v-else>
        <h3 class="woo-portal-detail__subtitle">Bijlage Inventarisatielijst</h3>
        <a
            :href="detail.URL_inventarisatielijst"
            class="woo-portal-detail__download"
            :download="fileName(detail.URL_inventarisatielijst)"
            target="_blank"
        >
          <div class="woo-portal-detail__download-circle">
            <svg aria-hidden="true" width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3
              v-html="fileName(detail.URL_inventarisatielijst)"
              class="woo-portal-detail__download-title"
          />
        </a>
      </template>
    </div>
    <!--      <button v-if="!detail.URL_inventarisatielijst" class="btn" disabled>-->
    <!--        Bekijk Inventarisatielijst in PDF-->
    <!--      </button>-->

    <div v-if="detail.URL_besluit">
      <h2>Besluit</h2>
      <template v-if="!detail.URL_besluit__is_upload">
        <h3 class="woo-portal-detail__subtitle">URL Besluit</h3>
        <a
            :href="detail.URL_besluit"
            class="btn"
            target="_blank"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.8 11.2a.6.6 0 0 0 1 0l5.4-5.5a.6.6 0 1 0-1-.9l-5.4 5.5a.6.6 0 0 0 0 .9Zm2.9 0-2.4 2.4c-.7.7-1.9.6-2.7-.2-.7-.8-.9-2-.2-2.7l2.4-2.4a.6.6 0 1 0-1-.9L1.6 9.8c-1.2 1.2-1 3.2.2 4.5.7.6 1.5 1 2.5 1a2.8 2.8 0 0 0 2-.8L8.6 12a.6.6 0 0 0-1-.9Zm6.6-9.5C13 .4 11 .3 9.8 1.5L7.4 4a.6.6 0 0 0 1 .9l2.3-2.4c.7-.7 1.9-.6 2.7.2.8.8.9 2 .2 2.7l-2.4 2.4a.6.6 0 0 0 1 .9l2.3-2.4c1.2-1.2 1-3.2-.2-4.5Z" fill="#fff"/></svg>
          Bekijk besluit
        </a>
      </template>
      <template v-else>
        <h3 class="woo-portal-detail__subtitle">Bijlage Besluit</h3>
        <a
            :href="detail.URL_besluit"
            class="woo-portal-detail__download"
            :download="fileName(detail.URL_besluit)"
            target="_blank"
        >
          <div class="woo-portal-detail__download-circle">
            <svg aria-hidden="true" width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3
              v-html="fileName(detail.URL_besluit)"
              class="woo-portal-detail__download-title"
          />
        </a>
      </template>
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
            <svg aria-hidden="true" width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
