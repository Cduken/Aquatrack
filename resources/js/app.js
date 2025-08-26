import "../css/app.css";
import "./bootstrap";
import Swal from "sweetalert2";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { OhVueIcon, addIcons } from "oh-vue-icons";

import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';



// Import ALL icons directly from their sets
import * as BiIcons from "oh-vue-icons/icons/bi";
import * as HiIcons from "oh-vue-icons/icons/hi";
import * as FaIcons from "oh-vue-icons/icons/fa";
import * as RiIcons from "oh-vue-icons/icons/ri";
import * as MdIcons from "oh-vue-icons/icons/md";
import * as SiIcons from "oh-vue-icons/icons/si";
import * as IoIcons from "oh-vue-icons/icons/io";

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
    BiIcons.BiEyeFill,
    BiIcons.BiClockFill,
    BiIcons.BiActivity,
    BiIcons.BiCheckCircleFill,
    BiIcons.BiExclamationTriangleFill,
    BiIcons.BiHourglassTop,
    BiIcons.BiFileEarmarkCheckFill,
    BiIcons.BiCalendarWeekFill,
    BiIcons.BiCalendarMonthFill,
    BiIcons.BiCalendarFill,
    BiIcons.BiSearch,
    BiIcons.BiHouseDoorFill,
    BiIcons.BiWater,
    BiIcons.BiCashStack,
    BiIcons.BiBellFill,
    BiIcons.BiCircleFill,
    BiIcons.BiArrowUpCircleFill,
    BiIcons.BiDashCircleFill,
    BiIcons.BiBarChartFill,
    BiIcons.BiChatSquareTextFill,
    BiIcons.BiReceipt,
    BiIcons.BiTools,
    BiIcons.BiCreditCard,
    BiIcons.BiLightbulb,
    BiIcons.BiPlusCircleFill,
    BiIcons.BiCardHeading,
    BiIcons.BiTextareaT,
    BiIcons.BiCalendarDate,
    BiIcons.BiCalendar3,
    BiIcons.BiToggleOn,
    BiIcons.BiXCircle,
    BiIcons.BiSaveFill,
    BiIcons.BiToggleOff,
    BiIcons.BiHash,
    BiIcons.BiGearFill,
    BiIcons.BiXCircleFill,
    BiIcons.BiExclamationCircle,
    BiIcons.BiPlusLg,
    BiIcons.BiFileEarmarkExcel,
    BiIcons.BiPersonCheck,
    BiIcons.BiArrowDown,
    BiIcons.BiPeople,
    BiIcons.BiArrowUp,
    BiIcons.BiPersonLinesFill,
    BiIcons.BiDropletHalf,
    BiIcons.BiDash,
    BiIcons.BiExclamationTriangle,
    BiIcons.BiChevronLeft,
    BiIcons.BiArrowRight,
    BiIcons.BiUpcScan,
    BiIcons.BiChevronDoubleLeft,
    BiIcons.BiChevronDoubleRight,
    BiIcons.BiChevronUp,
    BiIcons.BiPersonFill,
    BiIcons.BiEnvelopeFill,
    BiIcons.BiLockFill,
    BiIcons.BiKeyFill,
    BiIcons.BiShieldLockFill,
    BiIcons.BiShieldFillCheck,
    BiIcons.BiArrowRepeat,
    BiIcons.BiTelephone,
    BiIcons.BiCloudArrowUp,
    BiIcons.BiSignpostSplit,
    BiIcons.BiGrid,
    BiIcons.BiPlayCircleFill,
    BiIcons.BiClockHistory,
    BiIcons.BiFileEarmarkPlus,
    BiIcons.BiPersonPlus,
    BiIcons.BiShieldCheck,
    BiIcons.BiBoxArrowLeft,
    BiIcons.BiBell,
    BiIcons.BiPeopleFill,
    BiIcons.BiCalendarCheck,
    BiIcons.BiRulers,
    BiIcons.BiPencilSquare,
    BiIcons.BiCalendarMonth,
    BiIcons.Bi123,
    BiIcons.BiTag,
    BiIcons.BiInbox,
    BiIcons.BiArrowDownCircleFill,


    // Heroicons
    HiIcons.HiSolidUser,
    HiIcons.HiUser,
    HiIcons.HiUserGroup,
    HiIcons.HiCog,
    HiIcons.HiSolidUsers,
    HiIcons.HiSolidSpeakerphone,
    HiIcons.HiSolidUserAdd,
    HiIcons.HiX,
    HiIcons.HiMail,
    HiIcons.HiPhone,
    HiIcons.HiBriefcase,
    HiIcons.HiUsers,
    HiIcons.HiLocationMarker,
    HiIcons.HiChevronDown,
    HiIcons.HiMap,
    HiIcons.HiCheck,
    HiIcons.HiLockClosed,
    HiIcons.HiKey,
    HiIcons.HiIdentification,
    HiIcons.HiCalendar,
    HiIcons.HiTag,
    HiIcons.HiCode,
    HiIcons.HiArrowsExpand,
    HiIcons.HiUserCircle,
    HiIcons.HiCube,

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
    FaIcons.FaTrash,
    FaIcons.FaFolderOpen,
    FaIcons.FaWater,


    // Remix Icons
    RiIcons.RiDashboardLine,
    RiIcons.RiUserSettingsLine,
    RiIcons.RiTeamLine,
    RiIcons.RiPieChartLine,
    RiIcons.RiEditBoxFill,
    RiIcons.RiSave3Fill,
    RiIcons.RiEdit2Fill,


    // Material Design
    MdIcons.MdLoginOutlined,
    MdIcons.MdStartTwotone,
    MdIcons.MdDashboard,
    MdIcons.MdWarningamberRound,

    // Simple Icons
    SiIcons.SiGoogleanalytics,

    //Ionic Icons
    IoIcons.IoDesktop,
    IoIcons.IoSearch,
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
            .use(Toast, {
                timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false
            })
            .component("v-icon", OhVueIcon);

        app.config.globalProperties.$swal = Swal;
        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
