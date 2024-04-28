<template>
	<div v-if="cartData && cartData.cart_details">
		<div class="d-flex justify-content-center my-4">
			<h1>Carrito de compras</h1>
		</div>
		<div class="container-fluid d-flex justify-content-between shopping-cart">
			<div class="card border-light bg-white shadow-sm rounded rounded-3 details">
				<div class="card-header border-bottom bg-white p-3">
					<h2 class="my-auto fs-4 fw-bold">Productos</h2>
				</div>
				<div class="card-body d-flex row mx-0">
					<cart-detail-card v-for="(detail, index) in cartData.cart_details" :key="index" :cart_detail="detail" :product="getProduct(detail)" />
				</div>
			</div>

			<div class="card border-light bg-white shadow-sm rounded rounded-3 w-50 purchase-info ms-4">
				<div class="card-header border-bottom bg-white p-3">
					<h1 class="my-auto fs-5 fw-bold">Resumen de compra</h1>
				</div>
				<div class="card-body bg-white mt-1">
					<div>
						<span>{{ pluralize('Producto', cartData.product_quantity) }} {{ productQuantity(cartData.product_quantity) }}</span>
					</div>
					<div class="d-flex justify-content-between">
						<span class="fw-bold">Total</span>
						<span class="fw-bold">{{ formatCurrency(cartData.total_price) }}</span>
					</div>
				</div>
				<div class="card-footer border-light bg-white d-grid gap-2">
					<button class="btn btn-primary btn-lg" :disabled="cartData.product_quantity == 0" @click="buy">Continuar compra</button>
				</div>
			</div>
		</div>
	</div>

	<div v-else>
		<div class="card border-light bg-white shadow-sm p-3">
			<div class="card-body d-flex justify-content-center flex-wrap">
				<div class="w-100 d-flex justify-content-center my-4">
					<h1>No tienes productos en tu carrito.</h1>
				</div>
				<a href="/" role="button" class="btn btn-primary px-4">Ir al home</a>
			</div>
		</div>
	</div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { successMessage, handlerErrors, deleteMessage, basicMessage } from '@/helpers/Alerts.js';
import CartDetailCard from './CartDetailCard.vue';
import { formatCurrency, pluralize } from '@/helpers/Format.js';

export default {
	props: ['cart_data_prop'],
	components: { CartDetailCard },
	setup({ cart_data_prop }) {
		const cartData = ref(cart_data_prop);

		/* onMounted(() => index());

		const index = () => {
			getCart();
		} */

		const getCart = async () => {
			try {
				const { data } = await axios.get(`/carts/${cart_data_prop.user_id}`);
				cartData.value = data.cart;
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const getProduct = async (cart_detail) => {
			try {
				const { data } = await axios.get(`/products/${cart_detail.product_id}`);
				return data.product;
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const buy = async () => {
			await basicMessage('Esta funcionalidad aún no está disponible.');
		}

		const reloadState = () => {
			/* cartData.value = null; */
			getCart();
		};

		const productQuantity = (quantity) => {
			if (quantity > 1) return `(${quantity})`;
			else return '';
		};

		return {
			cartData,
			formatCurrency,
			pluralize,
			reloadState,
			productQuantity,
			getProduct,
			buy
		}
	}
}
</script>
