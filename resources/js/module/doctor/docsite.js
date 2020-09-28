

import 'es6-promise/auto'
import {store} from '../admin/store'
import {router} from './route'
import { mapGetters } from 'vuex'
import Echo from 'laravel-echo'
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
Vue.component('side-doc-panel', require('../../components/segments/SideWaitingPatients.vue').default);

window.io = require('socket.io-client')
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

new Vue({
    el:'#hpldocapp',
    store,
    router,
    data: {
        pgload: true,
        clear: false,
        status: {
            flag:0,
            message:''
        },
        elmblock: true
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

            // Echo.join('online')
            // .here(users => (this.users = users))
            // .joining(user => this.users.push(user))
            // .leaving(user => (this.users = this.users.filter(u => (u.id !== user.id))))
    },
    methods: {
        checkAuth() {
            return new Promise((res,rej) => {
                if(store.getters.isAuth()) {
                    this.clear = true;
                    this.elmblock = false
                    store.commit('setAuthCheck', true)
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
                        this.elmblock = false
                        store.commit('setAuthCheck', true)
                        const rpath = window.location.pathname
                        router.replace({path: rpath.indexOf('hpl') !== -1 ? rpath : '/hpl/dashboard'})
                        res()
                    }).catch(error => {
                        this.clear = false;
                        this.pgload = false;
                        this.elmblock = true
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
        })
    }
})
