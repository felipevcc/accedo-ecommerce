<template>
	<div class="modal fade" id="product_modal" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ is_create ? 'Crear' : 'Editar' }} producto</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
				</div>

				<!-- Backend Errors -->
				<backend-error :errors="back_errors" />

				<!-- Form -->
				<Form @submit="saveProduct" :validation-schema="formSchema">
					<div class="modal-body row">

						<!-- Image, preview -->
						<div class="col-12 d-flex justify-content-center mt-1">
							<img :src="image_preview" alt="Imagen Producto" class="img-thumbnail" width="170" height="170">
						</div>

						<!-- Load Image -->
						<div class="col-12 mt-1 ">
							<label for="image" class="form-label">Imagen</label>
							<input type="file" :class="`form-control ${back_errors['image'] ? 'is-invalid' : ''}`"
								id="image" accept="image/*" @change="onChangePreviewImage">
							<span class="invalid-feedback" v-if="back_errors['image']">
								{{ back_errors['image'] }}
							</span>
						</div>

						<!-- Name -->
						<div class="col-12 mt-2">
							<label for="name">Nombre</label>
							<Field name="name" v-slot="{ errorMessage, field }" v-model="product.name">
								<input type="text" id="name" :class="`form-control ${errorMessage || back_errors['name'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
								<span class="invalid-feedback">{{ back_errors['name'] }}</span>
							</Field>
						</div>

						<!-- Description -->
						<div class="col-12 mt-2">
							<Field name="description" v-slot="{ errorMessage, field }" v-model="product.description">
								<label for="description">Descripción</label>
								<textarea v-model="product.description" :class="`form-control ${errorMessage || back_errors['description'] ? 'is-invalid' : ''}`" id="description" rows="3" v-bind="field"></textarea>
								<span class="invalid-feedback">{{ errorMessage }}</span>
							</Field>
						</div>

						<!-- Price -->
						<div class="col-12 mt-2">
							<Field name="price" v-slot="{ errorMessage, field }" v-model="product.price">
								<label for="price">Precio</label>
								<input type="number" id="price" v-model="product.price" :class="`form-control ${errorMessage || back_errors['price'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
							</Field>
						</div>

						<!-- Stock -->
						<div class="col-12 mt-2">
							<Field name="stock" v-slot="{ errorMessage, field }" v-model="product.stock">
								<label for="stock">Cantidad</label>
								<input type="number" id="stock" v-model="product.stock" :class="`form-control ${errorMessage || back_errors['stock'] ? 'is-invalid' : ''}`" v-bind="field">
								<span class="invalid-feedback">{{ errorMessage }}</span>
							</Field>
						</div>

						<!-- Category -->
						<div class="col-12 mt-2" v-if="loaded_categories">
							<Field name="category" v-slot="{ errorMessage, field, valid }" v-model="product.category_id">
								<label for="category">Categoría</label>
								<v-select id="category" :options="categories" :reduce="category => category.id" v-model="product.category_id" v-bind="field" label="name" placeholder="Seleccione la categoría" :clearable="false" :closeOnContentClick="true" :class="`${errorMessage || back_errors['category_id'] ? 'is-invalid' : ''}`">
								</v-select>
								<span class="invalid-feedback" v-if="!valid">{{ errorMessage }}</span>

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
import BackendError from '@/components/Components/BackendError.vue';

export default {
	props: ['product_data'],
	components: { Field, Form, BackendError },
	setup({ product_data }) {
		const instance = getCurrentInstance();
		const back_errors = ref({});
		const closeModal = () => instance.parent.ctx.closeModal();

		const is_create = product_data ? ref(false) : ref(true);
		const product = !is_create.value ? ref(product_data) : ref({});
		const image_preview = !is_create.value ? ref(product_data.image.route) : ref('/storage/images/products/default.png');
		const image = ref(null);
		const categories = ref([]);
		const loaded_categories = ref(false);

		onMounted(() => {
			getCategories();
		});

		const getCategories = async () => {
			try {
				const { data } = await axios.get('/categories');
				categories.value = data.categories;
				loaded_categories.value = true;
			} catch (error) {
				await handlerErrors(error);
			}
		};

		const onChangePreviewImage = (event) => {
			image.value = event.target.files[0];
			image_preview.value = URL.createObjectURL(image.value);
		};

		const formSchema = yup.object({
			name: yup.string().required(),
			description: yup.string().required(),
			price: yup.number().required().positive().integer(),
			stock: yup.number().required().positive().integer(),
			category: yup.number().required()
		});

		const createFormData = (data) => {
			const form_data = new FormData();
			if (image.value) form_data.append('image', image.value, image.value.name);
			for (const prop in data) {
				if (prop == 'image') continue;
				form_data.append(prop, data[prop]);
			}
			return form_data;
		};

		const saveProduct = async () => {
			try {
				const productFormData = createFormData(product.value);
				if (is_create.value) await axios.post('/products/store', productFormData);
				else await axios.post(`/products/update/${product.value.id}`, productFormData);
				await successMessage({}).then(() => successResponse());
			} catch (error) {
				back_errors.value = await handlerErrors(error);
			}
		};

		const successResponse = () => {
			instance.parent.ctx.reloadState();
			closeModal();
		};

		return {
			product,
			is_create,
			image_preview,
			onChangePreviewImage,
			categories,
			loaded_categories,
			back_errors,
			closeModal,
			saveProduct,
			formSchema
		};
	}
};
</script>
