import './bootstrap';
import { createApp } from 'vue';
import vSelect from 'vue-select';

// Components ---------------------------------------------------
import BackendError from './components/Components/BackendError.vue';
import UserList from './components/User/UserList.vue';
import CategoryList from './components/Category/CategoryList.vue';
import ProductList from './components/Product/ProductList.vue';

const app = createApp({
	components: {
		UserList,
		CategoryList,
		ProductList
	}
});

app.component('v-select', vSelect);
app.component('backend-error', BackendError);

app.mount('#app');
