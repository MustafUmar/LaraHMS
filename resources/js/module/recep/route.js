import VueRouter from 'vue-router'
import Parent from '../../components/admin/Parent'
import Dashboard from '../../components/reception/Dashboard'

import Patients from '../../components/reception/Patients'
import Patient from '../../components/reception/Patient'
import NewPatFile from '../../components/reception/NewPatFile'
import PatientFile from '../../components/reception/PatientFile'

import { store } from '../admin/store'

Vue.use(VueRouter)

export const router = new VueRouter({
    routes: [
        {
            path: '/hpl', component: Parent,
            children: [
                { path: 'dashboard', component: Dashboard },

                { path: 'patients', component: Patients },
                { path: 'patient/file/:fid', name:'patfile', component: PatientFile, props: true },
                { path: 'patient/:pid', name:'patient', component: Patient, props: true },
                { path: 'file/new', component: NewPatFile },
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
