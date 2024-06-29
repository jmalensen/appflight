import './bootstrap';

import { createApp } from 'vue';
import TicketManager from './components/TicketManager.vue';

const app = createApp();
app.component('ticket-manager', TicketManager);
app.mount('#app');