<template>
	<div class="card bg-white border-light rounded-0 cart-detail-card" v-if="product.image.route">
		<div class="row g-0">
			<div class="col-md-4 img-cont">
				<img :src="product.image.route" class="card-img-top img-fluid" alt="Imagen producto" @click="showProduct">
			</div>
			<div class="col-md-8 p-3">
				<div class="card-body d-flex row">
					<div class="d-flex justify-content-between">
						<div class="">
							<h6 class="card-title fs-5 mt-2 " @click="showProduct">{{ product.format_name }}</h6>
							<div>
								<a @click="deleteCartProduct" role="button" class="card-link fw-bold">Eliminar</a>
								<a @click="buyProduct" role="button" class="card-link fw-bold">Comprar ahora</a>
							</div>
						</div>
						<div class="d-flex row justify-content-center text-center">
							<div class="input-group d-flex justify-content-center text-center">
								<button type="button" class="btn btn-primary btn-sm" @click="decreaseAmount" :disabled="detail.amount <= 1">-</button>
								<input type="number" v-model="detail.amount" :class="{ 'is-invalid': amountError }" @change="changeAmount" />
								<button type="button" class="btn btn-primary btn-sm" @click="increaseAmount" :disabled="detail.amount >= product.stock">+</button>
							</div>
							<span class="invalid-feedback d-block" v-if="amountError">{{ amountError }}</span>
							<span class="mt-1 px-1 product-stock text-body-secondary" v-else>
								{{ product.stock }} {{ pluralize('disponible', product.stock) }}
							</span>
						</div>
						<div class="d-flex flex-wrap mt-2">
							<span class="w-100 product-price fs-4 fw-normal">
								{{ formatCurrency(product.price) }}
							</span>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div v-else>
		NOTHING
	</div>
</template>

<script>
import { ref, onMounted, getCurrentInstance } from 'vue';
import { formatCurrency, pluralize } from '@/helpers/Format.js';
import { successMessage, handlerErrors, deleteMessage, warningMessage } from '@/helpers/Alerts.js';

export default {
	props: ['cart_detail'],
	//components: {},
	setup({ cart_detail }) {
		const instance = getCurrentInstance();
		const product = ref({
			image: {}
		});
		const detail = ref(cart_detail);
		const amountError = ref('');

		onMounted (() => {
			getProduct();
		});

		const getProduct = async () => {
			try {
				const { data } = await axios.get(`/products/${cart_detail.product_id}`);
				product.value = data.product;
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const increaseAmount = async () => {
			detail.value.amount++;
			changeAmount();
		};

		const decreaseAmount = () => {
			detail.value.amount--;
			changeAmount();
		};

		const changeAmount = () => {
			if (detail.value.amount < 1) {
				amountError.value = 'Puedes comprar desde 1 u.';
			} else if (detail.value.amount > product.value.stock) {
				amountError.value = 'No hay suficiente stock.';
			} else {
				amountError.value = '';
				updateCartDetail();
			}
		};

		const updateCartDetail = async () => {
			try {
				await axios.put(`/cartDetails/${cart_detail.id}`, detail.value);
				reloadCart();
			} catch (error) {
				await handlerErrors(error);
				reloadCart();
			}
		};

		const deleteCartProduct = async () => {
			try {
				await axios.delete(`/cartDetails/${cart_detail.id}`);
				reloadCart();
			} catch (error) {
				await handlerErrors(error);
				reloadCart();
			}
		};

		const buyProduct = () => {
			instance.parent.ctx.buy();
		};

		const reloadCart = async () => {
			await getProduct();
			instance.parent.ctx.reloadState();
		};

		const showProduct = () => {
			window.location.href = `/products/${product.value.id}`;
		};

		return {
			product,
			formatCurrency,
			pluralize,
			updateCartDetail,
			deleteCartProduct,
			showProduct,
			buyProduct,
			increaseAmount,
			decreaseAmount,
			detail,
			amountError,
			changeAmount
		}
	}
}
</script>
