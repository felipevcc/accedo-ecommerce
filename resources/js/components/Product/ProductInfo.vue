<template>
	<div class="container-fluid bg-white shadow-sm d-flex p-3 product-info">
		<div class="p-4 d-flex row">
			<div class="p-3 d-flex justify-content-start">
				<img :src="product.image.route" class="img-fluid" alt="Imagen de producto">
			</div>
			<div class="p-4 product-description">
				<h2 class="mb-3">Descripción del producto</h2>
				<p class="fs-5">{{ product.description }}</p>
			</div>
		</div>
		<div class="border shadow-sm rounded rounded-3 p-4 w-50">
			<div class="mt-3">
				<h1 class="fs-4 fw-bold">{{ product.name }}</h1>
			</div>
			<div class="d-flex flex-wrap">
				<span class="w-100 product-price fs-1 fw-normal mt-1">
					{{ formatCurrency(product.price) }}
				</span>
				<div class="mt-3">
					<h5 class="fw-bold">{{ product.stock > 0 ? 'Stock disponible' : 'No hay stock' }}</h5>
					<span class="mt-1 px-1 product-stock fw-bold" v-if="product.stock > 0">
						({{ product.stock }} {{ pluralize('disponible', product.stock) }})
					</span>
				</div>
			</div>
			<div class="mt-4 d-grid gap-2 add-cart">
				<button type="button" class="btn btn-primary btn-lg" :disabled="!available(product.stock)" @click="addToCart">Agregar al carrito</button>
			</div>
		</div>
	</div>
</template>

<script>
import { formatCurrency, pluralize } from '@/helpers/Format.js';
import { successMessage, handlerErrors, redirectMessage } from '@/helpers/Alerts.js';

export default {
	props: ['product'],
	//components: {},
	setup({ product }) {
		const available = (stock) => {
			if (stock) return true;
			else return false;
		}

		const addToCart = async () => {
			try {
				await axios.post('/cartDetails/store', { product_id: product.id });
				await successMessage({ text: 'Producto agregado al carrito'});
				if (await redirectMessage({ title: 'Ir al carrito' })) {
					const { data: { auth_user } } = await axios.get('/authUser');
					window.location.href = `/carts/${auth_user.id}`;
				};
			} catch (error) {
				await handlerErrors(error);
			}
		}

		return {
			formatCurrency,
			pluralize,
			available,
			addToCart
		}
	}
}
</script>
