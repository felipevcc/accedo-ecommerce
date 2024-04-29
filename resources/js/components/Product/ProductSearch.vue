<template>
	<div class="d-flex justify-content-between search">
		<div class="my-4 search__title me-4" v-if="products.length > 0">
			<h1 class="fs-1">Resultados</h1>
		</div>
		<div class="d-flex row products" v-if="products.length > 0">
			<product-card v-for="(product, index) in products" :key="index" :product="product" />
		</div>
		<div class="shadow mx-auto p-5 rounded bg-white" v-else>
			<h3 class="fw-bold">No hay publicaciones que coincidan con tu búsqueda.</h3>
			<ul>
				<li>Revisa la ortografía.</li>
				<li>Utiliza <strong>palabras más genéricas</strong> o menos palabras.</li>
				<li><a href="/">Navega por las categorías</a> para encontrar un producto similar.</li>
			</ul>
		</div>
	</div>
</template>

<script>
import ProductCard from './ProductCardHoriz.vue';

export default {
	props: ['products'],
	components: { ProductCard },
	setup() {
		return {}
	}
}
</script>
