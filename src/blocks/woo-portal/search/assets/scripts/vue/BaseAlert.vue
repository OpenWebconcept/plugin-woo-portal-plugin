<script setup>
import { defineProps } from 'vue';

defineProps({
	type: {
		type: String,
		default: 'error',
	},
	message: {
		type: String,
		default: '',
		required: true,
	},
});
</script>

<template v-cloak>
	<div class="alert" :class="'alert-' + type" v-html="message" />
</template>

<style scoped>
.alert {
	padding: 1rem;
	margin-bottom: 1rem;
	border-radius: 4px;
	font-size: 1.2rem;
	font-weight: bold;
	text-align: center;
}

.alert-success {
	background-color: #d4edda;
	color: #155724;
}

.alert-warning {
	background-color: #fff3cd;
	color: #856404;
}

.alert-error {
	background-color: #f8d7da;
	color: #721c24;
}
</style>
