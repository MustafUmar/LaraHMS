import Vuex from 'vuex'
Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
    //   token: '',
        period: 1800,
        inactive: false,
        timestamp: 0,
        authcheck: false,
        username: '',
    },
    getters: {
        // inSession: (state) => () => {

        // },
        isAuth: (state, getters) => () => {
            return !! getters.getToken()
        },
        getToken: (state, getters) => () => {
            const token = getters.getCookie('hplactk')
            if(token) {
                const expire = getters.getCookie('tkexp')
                if(expire) {
                    if(Date.now() < parseInt(expire))
                        return token
                    else{
                        document.cookie = 'hplactk=; Max-Age=-99999999;';
                        document.cookie = 'tkexp=; Max-Age=-99999999;';
                        document.cookie = 'acclient=; Max-Age=-99999999;';
                        return null
                    }
                } else {
                    document.cookie = 'hplactk=; Max-Age=-99999999;';
                    return null
                }
            } else return null;
        },
        getTimestamp: (state) => {
            return state.timestamp
        },
        IsInactive: (state) => {
            if(state.timestamp == 0)
                return false
            if((Date.now() - state.timestamp) > (state.period*1000))
                return true
            else
                return false
        },
        isAuthCheck: (state) => {
            return state.authcheck
        },
        getUser: (state) => {
            return state.username
        },
        getCookie: () => (cname) => {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    },
    mutations: {
        setUsername(state, user) {
            state.username = user
        },
        setTimestamp(state) {
            state.timestamp = Date.now()
        },
        setAuthCheck(state, stat) {
            state.authcheck = !!stat
        },
        setCookie (state, data) {
            var d = new Date();
            d.setTime(d.getTime() + (data.ts*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = data.name + "=" + data.value + ";" + expires + ";path=/";
        },
        destroyToken(state) {
            console.log('destroyed')
            document.cookie = 'hplactk=; Max-Age=-99999999;domain='+window.location.hostname+";path=/";
            document.cookie = 'tkexp=; Max-Age=-99999999;domain='+window.location.hostname+";path=/";
            document.cookie = 'acclient=; Max-Age=-99999999;domain='+window.location.hostname+";path=/";
            // state.token = '';
        },
    },
    actions: {
        setToken({commit}, payload) {
            commit('setCookie', {name:'hplactk', value:payload.token, ts:43200})
            commit('setCookie', {name:'tkexp', value:Date.now() + (parseInt(payload.expire)*1000), ts:43200})
            commit('setCookie', {name:'acclient', value:payload.usertype, ts:43200})
            commit('setUsername', payload.user)
        },
        setAccessToken({commit, dispatch}) {
            commit('destroyToken')
            return new Promise((res,rej) => {
                axios.post('/access').then(response => {
                    dispatch('setToken', response.data)
                    console.log(response.data);
                    res(200)
                }).catch(error => {
                    console.log(error)
                    if(error.response) {
                        if(error.response.status == 401) {
                            console.log('Account not verified')
                            window.location.replace('/hpl/login')
                        }
                        rej(error.response.status)
                    } else
                        rej(null)
                })
            })
        },
        verifyUser({commit, getters, dispatch}) {
            return new Promise((resolve,reject) => {
                const clientele = getters.getCookie('acclient')
                // const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                if(!!clientele) {
                    axios.post('/account/verify', {'client': clientele}).then(response => {
                        console.log('verified')
                        commit('setUsername', response.data)
                        resolve(200)
                    }).catch(error => {
                        console.log(error)
                        if(error.response) {
                            if(error.response.status == 401) {
                                window.location.replace('/hpl/login')
                            } //else dispatch('softLogout')
                        }
                        else
                            reject(null)
                    })
                } else {
                    dispatch('setAccessToken').then(success => {
                        resolve(200)
                    }).catch(error => {
                        reject(error)
                    })
                }
            })

        },
        softLogout({dispatch}) {
            const elm = document.getElementById('logout-form');
            if(!!elm) {
                elm.submit()
            } else {
                dispatch('forceLogout')
            }
        },
        forceLogout({commit}) {

            //send ip of this client
            commit('destroyToken')
            var f = document.createElement("form");
            f.setAttribute('method',"post");
            f.setAttribute('action',"/hpl/logout");

            var s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type',"hidden");
            s.setAttribute('name',"_token");
            s.setAttribute('value', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            f.appendChild(s);

            document.getElementsByTagName('body')[0].appendChild(f);

            f.submit();

        }
    }
})
