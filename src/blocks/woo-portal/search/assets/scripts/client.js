import {createApp} from 'vue';
import App from './vue/App.vue';

createApp( App, {
  ...document.getElementById( 'woo-portal-search' ).dataset,
} ).mount( '#woo-portal-search' );
