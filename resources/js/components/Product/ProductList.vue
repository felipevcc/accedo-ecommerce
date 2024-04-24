<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="createProduct">Crear producto</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="product_table">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Categoría</th>
							<th>Precio</th>
							<th>Stock</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody @click="handleAction">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div v-if="load_modal">
		<product-modal :product_data="product" />
	</div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { successMessage, handlerErrors, deleteMessage } from '@/helpers/Alerts.js';
import handlerModal from '@/helpers/HandlerModal.js';
import ProductModal from './ProductModal.vue';

export default {
	//props: [],
	components: { ProductModal },
	setup(/* props */) {
		const table = ref(null);
		const product = ref(null);
		const { openModal, closeModal, load_modal } = handlerModal();

		onMounted(() => index());

		const index = () => mountTable();

		const mountTable = () => {
			table.value = $('#product_table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				scrollX: true,
				order: [[0, 'asc']],
				autoWidth: false,
				dom: 'Bfrtip',
				buttons: ['pageLength', 'excel', 'pdf', 'copy'],
				ajax: `/products/getAllDT`,
				columns: [
					{ data: 'name', name: 'name', orderable: true, searchable: true },
					{ data: 'category.name', name: 'name', orderable: true, searchable: true },
					{ data: 'price', name: 'price', orderable: true, searchable: false },
					{ data: 'stock', name: 'stock', orderable: true, searchable: false },
					{
						name: 'action',
						orderable: false,
						searchable: false,
						render: (data, type, row, meta) => {
							return `
								<div class="d-flex justify-content-center" data-role='actions'>
									<button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="btn btn-warning btn-sm">
										<i class='fa-solid fa-pencil' data-id='${row.id}' role='edit'></i>
									</button>
									<button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="btn btn-danger btn-sm ms-1">
										<i class='fa-solid fa-trash-can' data-id='${row.id}' role='delete'></i>
									</button>
								</div>
							`;
						}
					}
				]
			});
		};

		const handleAction = event => {
			const button = event.target.closest('[role]');
			if (!button) return;
			const product_id = button.getAttribute('data-id');

			if (button.getAttribute('role') == 'edit') {
				editProduct(product_id);
			} else if (button.getAttribute('role') == 'delete') {
				deleteProduct(product_id);
			}
		};

		const editProduct = async id => {
			try {
				const { data } = await axios.get(`/products/${id}`);
				product.value = data.product;
				await openModal('product_modal');
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const deleteProduct = async id => {
			if (!(await deleteMessage())) return;
			try {
				await axios.delete(`/products/${id}`);
				await successMessage({ is_delete: true });
				reloadState();
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const createProduct = async () => {
			product.value = null;
			await openModal('product_modal');
		};

		const reloadState = () => {
			table.value.destroy();
			// Reload table
			index();
		};

		return {
			product,
			load_modal,
			createProduct,
			handleAction,
			editProduct,
			deleteProduct,
			closeModal,
			reloadState
		};
	}
};
</script>
