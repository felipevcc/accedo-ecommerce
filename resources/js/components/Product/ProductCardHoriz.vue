<template>
	<div class="card bg-white border-light rounded-0 product-card-horiz">
		<a :href="`/products/${product.id}`" class="content-link">
			<div class="row g-0">
				<div class="col-md-4 img-cont">
					<img :src="product.image.route" class="card-img-top img-fluid" alt="Imagen producto">
				</div>
				<div class="col-md-8 p-3">
					<div class="card-body">
						<h6 class="card-title fs-4 text-body-secondary mt-2">{{ product.name }}</h6>
						<div class="d-flex flex-wrap mt-2">
							<span class="w-100 product-price fs-3 fw-normal">
								{{ formatCurrency(product.price) }}
							</span>
							<span class="mt-1 px-1 product-stock fw-bold">
								{{ productAvailable(product.stock) }} {{ pluralize('disponible', product.stock) }}
							</span>
						</div>
					</div>
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
