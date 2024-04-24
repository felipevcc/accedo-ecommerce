<template>
</template>

<script>
import { ref, onMounted } from 'vue';
import { successMessage, handlerErrors, deleteMessage } from '@/helpers/Alerts.js';
import HandlerModal from '@/helpers/HandlerModal.js';
import ProductModal from './ProductModal.vue';

export default {
	//props: [],
	components: { ProductModal },
	setup(/* props */) {
		return {}
	}
}
</script>
