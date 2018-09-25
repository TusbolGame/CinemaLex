
import router from './Routes';

export default function (Vue) {

    Vue.helper = {
        back() {
            router.go(-1)
        },
        home() {
            router.push({name: 'discover'})
        },
        set_caption(caption) {
            localStorage.setItem('caption', caption);
        },
        client_secret() {
            return "IVBc3DFXCEcT4UcWRPBrHuCunCEVJuduYCTrbKGZ";
        },
        stripe_key() {
            return "pk_test_1MeRAI65kBVS1UVQOEgxXGsf";
        },
        current_theme() {
            const theme = document.body.firstElementChild.className;
            if(theme !== undefined){
                return theme;
            }
        },
    }
    //make auth global 
    Object.defineProperties(Vue.prototype, {
        $Helper: {
            get: () => {
                return Vue.helper
            }
        }
    })
}
