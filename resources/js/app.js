import "../css/app.css";
import "./bootstrap";

import Swal from 'sweetalert2' // Add this at the top

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { OhVueIcon, addIcons } from "oh-vue-icons";

// Import individual icons
import { MdStartTwotone } from "oh-vue-icons/icons";
import { HiSolidUser } from "oh-vue-icons/icons";
import { BiX, BiCheckCircle } from "oh-vue-icons/icons/bi"; // Added BiCheckCircle
import { BiHouse } from "oh-vue-icons/icons/bi";
import { BiPlusCircle } from "oh-vue-icons/icons/bi";
import {
    BiSpeedometer2,
    BiBellFill,
    BiBoxArrowInRight,
    BiList,
    BiXLg,
    BiShieldLock,
    BiFlag,
    BiGraphUp,
} from "oh-vue-icons/icons/bi";
import { OiGraph } from "oh-vue-icons/icons/oi";
import {
    FaBuilding,
    FaLeaf,
    FaIndustry,
    FaUtensils,
} from "oh-vue-icons/icons/fa";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Add all icons you need
addIcons(
    BiSpeedometer2,
    BiBellFill,
    BiBoxArrowInRight,
    BiList,
    BiXLg,
    BiShieldLock,
    BiFlag,
    BiGraphUp,
    OiGraph,
    FaBuilding,
    FaLeaf,
    FaIndustry,
    FaUtensils,
    MdStartTwotone,
    HiSolidUser,
    BiX,
    BiHouse,
    BiPlusCircle,
    BiFlag,
    BiCheckCircle // Added for notification checkmark
);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("v-icon", OhVueIcon)

        app.config.globalProperties.$swal = Swal
        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
