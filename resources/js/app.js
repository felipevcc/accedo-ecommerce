import './bootstrap';
import { createApp } from 'vue';
import vSelect from 'vue-select';

const app = createApp({});

app.component('v-select', vSelect);
app.mount('#app');
