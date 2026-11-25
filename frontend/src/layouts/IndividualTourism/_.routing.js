// Pages
import IndividualRegisterPage from '../../pages/Individual/Register';
import IndividualPacksPage from "../../pages/Individual/Packs"
import IndividualPacksListPage from "../../pages/Individual/PacksList"
import IndividualPackDetail from "../../pages/Individual/PackDetail"
import PackageSharePage from "../../pages/Packages/Share"
import IndividualPackServicesPage from "../../pages/Individual/PackServices"
import IndividualPackServicesSelectPage from "../../pages/Individual/PackServicesSelect"
import IndividualPackSummaryPage from "../../pages/Individual/PackSummary"
import IndividualCheckoutPage from "../../pages/Individual/Checkout"
import IndividualPaymentPage from "../../pages/Individual/Payment"
import IndividualPrePayPage from "../../pages/Individual/PrePay"
import PaymentConfirmedPage from "../../pages/PaymentConfirmed"
import FinishPage from "../../pages/Finish"
import ThanksPage from "../../pages/Thanks"
import ContactCountryPage from "../../pages/ContactCountry"

export const menu = {
    selectPackagesGroup: {
        path: '/',
        title: 'Packs',
    },
    selectPackages: {
        path: '/packs/:group',
        title: 'Packs',
    },
    register: {
        path: '/register',
        title: 'Registro',
    },
    packages: {
        path: '/packages/:store_code',
        title: 'Paquetes',
    },
    packageDetail: {
        path: '/paquete/:code',
        title: 'Paquete',
    },
    packageServices: {
        path: '/paquete/:code/servicios',
        title: 'Servicios',
    },
    packageServicesEdit: {
        path: '/paquete/:code/seleccion/',
        title: 'Seleccionar servicios',
    },
    packageSummary: {
        path: '/paquete/:code/confirmacion',
        title: 'Confirmación',
    },
    packageShare: {
        path: '/share/:code',
        title: 'Paquete',
    },
    payment: {
        path: '/pago',
        title: 'Pagar',
    },
    prePay: {
        path: '/reservar',
        title: 'Reservar!',
    },
    checkout: {
        path: '/checkout',
        title: 'Checkout',
    },
    finish: {
        path: '/finish',
        title: 'Fin',
    },
    paymentConfirmed: {
        path: '/payment-confirmed',
        title: 'Pago confirmado',
    },
    contact: {
        path: '/contact',
        title: 'Contacto',
    },
    contact_country: {
        path: '/register/contact',
        title: 'Contacto Pais',
    },
    thanks: {
        path: '/thanks',
        title: 'Gracias',
    },
}

const routes = [
    {
        path: menu.selectPackagesGroup.path,
        title: menu.selectPackagesGroup.title,
        component: IndividualPacksPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.selectPackages.path,
        title: menu.selectPackages.title,
        component: IndividualPacksListPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.packageDetail.path,
        title: menu.packageDetail.title,
        component: IndividualPackDetail,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.register.path,
        title: menu.register.title,
        component: IndividualRegisterPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.packageServices.path,
        title: menu.packageServices.title,
        component: IndividualPackServicesPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },{
        path: menu.packageServicesEdit.path,
        title: menu.packageServicesEdit.title,
        component: IndividualPackServicesSelectPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.packageSummary.path,
        title: menu.packageSummary.title,
        component: IndividualPackSummaryPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.packageShare.path,
        title: menu.packageShare.title,
        component: PackageSharePage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },

    {
        path: menu.checkout.path,
        title: menu.checkout.title,
        component: IndividualCheckoutPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.payment.path,
        title: menu.payment.title,
        component: IndividualPaymentPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.prePay.path,
        title: menu.prePay.title,
        component: IndividualPrePayPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.paymentConfirmed.path,
        title: menu.paymentConfirmed.title,
        component: PaymentConfirmedPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.finish.path,
        title: menu.finish.title,
        component: FinishPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.thanks.path,
        title: menu.thanks.title,
        component: ThanksPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.contact_country.path,
        title: menu.contact_country.title,
        component: ContactCountryPage,
        exact: true,
        authorize: () => true,
        notAuthorizeRedirectTo: '/',
    },
];

export default routes;
