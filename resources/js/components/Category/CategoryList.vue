<template>
	<div class="d-flex flex-column">
		<div class="card border-light d-flex justify-content-center mb-3 flex-grow shadow category-card" v-for="(category, index) in categories" :key="index">
			<div class="card-header d-flex justify-content-start align-items-center justify-content-between">
				<h2 class="my-auto mx-3">{{ category.name }}</h2>
				<a :href="'/users'" class="card-link mx-3">Ver más</a>
			</div>
			<div class="card-body d-flex">
				<!-- Products by categoy -->
				<div class="card border-light mx-2 my-3 product-card" v-for="(product, index) in category.products" :key="index">
					<a :href="'/users'" class="content-link">
						<img :src="product.image.route" class="card-img-top" alt="Imagen producto">
						<div class="card-body">
							<h6 class="card-title pb-5">{{ product.format_name }}</h6>
							<div class="d-flex flex-wrap position-absolute bottom-0">
								<span class="w-100 product-price">
									{{ formatCurrency(product.price) }}
								</span>
								<span class="mt-1 px-1 product-stock">
									{{ product.stock }} {{ pluralize('disponible', product.stock) }}
								</span>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { formatCurrency, pluralize } from '@/helpers/Format.js';

export default {
	props: ['categories'],
	//components: {},
	setup({ categories }) {

		console.log(categories);

		return {
			formatCurrency,
			pluralize
		}
	}
}
</script>

<style lang='scss' scoped>
</style>
