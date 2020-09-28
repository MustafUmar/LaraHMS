///////////forms///////////
$('#product-form').submit(function(e) {
    $('#prodadd-btn').attr('disabled',true)
    // return true
})


import 'es6-promise/auto'
import {store} from '../admin/store'
import {router} from './route'
import { mapGetters } from 'vuex'
import { DateTime as LuxonDateTime } from 'luxon';
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

axios.interceptors.request.use(function (config) {
    if(store.getters.isAuth) {
        const token = store.getters.getToken()
        if(token) {
            config.headers.Authorization = `Bearer ${token}`
        }
    }

    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

Vue.component('doctors-online', require('../../components/doctor/OnlineDoctors.vue').default);


new Vue({
    el:'#hplrepapp',
    store,
    router,
    data: {
        pgload: true,
        clear: false,
        personnels: [],
        status: {
            flag:0,
            message:''
        }
    },
    mounted() {
        this.checkAuth().then(() => {
            const user = store.getters.getCookie('acclient')
            if(!!user) {
                axios.post('api/personnels', {uinfo: user}).then(response => {
                    this.personnels = response.data
                    this.$refs.online_users.startEcho(this.personnels, user)
                }).catch(error => {
                    console.log(error)
                })
            }
        })
    },
    methods: {
        checkAuth() {
            return new Promise((res,rej) => {
                if(store.getters.isAuth()) {
                    this.clear = true;
                    store.commit('setAuthCheck', true);
                    // const verified = store.dispatch('verifyUser').catch(err => this.clear = false)
                    const rpath = window.location.pathname
                    router.replace({path: rpath.indexOf('hpl') !== -1 ? rpath : '/hpl/dashboard'})

                    store.dispatch('verifyUser').then(() => {
                        res()
                    }).catch(err => {
                        // this.pgload = false;
                        this.clear = false
                        rej()
                    })
                    this.pgload = false;
                } else {
                    store.dispatch('setAccessToken').then(() => {
                        this.clear = true;
                        this.pgload = false;
                        store.commit('setAuthCheck', true)
                        const rpath = window.location.pathname
                        router.replace({path: rpath.indexOf('hpl') !== -1 ? rpath : '/hpl/dashboard'})
                        // return Promise.resolve()
                        res()
                    }).catch(error => {
                        this.clear = false;
                        this.pgload = false;
                        // return Promise.reject()
                        rej()
                    })
                }
            })
        },
        fireNotifier(message, status) {
            alertify.set('notifier','position', 'top-center');
            alertify.notify(message, status, 5,);
        }
    },
    computed: {
        ...mapGetters({
            isauth: 'isAuth'
        }),
        luxonDate() {
            return LuxonDateTime
        }
    }
})
