<template>
	<div class="d-flex flex-column">
		<div class="card border-light bg-light d-flex justify-content-center mb-3 flex-grow shadow category-card" v-for="(category, index) in categories" :key="index">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h2 class="my-auto mx-3 fs-4 fw-bold">{{ category.name }}</h2>
				<a :href="`/categories/${category.id}/products`" class="card-link mx-3">Ver más</a>
			</div>
			<div class="card-body d-flex bg-white">
				<!-- Products by categoy -->
				<product-card v-for="(product, index) in category.products" :key="index" :product="product" />
			</div>
		</div>
	</div>
</template>

<script>
import ProductCard from '../Product/ProductCard.vue';

export default {
	props: ['categories'],
	components: { ProductCard },
	setup() {
		return {}
	}
}
</script>
