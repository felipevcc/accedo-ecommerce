<x-app title="{{ explode(' ', $product->name)[0] }}">
	<section class="container">
		<product-info :product="{{ $product }}" />
	</section>
</x-app>
