<template>
	<div class="card bg-white border-light mx-2 my-3 product-card">
		<a :href="`/products/${product.id}`" class="content-link">
			<img :src="product.image.route" class="card-img-top" alt="Imagen producto">
			<div class="card-body d-flex flex-column justify-content-end">
				<h6 class="card-title">{{ product.format_name }}</h6>
				<div class="d-flex flex-wrap">
					<span class="w-100 product-price fs-4 fw-normal">
						{{ formatCurrency(product.price) }}
					</span>
					<span class="mt-1 px-1 product-stock fw-bold">
						{{ productAvailable(product.stock) }} {{ pluralize('disponible', product.stock) }}
					</span>
				</div>
			</div>
		</a>
	</div>
</template>

<script>
import { formatCurrency, pluralize } from '@/helpers/Format.js';

export default {
	props: ['product'],
	setup() {
		const productAvailable = (stock) => {
			if (stock > 10) return '+10';
			else return stock;
		}

		return {
			formatCurrency,
			pluralize,
			productAvailable
		}
	}
}
</script>
