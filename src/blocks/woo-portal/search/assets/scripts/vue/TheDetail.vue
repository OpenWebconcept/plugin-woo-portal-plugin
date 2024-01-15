<template v-cloak>
  <section v-if="selectedId" class="woo-portal-detail-wrapper">
    <article class="woo-portal-detail">
      <button @click="closeDetail" class="woo-portal-detail__btn--back">
        <svg
            width="10"
            height="16"
            viewBox="0 0 10 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
          <path
              d="M9.88 1.88L8 0L0 8L8 16L9.88 14.12L3.77333 8L9.88 1.88Z"
              fill="none"
          />
        </svg>
        Terug naar resultaten
      </button>

      <div v-if="loadingDetail" class="woo-portal-detail--loading">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
        >
          <path
              d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
              opacity=".25"
          />
          <path
              d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z"
              class="spinner"
          />
        </svg>
      </div>
      <woo-component
          v-if="detail.type ? detail.type.toLowerCase().match('woo') : false"
          :loading="loadingDetail"
          :detail="detail"
          :typeClass="typeClass(detail.type)"/>
      <convenant-component
          v-if="detail.type ? detail.type.toLowerCase().match('convenant') : false"
          :loading="loadingDetail"
          :detail="detail"
          :typeClass="typeClass(detail.type)" />
    </article>
  </section>
</template>

<script>
import WooComponent from "./Woo.vue";
import ConvenantComponent from "./Convenant.vue";
export default {
  components: {
    WooComponent,
    ConvenantComponent
  },
  props: {
    restUrl: {
      type: [String, Boolean],
      required: false,
      default: ''
    },
		pageTitle: {
			type: [String, Boolean],
			required: false,
			default: ''
		},
  },
  data() {
    return {
      selectedId: "",
      detail: [],
      loadingDetail: false,
    };
  },
  methods: {
    closeDetail() {
      this.selectedId = null;
      this.detail = null;
      window.history.replaceState({}, this.pageTitle, "/" + "");
			document.title = this.pageTitle;
    },

    openDetail(restUrl) {
      this.loadingDetail = true;
      this.selectedId = restUrl.substring(restUrl.lastIndexOf('/') + 1);
      const object = sessionStorage.getItem(this.selectedId);
      if (!!object) {
        this.detail = JSON.parse(object);
        this.loadingDetail = false;
      }
      else {
        fetch(
            restUrl
        )
            .then((response) => response.json())
            .then((data) => {
              data.hasOwnProperty('WOOverzoeken') ?
              this.detail = {
                ...data.WOOverzoeken.pop(),
                ...{type: "Woo-verzoek"}
              } :  this.detail = {
              ...data.Convenantenverzoeken.pop(),
              ...{ type: "Convenant" }
              };
              this.loadingDetail = false;

              sessionStorage.setItem(
                  this.selectedId,
                  JSON.stringify(this.detail)
              );
            });
				}
    },

    typeClass(type) {
      return "woo-portal-result__type woo-portal-result__type--" + type.toLowerCase();
    },
  },

  mounted() {
    if ( this.restUrl ) {
      this.openDetail( this.restUrl )
    }
  }
};
</script>

<!-- Use preprocessors via the lang attribute! e.g. <style lang="scss"> -->
<style lang="scss">

.woo-portal-result__type--convenant {
  background-color: var(--owp-color-secondary);
  display: block;
}

.woo-portal-result__type--woo-verzoek {
  background-color: var(--owp-color-primary);
  display: block;
}

.woo-portal-aside {
  background: var(--owp-search-background);
  grid-area: filters;
  width: 320px;
  h3 {
    font-weight: 700;
    font-size: 20px;
    line-height: 100%;
    margin-block: 0;
  }
}

.woo-portal-detail-wrapper {
  padding-block: 160px;
  background: var(--owp-search-background);
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  height: 100%;
  width: 100vw;
  min-height: 100%;
  overflow-y: auto;
  z-index: 999;
}

.woo-portal-detail {
  position: relative;
  grid-template-areas: "detail-content detail-content detail-aside";
  flex-direction: row;
  align-items: flex-start;
  margin-inline: auto;
  padding: calc(var(--padding) * 2 ) var(--padding);
  text-align: left;
  background: #ffffff;
  /* contact shadow */
  box-shadow: 0px 6px 15px rgba(4, 50, 89, 0.15);
  border-radius: 8px;
  width: min(calc(100% - var(--padding)), 1160px);
  @media (min-width: 600px) {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
    padding: 80px 40px;
  }
  @media (min-width: 900px) {
    width: min(calc(100% - 140px), 1160px);
  }
  @media (min-width: 1200px) {
    padding: 80px;
    gap: 80px;
    width: min(calc(100% - 280px), 1160px);
  }
  p {
    font-weight: 400;
    font-size: 18px;
    line-height: 170%;
		margin: 0;
  }
}

