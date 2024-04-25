<x-app title="Inicio">
	<section class="container">
		<div class="d-flex justify-content-center my-4">
			<h1>Productos</h1>
		</div>

		<category-list :categories="{{ $categories }}" />
	</section>
</x-app>
