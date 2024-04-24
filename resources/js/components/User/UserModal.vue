<template>
	<div class="modal fade" id="user_modal" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ is_create ? 'Crear' : 'Editar' }} usuario</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
				</div>

				<!-- Backend Errors -->
				<backend-error :errors="back_errors" />

				<!-- Form -->
				<Form @submit="saveUser" :validation-schema="schema">
					<div class="modal-body row">

						<!-- Number id (CC) -->
						<div class="col-12 mt-2">
							<label for="number_id">Cédula</label>
							<Field name="number_id" v-slot="{ errorMessage, field }" v-model="user.number_id">
								<input type="number" id="number_id" :class="`form-control ${errorMessage || back_errors['number_id'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['number_id'] }}</span>
							</Field>
						</div>

						<!-- Name -->
						<div class="col-12 mt-2">
							<label for="name">Nombre</label>
							<Field name="name" v-slot="{ errorMessage, field }" v-model="user.name">
								<input type="text" id="name" :class="`form-control ${errorMessage || back_errors['name'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['name'] }}</span>
							</Field>
						</div>

						<!-- Last name -->
						<div class="col-12 mt-2">
							<label for="last_name">Apellido</label>
							<Field name="last_name" v-slot="{ errorMessage, field }" v-model="user.last_name">
								<input type="text" id="last_name" :class="`form-control ${errorMessage || back_errors['last_name'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['last_name'] }}</span>
							</Field>
						</div>

						<!-- Email -->
						<div class="col-12 mt-2">
							<label for="email">Correo electrónico</label>
							<Field name="email" v-slot="{ errorMessage, field }" v-model="user.email">
								<input type="email" id="email" :class="`form-control ${errorMessage || back_errors['email'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['email'] }}</span>
							</Field>
						</div>

						<!-- Password -->
						<div class="col-12 mt-2">
							<label for="password">Contraseña</label>
							<Field name="password" v-slot="{ errorMessage, field }" v-model="user.password">
								<input type="password" id="password" :class="`form-control ${errorMessage || back_errors['password'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['password'] }}</span>
							</Field>
						</div>

						<!-- Password confirmation -->
						<div class="col-12 mt-2">
							<label for="password_confirmation">Confirmar contraseña</label>
							<Field name="password_confirmation" v-slot="{ errorMessage, field }" v-model="user.password_confirmation">
								<input type="password" id="password_confirmation" :class="`form-control ${errorMessage || back_errors['password_confirmation'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['password_confirmation'] }}</span>
							</Field>
						</div>

						<!-- Role -->
						<div class="col-12 mt-2">
							<label for="role">Role</label>
							<Field name="role" v-slot="{ errorMessage, field }" v-model="user.role">
								<v-select id="role" :options="roles" label="role" v-model="user.role" v-bind="field" :searchable="false" :closeOnContentClick="true" placeholder="Seleccione el role" :clearable="false" :class="`${errorMessage || back_errors['role'] ? 'is-invalid' : ''}`"></v-select>
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['role'] }}</span>
							</Field>
						</div>
					</div>

					<!-- Buttons -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Enviar</button>
					</div>
				</Form>
			</div>
		</div>
	</div>
</template>

<script>
import { ref, getCurrentInstance, onMounted } from 'vue';
import { handlerErrors, successMessage } from '@/helpers/Alerts.js';
import { Field, Form } from 'vee-validate';
import * as yup from 'yup';
import BackendError from '../Components/BackendError.vue';

export default {
	props: ['user_data'],
	components: {
		Field,
		Form,
		BackendError
	},
	setup({ user_data }) {
		const instance = getCurrentInstance();
		const is_create = user_data ? ref(false) : ref(true);
		const user = !is_create.value ? ref(user_data) : ref({});
		const roles = ref([]);
		const back_errors = ref({});
		const closeModal = () => instance.parent.ctx.closeModal();

		onMounted(() => {
			getRoles();
		});

		const getRoles = async () => {
			try {
				const { data } = await axios.get('/roles/getAll');
				roles.value = data.roles;
			} catch (error) {
				await handlerErrors(error);
			}
		}

		const schema = () => {
			return yup.object({
				number_id: yup.number().positive().required(),
				name: yup.string().required(),
				last_name: yup.string().required(),
				email: yup.string().email().required(),
				password: yup.string().required(),
				password_confirmation: yup.string().required(),
				role: yup.string().required()
			})
		}

		const saveUser = async () => {
			try {
				if (is_create.value) {
					await axios.post('/users', user.value);
				} else {
					await axios.put(`/users/${user.value.id}`, user.value);
				}
				await successMessage({}).then(() => successResponse());
			} catch (error) {
				back_errors.value = await handlerErrors(error);
			}
		}

		const successResponse = () => {
			instance.parent.ctx.reloadState();
			closeModal();
		}

		return {
			user,
			is_create,
			roles,
			back_errors,
			closeModal,
			saveUser,
			schema,
		}
	}
}
</script>
