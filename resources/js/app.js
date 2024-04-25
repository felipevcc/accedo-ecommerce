import './bootstrap';
import { createApp } from 'vue';
import vSelect from 'vue-select';

// Components ---------------------------------------------------
import BackendError from './components/Components/BackendError.vue';
import AdminUserList from './components/Admin/User/UserList.vue';
import AdminCategoryList from './components/Admin/Category/CategoryList.vue';
import AdminProductList from './components/Admin/Product/ProductList.vue';
import CategoryList from './components/Category/CategoryList.vue';
import CategoryProducts from './components/Category/CategoryProducts.vue';
import ProductSearch from './components/Product/ProductSearch.vue';
import ProductInfo from './components/Product/ProductInfo.vue';
import ShoppingCart from './components/Cart/ShoppingCart.vue';

const app = createApp({
	components: {
		AdminUserList,
		AdminCategoryList,
		AdminProductList,
		CategoryList,
		CategoryProducts,
		ProductSearch,
		ProductInfo,
		ShoppingCart
	}
});

app.component('v-select', vSelect);
app.component('backend-error', BackendError);

app.mount('#app');
