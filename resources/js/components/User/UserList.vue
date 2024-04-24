<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="createUser">Crear usuario</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="user_table">
					<thead>
						<tr>
							<th>Cédula</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Email</th>
							<th>Role</th>
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
		<user-modal :user_data="user" />
	</div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { successMessage, handlerErrors, deleteMessage } from '@/helpers/Alerts.js';
import HandlerModal from '@/helpers/HandlerModal.js';
import UserModal from './UserModal.vue';

export default {
	//props: [],
	components: { UserModal },
	setup(/* props */) {
		const table = ref(null);
		const user = ref(null);
		const { openModal, closeModal, load_modal } = HandlerModal();

		onMounted(() => index());

		const index = () => mountTable();

		const mountTable = () => {
			table.value = $('#user_table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				scrollX: true,
				order: [[0, 'asc']],
				autoWidth: false,
				dom: 'Bfrtip',
				buttons: ['pageLength', 'excel', 'pdf', 'copy'],
				ajax: `/users/getAllDT`,
				columns: [
					{ data: 'number_id', name: 'name', orderable: true, searchable: true },
					{ data: 'name', name: 'name', orderable: true, searchable: true },
					{ data: 'last_name', name: 'name', orderable: true, searchable: true },
					{ data: 'email', name: 'name', orderable: true, searchable: true },
					{ data: 'role', name: 'role', orderable: true, searchable: true },
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
							`
						}
					}
				]
			})
		}

		const handleAction = (event) => {
			const button = event.target.closest('[role]');
			if (!button) return;
			const user_id = button.getAttribute('data-id');

			if (button.getAttribute('role') == 'edit') {
				editUser(user_id);
			} else if (button.getAttribute('role') == 'delete') {
				deleteUser(user_id);
			}
		}

		const editUser = async (id) => {
			try {
				const { data } = await axios.get(`/users/${id}`);
				user.value = data.user;
				await openModal('user_modal');
			} catch (error) {
				await handlerErrors(error);
			}
		}

		const deleteUser = async (id) => {
			if (!await deleteMessage()) return;
			try {
				await axios.delete(`/users/${id}`);
				await successMessage({ is_delete: true });
				reloadState();
			} catch (error) {
				await handlerErrors(error);
			}
		}

		const createUser = async () => {
			user.value = null;
			await openModal('user_modal');
		}

		// Reload table component
		const reloadState = () => {
			table.value.destroy();
			// Reload table
			index();
		}

		return {
			user,
			load_modal,
			editUser,
			deleteUser,
			createUser,
			closeModal,
			reloadState,
			handleAction
		}
	}
}
</script>
