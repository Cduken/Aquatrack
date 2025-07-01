import "../css/app.css";
import "./bootstrap";

import Swal from "sweetalert2";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { OhVueIcon, addIcons } from "oh-vue-icons";

// Import icon sets in bulk
import * as BiIcons from "oh-vue-icons/icons/bi";
import * as FaIcons from "oh-vue-icons/icons/fa";
import * as OiIcons from "oh-vue-icons/icons/oi";
import * as MdIcons from "oh-vue-icons/icons/md";
import * as HiIcons from "oh-vue-icons/icons/hi";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Add all icons you need
addIcons(
    // Bootstrap Icons (bi)
    BiIcons.BiSpeedometer2,
    BiIcons.BiBellFill,
    BiIcons.BiBoxArrowInRight,
    BiIcons.BiList,
    BiIcons.BiXLg,
    BiIcons.BiShieldLock,
    BiIcons.BiFlag,
    BiIcons.BiGraphUp,
    BiIcons.BiFileEarmarkText,
    BiIcons.BiGeoAlt,
    BiIcons.BiBuilding,
    BiIcons.BiMap,
    BiIcons.BiGeo,
    BiIcons.BiSignpost,
    BiIcons.BiInfoCircle,
    BiIcons.BiTag,
    BiIcons.BiCalendar,
    BiIcons.BiCardText,
    BiIcons.BiImages,
    BiIcons.BiZoomIn,
    BiIcons.BiEye,
    BiIcons.BiEyeSlash,  // Added eye-slash icon
    BiIcons.BiX,
    BiIcons.BiHouse,
    BiIcons.BiPlusCircle,
    BiIcons.BiCheckCircle,
    BiIcons.BiChevronRight,
    BiIcons.BiDroplet,
    BiIcons.BiPlus,
    BiIcons.BiXLg,
    BiIcons.BiX,
    BiIcons.BiCloudUpload,

    // Font Awesome (fa)
    FaIcons.FaBuilding,
    FaIcons.FaLeaf,
    FaIcons.FaIndustry,
    FaIcons.FaUtensils,

    // Octicons (oi)
    OiIcons.OiGraph,

    // Material Design (md)
    MdIcons.MdStartTwotone,

    // Heroicons (hi)
    HiIcons.HiSolidUser
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
            .component("v-icon", OhVueIcon);

        app.config.globalProperties.$swal = Swal;
        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
