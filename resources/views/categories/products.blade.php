<x-app title="{{ $category->name }}">
	<section class="container">
		<category-products :category_data="{{ $category }}" />
	</section>
</x-app>
