// Pages
import RegisterStep1Page from '../../pages/RegisterStep1';
import RegisterStep2Page from '../../pages/RegisterStep2';
import StoresPage from "../../pages/Stores"
import SelectPackagesPage from "../../pages/SelectPackages"
import PackageDetailPage from "../../pages/Packages/Detail"
import PackageSharePage from "../../pages/Packages/Share"
import PackageServicesPage from "../../pages/Packages/Services"
import PackageConfirmPage from "../../pages/Packages/Confirm"
import CheckoutPage from "../../pages/Checkout"
import CreditCardPage from "../../pages/CreditCard"
import PrePayPage from "../../pages/PrePay"
import PaymentConfirmedPage from "../../pages/PaymentConfirmed"
import FinishPage from "../../pages/Finish"
import ThanksPage from "../../pages/Thanks"
import ContactCountryPage from "../../pages/ContactCountry"

export const menu = {
    home: {
        path: '/',
        name: 'Inicio',
    },
    stores: {
        path: '/stores',
        name: 'Empresas',
    },
    selectPackages: {
        path: '/select-packages',
        name: 'Selección de destinos',
    },
    register: {
        path: '/register',
        name: 'Registro',
    },
    mobile: {
        path: '/mobile',
        name: 'Ingresar celular',
    },
    selectDate: {
        path: '/select-date',
        name: 'Seleccionar una fecha',
    },
    activation: {
        path: '/activation',
        name: 'Activación',
    },
    packages: {
        path: '/packages/:store_code',
        name: 'Paquetes',
    },
    packageDetail: {
        path: '/package/:code',
        name: 'Paquete',
    },
    packageShare: {
        path: '/share/:code',
        name: 'Paquete',
    },
    packageServices: {
        path: '/package/:code/services',
        name: 'Servicios',
    },
    packageConfirm: {
        path: '/package/:code/confirm',
        name: 'Confirmación',
    },
    creditCard: {
        path: '/payment',
        name: 'Credit Card',
    },
    prePay: {
        path: '/offer',
        name: 'Offer',
    },
    checkout: {
        path: '/checkout',
        name: 'Checkout',
    },
    finish: {
        path: '/finish',
        name: 'Fin',
    },
    paymentConfirmed: {
        path: '/payment-confirmed',
        name: 'Pago confirmado',
    },
    contact: {
        path: '/contact',
        name: 'Contacto',
    },
    contact_country: {
        path: '/register/contact',
        name: 'Contacto Pais',
    },
    thanks: {
        path: '/thanks',
        name: 'Gracias',
    },
}

const routes = [
    /*{
        path: menu.home.path,
        component: HomePage,
        exact: true,
        authorize: (state) => true,
        notAuthorizeRedirectTo: '/',
    },*/
    {
        path: menu.home.path,
        component: StoresPage,
        exact: true,
        authorize: (state) => true,
        notAuthorizeRedirectTo: '/',
    },{
        path: menu.stores.path,
        component: StoresPage,
        exact: true,
        authorize: (state) => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.selectPackages.path,
        component: SelectPackagesPage,
        exact: true,
        authorize: ({ storeIsSelected }) => storeIsSelected,
        notAuthorizeRedirectTo: menu.stores.path,
    },
    {
        path: menu.register.path,
        component: RegisterStep1Page,
        exact: true,
        authorize: ({ storeIsSelected }) => storeIsSelected,
        notAuthorizeRedirectTo: menu.stores.path,
    },
    {
        path: menu.mobile.path,
        component: RegisterStep2Page,
        exact: true,
        authorize: ({ storeIsSelected }) => storeIsSelected,
        notAuthorizeRedirectTo: menu.stores.path,
    },
    {
        path: menu.packageDetail.path,
        component: PackageDetailPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.packageShare.path,
        component: PackageSharePage,
        exact: true,
        authorize: (state) => true,
        notAuthorizeRedirectTo: '/',
    },
    {
        path: menu.packageServices.path,
        component: PackageServicesPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.packageConfirm.path,
        component: PackageConfirmPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.checkout.path,
        component: CheckoutPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.creditCard.path,
        component: CreditCardPage,
        exact: true,
        authorize: ({ userIsLoggedIn, configuration }) => userIsLoggedIn && configuration.payment.isEnabled,
        notAuthorizeRedirectTo: menu.checkout.path,
    },
    {
        path: menu.prePay.path,
        component: PrePayPage,
        exact: true,
        authorize: ({ userIsLoggedIn, configuration }) => userIsLoggedIn && configuration.payment.isEnabled,
        notAuthorizeRedirectTo: menu.checkout.path,
    },
    {
        path: menu.paymentConfirmed.path,
        component: PaymentConfirmedPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.checkout.path,
    },
    {
        path: menu.finish.path,
        component: FinishPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.thanks.path,
        component: ThanksPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
    {
        path: menu.contact_country.path,
        component: ContactCountryPage,
        exact: true,
        authorize: ({ userIsLoggedIn }) => userIsLoggedIn,
        notAuthorizeRedirectTo: menu.activation.path,
    },
];

export default routes;
