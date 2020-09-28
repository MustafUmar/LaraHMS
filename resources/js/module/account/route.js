import VueRouter from 'vue-router'
import Parent from '../../components/admin/Parent'
import Dashboard from '../../components/account/Dashboard'

import Patients from '../../components/account/Patients'
import Patient from '../../components/account/Patient'

import PaymentSummary from '../../components/account/PaymentSummary'
import Payments from '../../components/account/Payments'
import Bill from '../../components/account/Bill'

import { store } from '../admin/store'

Vue.use(VueRouter)

export const router = new VueRouter({
    routes: [
        {
            path: '/hpl', component: Parent,
            children: [
                { path: 'dashboard', component: Dashboard },

                { path: 'patients', component: Patients },
                { path: 'patient/:pat', name: 'patient', component: Patient, props: true },

                { path: 'payment-summary', component: PaymentSummary },
                { path: 'payments', component: Payments },

                { path: 'bill/:pay', name: 'bill', component: Bill, props: true },
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
