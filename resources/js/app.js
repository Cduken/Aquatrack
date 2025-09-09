import "../css/app.css";
import "./bootstrap";
import Swal from "sweetalert2";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { OhVueIcon, addIcons } from "oh-vue-icons";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// Make Leaflet globally available
window.L = L;

// Fix for default Leaflet markers (optional but recommended)
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
    iconUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
    shadowUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
});

// Import ALL icons directly from their sets
import * as BiIcons from "oh-vue-icons/icons/bi";
import * as HiIcons from "oh-vue-icons/icons/hi";
import * as FaIcons from "oh-vue-icons/icons/fa";
import * as RiIcons from "oh-vue-icons/icons/ri";
import * as MdIcons from "oh-vue-icons/icons/md";
import * as SiIcons from "oh-vue-icons/icons/si";
import * as IoIcons from "oh-vue-icons/icons/io";
import * as OiIcons from "oh-vue-icons/icons/oi";

import { LaUserEditSolid } from "oh-vue-icons/icons";




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
    BiIcons.BiClipboardData,
    BiIcons.BiHourglassSplit,
    BiIcons.BiLightningCharge,
    BiIcons.BiCurrencyDollar,
    BiIcons.BiCalendarWeek,
    BiIcons.BiClock,
    BiIcons.BiSearchHeart,
    BiIcons.BiPersonCircle,
    BiIcons.BiFilter,
    BiIcons.BiArrowDownUp,
    BiIcons.BiCreditCardFill,
    BiIcons.BiGeoAltFill,
    BiIcons.BiCompass,
    BiIcons.BiArrowBarLeft,
    BiIcons.BiCalendarCheckFill,
    BiIcons.BiHddNetwork,
    BiIcons.BiArrowBarRight,
    BiIcons.BiExclamationCircleFill,
    BiIcons.BiListUl,
    BiIcons.BiCash,
    BiIcons.BiExclamationOctagon,
    BiIcons.BiAlarm,
    BiIcons.BiCheckLg,
    BiIcons.BiArrowRepeat,
    BiIcons.BiCalendarX,
    BiIcons.BiArrowLeftRight,
    BiIcons.BiCashCoin,
    BiIcons.BiArrowClockwise,
    BiIcons.BiGlobe,
    BiIcons.BiCamera,
    BiIcons.BiCameraVideo,
    BiIcons.BiCameraVideoOff,
    BiIcons.BiClockHistory,
    BiIcons.BiExclamationTriangleFill,
    BiIcons.BiMegaphone,
    BiIcons.BiEnvelopeOpen,
    BiIcons.BiCalendarEvent,
    BiIcons.BiPlay,

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
    HiIcons.HiSolidSearch,
    HiIcons.HiSearch,
    HiIcons.HiClipboardList,
    HiIcons.HiMenu,
    HiIcons.HiSolidPlus,
    HiIcons.HiSolidFilter,
    HiIcons.HiCheckCircle,
    HiIcons.HiRefresh,
    HiIcons.HiEye,
    HiIcons.HiPencil,
    HiIcons.HiTrash,
    HiIcons.HiShieldCheck,
    HiIcons.HiXCircle,
    HiIcons.HiLockOpen,
    HiIcons.HiTrendingUp,
    HiIcons.HiArrowSmUp,
    HiIcons.HiSpeakerphone,
    HiIcons.HiDocumentText,
    HiIcons.HiClock,
    HiIcons.HiPlus,
    HiIcons.HiDesktopComputer,
    HiIcons.HiExclamationCircle,
    HiIcons.HiExclamation,
    HiIcons.HiInformationCircle,
    HiIcons.HiCamera,
    HiIcons.HiVideoCamera,
    HiIcons.HiSolidX,
    HiIcons.HiPlay,
    HiIcons.HiPhotograph,
    HiIcons.HiDownload,
    HiIcons.HiSortDescending,
    HiIcons.HiPaperAirplane,

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
    FaIcons.FaRegularTrashAlt,
    FaIcons.FaFileInvoiceDollar,


    // Remix Icons
    RiIcons.RiDashboardLine,
    RiIcons.RiUserSettingsLine,
    RiIcons.RiTeamLine,
    RiIcons.RiPieChartLine,
    RiIcons.RiEditBoxFill,
    RiIcons.RiSave3Fill,
    RiIcons.RiEdit2Fill,
    RiIcons.RiEditBoxLine,

    // Material Design
    MdIcons.MdLoginOutlined,
    MdIcons.MdStartTwotone,
    MdIcons.MdDashboard,
    MdIcons.MdWarningamberRound,
    MdIcons.MdLockreset,
    MdIcons.MdReportproblem,
    MdIcons.MdPeoplealtOutlined,
    MdIcons.MdDashboardSharp,



    // Simple Icons
    SiIcons.SiGoogleanalytics,

    //Ionic Icons
    IoIcons.IoDesktop,
    IoIcons.IoSearch,
    IoIcons.IoSpeedometer,
    IoIcons.IoMegaphoneSharp,

    // Octicons
    OiIcons.OiReport,

    // Line Awesome
    LaUserEditSolid,

    // Eos Icons


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
                closeButton: "button",
                icon: true,
                rtl: false,
            })
            .component("v-icon", OhVueIcon);

        app.config.globalProperties.$swal = Swal;
        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
