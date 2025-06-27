import '../css/app.css'
import { createApp , h} from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import '@fortawesome/fontawesome-free/css/all.css';
import MainLayout from './pages/Layouts/MainLayout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    return pages[`./pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .component('Link', Link)
      .component('Head', Head)
      .component('MainLayout', MainLayout)
      .mount(el)
  },
})