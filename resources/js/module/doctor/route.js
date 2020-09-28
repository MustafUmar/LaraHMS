import VueRouter from 'vue-router'
import Parent from '../../components/admin/Parent'
import Dashboard from '../../components/doctor/Dashboard'

import Diagnosis from '../../components/doctor/PatientDiagnosis'
import EditDiagnosis from '../../components/doctor/EditDiagnosis'

import { store } from '../admin/store'

Vue.use(VueRouter)

export const router = new VueRouter({
    routes: [
        {
            path: '/hpl', component: Parent,
            children: [
                { path: 'dashboard', component: Dashboard },
                { path: 'diagnosis', component: Diagnosis },
                { path: 'diagnosis/patient', component: EditDiagnosis },
            ]
        },
    ],
    mode: 'history'
})

router.beforeEach((to, from, next) => {
    if(store.getters.isAuthCheck) {
        if(store.getters.IsInactive) {
            store.dispatch('forceLogout')
            next(false)
        }
        if(store.getters.isAuth()) {
            store.commit('setTimestamp')
            next()
        } else {
            next(false)
        }
    }
    // else next(false)
})

// export default router
