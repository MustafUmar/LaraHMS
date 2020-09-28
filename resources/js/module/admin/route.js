import VueRouter from 'vue-router'
import Parent from '../../components/admin/Parent'
import Dashboard from '../../components/admin/Dashboard'

import Doctors from '../../components/admin/doctor/Doctors'
import Doctor from '../../components/admin/doctor/Doctor'
import NewDoctor from '../../components/admin/doctor/NewDoctor'
import EditDoctor from '../../components/admin/doctor/EditDoctor'

import Receptions from '../../components/admin/reception/Receptions'
import Reception from '../../components/admin/reception/Reception'
import NewReception from '../../components/admin/reception/NewReception'
import EditReception from '../../components/admin/reception/EditReception'

import Finance from '../../components/admin/account/Finance'
import Accounts from '../../components/admin/account/Accounts'
import Account from '../../components/admin/account/Account'
import NewAccount from '../../components/admin/account/NewAccount'
import EditAccount from '../../components/admin/account/EditAccount'

import Staffs from '../../components/admin/staff/Staffs'
import Staff from '../../components/admin/staff/Staff'
import NewStaff from '../../components/admin/staff/NewStaff'
import EditStaff from '../../components/admin/staff/EditStaff'

import { store } from './store'

Vue.use(VueRouter)

export const router = new VueRouter({
    routes: [
        {
            path: '/hpl', component: Parent,
            children: [
                { path: 'dashboard', component: Dashboard },
                { path: 'doctors', component: Doctors },
                { path: 'doctor/new', component: NewDoctor },
                { path: 'doctor/edit', name:'doc_edit', component: EditDoctor, props: true},
                { path: 'doctor/:doc', name:'doctor', component: Doctor, props: true},

                { path: 'receptions', component: Receptions },
                { path: 'reception/new', component: NewReception },
                { path: 'reception/edit', name:'rep_edit', component: EditReception, props: true},
                { path: 'reception/:rep', name:'reception', component: Reception, props: true},

                { path: 'finances', component: Finance },
                { path: 'accounts', component: Accounts },
                { path: 'account/new', component: NewAccount },
                { path: 'account/edit', name:'acc_edit', component: EditAccount, props: true},
                { path: 'account/:acc', name:'account', component: Account, props: true},

                { path: 'staffs', component: Staffs },
                { path: 'staff/new', component: NewStaff },
                { path: 'staff/edit', name:'staff_edit', component: EditStaff, props: true},
                { path: 'staff/:stf', name:'staff', component: Staff, props: true},

            ]
        },
    ],
    mode: 'history'
})

router.beforeEach((to, from, next) => {
    if(store.getters.isAuthCheck) {
        if(store.getters.IsInactive) {
            store.dispatch('softLogout')
            next(false)
        }
        if(store.getters.isAuth()) {
            store.commit('setTimestamp')
            next()
        } else {
            store.dispatch('softLogout')
            next(false)
        }
    }
    // else next(false)
})

// export default router