.woo-portal-detail__content {
  grid-area: detail-content;
  display: flex;
  flex-direction: column;
  gap: 40px;
}
.woo-portal-detail__aside {
  grid-area: detail-aside;
  position: sticky;
  top: 0;
}

.woo-portal-detail__btn--back {
  align-items: center;
  border: 0;
  background: transparent;
  position: absolute;
  top: -24px;
  left: 0;
  transform: translateY(-100%);
  display: flex;
  flex-direction: row;
  gap: 16px;
  color: var(--owp-color);
  text-decoration: none;
  svg path {
    fill: var(--owp-color);
  }
  &:hover {
    text-decoration: underline;
  }
}

.woo-portal-detail .spinner {
  transform-origin: center;
  animation: spinner 0.75s infinite linear;
}
@keyframes spinner {
  100% {
    transform: rotate(360deg);
  }
}

.woo-portal-detail dl {
  margin-block: 0;
}

.woo-portal-detail dt {
  font-weight: 700;
  font-size: 14px;
  line-height: 1.7;
}
.woo-portal-detail dd {
  font-weight: 400;
  font-size: 14px;
  line-height: 1.7;
  margin-inline-start: 0;
  margin-block-start: 4px;
  white-space: normal;
  &.themas, &.partijen {
    display: table-caption;
  }
}

.woo-portal-detail dd + dt {
  margin-block-start: 28px;
}

.woo-portal-detail pre {
  background: lightgrey;
  overflow-x: scroll;
}

.woo-portal-detail__download {
  align-items: center;
  border: 1px solid #d2d2d2;
  color: #333333;

  display: grid;
  grid-template-areas: "download-icon download-title" "download-icon download-meta";
  grid-template-columns: 64px calc(100% - 64px);

  padding-block: 24px;
  padding-inline: 16px;
  column-gap: 24px;
  row-gap: 0;

  text-decoration: none;
  svg {
    transition: transform 125ms ease-in-out;
  }

  p {
    margin-block: 0;
  }
}

.woo-portal-detail__downloads {
  display: flex;
  gap: 4px;
  flex-direction: column;
  margin-bottom: 28px;
}

.woo-portal-detail__download-circle {
  grid-area: download-icon;
  aspect-ratio: 1;
  background: var(--owp-color-search-button);
  clip-path: circle(50%);
  display: grid;
  place-items: center;
  width: 64px;
  height: auto;
  svg, svg path {
    stroke: var(--owp-color-search-button-text-color);
  }
}

.woo-portal-detail__download:focus-visible {
  outline: 2px dashed #000;
}
.woo-portal-detail__download:hover svg {
  transform: translateY(-2px);
}

.woo-portal-detail__download:active svg {
  transform: translateY(2px);
}

.woo-portal-detail__title {
  font-weight: 700;
  font-size: 2rem;
  line-height: 120%;
  margin-block: 0;
  @media (min-width: 990px) {
    font-size: 3rem;
  }
}

.woo-portal-detail h2 {
  font-weight: 700;
  font-size: 1.125rem;
  line-height: 170%;
  margin-block-start: 0;
  margin-block-end: 4px;
}

.woo-portal-detail__subtitle {
  font-weight: 700;
  font-size: 1rem;
  line-height: 170%;
  margin-block-start: 0;
  margin-block-end: 4px;
}

.woo-portal-detail__download-title {
  font-weight: 700;
  font-size: 20px;
  line-height: 130%;
  grid-area: download-title;
  margin-block: 0;
  color: var(--owp-color);
}

.woo-portal-detail__download-meta {
  grid-area: download-meta;
  color: var(--owp-color);
}

.woo-portal-detail .btn {
  font-style: normal;
  font-weight: 400;
  font-size: 12px;
  line-height: 150%;
  color: var(--owp-color-search-button-text-color);
  background: var(--owp-color-search-button);
  border: 0;
  border-radius: 3px;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding: 8px 16px;
  text-decoration: none;
  width: fit-content;
	font-weight: 700;
	margin-bottom: 16px ;
  &:hover {
    background: var(--owp-color-search-button-tint);
  }
  &:active, &:focus {
    background: var(--owp-color-search-button-shade);
  }
  &:disabled {
    background: #ccc;
  }
	svg {
		margin-right: 12px;
	}
}

.woo-portal-detail__columns {
  display: grid;
  grid-template-columns: 1fr;
  @media (min-width: 678px) {
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }
}

.woo-portal-detail__column {
  display: flex;
  flex-direction: column;
  gap: 8px;

  * {
    margin-block: 0;
  }
}
</style>
