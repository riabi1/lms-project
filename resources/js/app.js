import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js'; 
import SendMessage from './components/SendMessage.vue' 
import ChatMessage from './components/ChatMessage.vue' 
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

 window.am5 = am5;
 window.am5xy = am5xy;
 window.am5themes_Animated = am5themes_Animated;

const app=createApp({
	components:{
		SendMessage, 
		ChatMessage,
	}
});
app.mount('#app'); 

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
