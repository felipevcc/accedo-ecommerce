import Swal from 'sweetalert2';

export const handlerErrors = async error => {
	console.error(error);
	const status = error.response.status;
	let options = null;
	let error_object = {};

	switch (status) {
		case 422:
			{
				options = {
					icon: 'error',
					title: 'Error: Campos erróneos.',
					text: 'Llena correctamente el formulario.'
				};
				const errors = error.response.data.errors;
				for (let prop in errors) {
					error_object[prop] = errors[prop][0];
				}
			}
			break;
		case 404:
			options = {
				icon: 'error',
				title: 'Error: URL no encontrada.',
				text: 'Intenta utilizar otra URL.'
			};
			break;
		case 403:
			options = {
				icon: 'error',
				title: 'Error: Usuario sin permisos',
				text: 'No tienes los permisos para ejecutar esta accion.'
			};
			break;
		case 401:
			// Unauthorized, unauthenticated user
			window.location.href = '/login';
			break;
		default:
			options = {
				icon: 'error',
				title: 'Error del servidor',
				text: 'Algo salió mal, espera que se revisará este error.'
			};
			break;
	}
	await Swal.fire(options);
	return error_object;
};

export const successMessage = async ({ is_delete = false, reload = false, text = null }) => {
	await Swal.fire({
		icon: 'success',
		title: 'Felicidades!',
		text: text ?? (is_delete
			? 'Dato eliminado correctamente.'
			: 'Dato almacenado correctamente.')
	});
	if (reload) window.location.reload();
};

export const redirectMessage = async ({ title = '¿Continuar?', confirmButtonText = 'Ir', cancelButtonText = 'Quedarse' }) => {
	const { isConfirmed } = await Swal.fire({
		title,
		showCancelButton: true,
		confirmButtonText,
		cancelButtonText
	});
	return isConfirmed;
}

export const deleteMessage = async () => {
	const { isConfirmed } = await Swal.fire({
		icon: 'warning',
		title: 'Esta seguro de eliminar?',
		showCancelButton: true
	});
	return isConfirmed;
};

export const warningMessage = async (text) => {
	await Swal.fire({
		icon: 'warning',
		title: text
	});
}

export const basicMessage = async (text) => {
	await Swal.fire(text);
}
