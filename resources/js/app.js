import '../css/app.css'
import { createApp , h} from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import '@fortawesome/fontawesome-free/css/all.css';
import SeekerLayout from './Pages/Layouts/SeekerLayout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .component('Link', Link)
      .component('Head', Head)
      .component('SeekerLayout', SeekerLayout)
      .mount(el)
  },
})