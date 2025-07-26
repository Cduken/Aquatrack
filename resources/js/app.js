import "../css/app.css";
import "./bootstrap";

import Swal from "sweetalert2";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { OhVueIcon, addIcons } from "oh-vue-icons";

// Import ALL icons directly from their sets
import * as BiIcons from "oh-vue-icons/icons/bi";
import * as HiIcons from "oh-vue-icons/icons/hi";
import * as FaIcons from "oh-vue-icons/icons/fa";
import * as RiIcons from "oh-vue-icons/icons/ri";
import * as MdIcons from "oh-vue-icons/icons/md";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Add icons individually to ensure they're registered
addIcons(
    // Bootstrap Icons
    BiIcons.BiSpeedometer2,
    BiIcons.BiGear,
    BiIcons.BiFileEarmarkText,
    BiIcons.BiBellFill,
    BiIcons.BiBoxArrowRight,
    BiIcons.BiList,
    BiIcons.BiX,
    BiIcons.BiShieldLock,
    BiIcons.BiFlag,
    BiIcons.BiGraphUp,
    BiIcons.BiGeoAlt,
    BiIcons.BiBuilding,
    BiIcons.BiMap,
    BiIcons.BiInfoCircle,
    BiIcons.BiTag,
    BiIcons.BiCalendar,
    BiIcons.BiCardText,
    BiIcons.BiImage,
    BiIcons.BiZoomIn,
    BiIcons.BiEye,
    BiIcons.BiEyeSlash,
    BiIcons.BiHouse,
    BiIcons.BiPlusCircle,
    BiIcons.BiCheckCircle,
    BiIcons.BiChevronRight,
    BiIcons.BiDroplet,
    BiIcons.BiPlus,
    BiIcons.BiCloudUpload,
    BiIcons.BiTelephone,
    BiIcons.BiPersonBadge,
    BiIcons.BiPerson,
    BiIcons.BiPhone,
    BiIcons.BiListCheck,
    BiIcons.BiLock,
    BiIcons.BiTable,
    BiIcons.BiEnvelope,
    BiIcons.BiWallet,
    BiIcons.BiArchive,
    BiIcons.BiXLg,
    BiIcons.BiBoxArrowInRight,
    BiIcons.BiGeo,
    BiIcons.BiSignpost,
    BiIcons.BiImages,
    BiIcons.BiArrowLeft,
    BiIcons.BiPencil, // Added for Edit
    BiIcons.BiTrash, // Added for Delete
    BiIcons.BiDownload, // Added for Export
    BiIcons.BiChevronDown,
    BiIcons.BiMegaphoneFill,
    BiIcons.BiFileEarmarkTextFill,
    BiIcons.BiFlagFill,

    // Heroicons
    HiIcons.HiSolidUser,
    HiIcons.HiUser,
    HiIcons.HiUserGroup,
    HiIcons.HiCog,

    // Font Awesome
    FaIcons.FaBuilding,
    FaIcons.FaLeaf,
    FaIcons.FaIndustry,
    FaIcons.FaUtensils,
    FaIcons.FaUserCog,
    FaIcons.FaUsers,
    FaIcons.FaChartBar,
    FaIcons.FaCogs,
    FaIcons.FaUsers,

    // Remix Icons
    RiIcons.RiDashboardLine,
    RiIcons.RiUserSettingsLine,
    RiIcons.RiTeamLine,
    RiIcons.RiPieChartLine,

    // Material Design
    MdIcons.MdLoginOutlined,
    MdIcons.MdStartTwotone,
    MdIcons.MdDashboard,
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
