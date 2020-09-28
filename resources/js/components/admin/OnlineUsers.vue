<template>
    <div class="useroption-dropdown dropdown-menu dropdown-menu-right">
        <div class="top-actions">
            <h6 class="m-0">Status:
                <span v-if="status == 'Online'" class="badge badge-success">{{status}}</span>
                <span v-if="status == 'Busy'" class="badge badge-warning">{{status}}</span>
                <span v-if="status == 'Offline'" class="badge badge-secondary">{{status}}</span>
            </h6>
        </div>
        <div class="main-actions">

            <div v-if="!stat.error">
                <div class="trans-loader" v-if="loading">
                    <div class="d-flex justify-content-center align-items-center loader-wrapper">
                        <div class="loader-content fa-lg">
                            <span><i class="fas fa-spinner fa-pulse"></i></span>
                            Connecting ...
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-fill flex-nowrap" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="online-admins-tab" data-toggle="tab" href="#online-admins" role="tab" aria-controls="online-admins" aria-selected="true">
                            <i class="fas fa-user-cog fa-lg has-badge tab-icons" :data-count="admincount"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="online-doctors-tab" data-toggle="tab" href="#online-doctors" role="tab" aria-controls="online-doctors" aria-selected="false">
                            <i class="fas fa-user-md fa-lg has-badge tab-icons" :data-count="doccount"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="online-receps-tab" data-toggle="tab" href="#online-receps" role="tab" aria-controls="online-receps" aria-selected="false">
                            <i class="fas fa-door-open fa-lg has-badge tab-icons" :data-count="repcount"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="online-lab-tab" data-toggle="tab" href="#online-lab" role="tab" aria-controls="online-lab" aria-selected="false">
                            <i class="fas fa-vial fa-lg has-badge tab-icons" :data-count="labcount"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="online-account-tab" data-toggle="tab" href="#online-account" role="tab" aria-controls="online-account" aria-selected="false">
                            <i class="fas fa-cash-register fa-lg has-badge tab-icons" :data-count="acccount"></i>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="online-users-tab">
                    <div class="tab-pane fade show active" id="online-admins" role="tabpanel" aria-labelledby="online-admins-tab">
                        <h6 class="px-2 m-0">Admins</h6>
                        <div class="list-group" v-if="admins.length > 0">
                            <div class="list-group-item p-1" v-for="user in admins" :key="user.hashid">
                                <div class="media">
                                    <img src="/images/abstrct_doc.png" class="img-thumbnail img-size-2 align-self-center mr-2" alt="...">
                                    <div class="media-body ml-1">
                                        <h6 class="mt-1">{{user.fullname}}</h6>
                                        <p class="mb-0">
                                            <span v-if="user.status == 'Online'" class="badge badge-success">{{user.status}}</span>
                                            <span v-if="user.status == 'Busy'" class="badge badge-warning">{{user.status}}</span>
                                            <span v-if="user.status == 'Offline'" class="badge badge-secondary">{{user.status}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-muted text-center p-3"><em>No one here!</em></p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="online-doctors" role="tabpanel" aria-labelledby="online-doctors-tab">
                        <h6 class="px-2 m-0">Doctors</h6>
                        <div class="list-group" v-if="doctors.length > 0">
                            <div class="list-group-item p-1" v-for="user in doctors" :key="user.hashid">
                                <div class="media">
                                    <img src="/images/abstrct_doc.png" class="img-thumbnail img-size-2 align-self-center mr-2" alt="...">
                                    <div class="media-body ml-1">
                                        <h6 class="mt-1">{{user.fullname}}</h6>
                                        <p class="mb-0">
                                            <span v-if="user.status == 'Online'" class="badge badge-success">{{user.status}}</span>
                                            <span v-if="user.status == 'Busy'" class="badge badge-warning">{{user.status}}</span>
                                            <span v-if="user.status == 'Offline'" class="badge badge-secondary">{{user.status}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-muted text-center p-3"><em>No one here!</em></p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="online-receps" role="tabpanel" aria-labelledby="online-receps-tab">
                        <h6 class="px-2 m-0">Reception</h6>
                        <div class="list-group" v-if="receptions.length > 0">
                            <div class="list-group-item p-1" v-for="user in receptions" :key="user.hashid">
                                <div class="media">
                                    <img src="/images/abstrct_doc.png" class="img-thumbnail img-size-2 align-self-center mr-2" alt="...">
                                    <div class="media-body ml-1">
                                        <h6 class="mt-1">{{user.fullname}}</h6>
                                        <p class="mb-0">
                                            <span v-if="user.status == 'Online'" class="badge badge-success">{{user.status}}</span>
                                            <span v-if="user.status == 'Busy'" class="badge badge-warning">{{user.status}}</span>
                                            <span v-if="user.status == 'Offline'" class="badge badge-secondary">{{user.status}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-muted text-center p-3"><em>No one here!</em></p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="online-lab" role="tabpanel" aria-labelledby="online-lab-tab">
                        <h6 class="px-2 m-0">Lab</h6>
                    </div>
                    <div class="tab-pane fade" id="online-account" role="tabpanel" aria-labelledby="online-account-tab">
                        <h6 class="px-2 m-0">Account</h6>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="d-flex justify-content-center align-items-center">
                    <h6 class="text-muted p-4">{{stat.message}}</h6>
                </div>
            </div>

        </div>
        <div class="bottom-actions p-1">
            <div class="two-flexed-spacebtw">
                <button class="btn btn-link btn-sm">Settings</button>
                <a href="#" class="btn btn-link btn-sm logout-link">
                    Logout
                </a>
            </div>
        </div>
    </div>
</template>

<script>

import Echo from 'laravel-echo'

    window.io = require('socket.io-client')
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001',
        reconnectionAttempts: 50
    });


export default {
    // props:['users'],
    data() {
        return {
            users: [],
            admins: [],
            doctors: [],
            receptions: [],
            labs: [],
            accounts: [],
            status: 'Offline',
            loading: false,
            stat: {
                error: true,
                message: 'Unavailable'
            }
        }
    },
    mounted() {
        window.Echo.connector.socket.on('connect', () => {
            console.log('connected')
            this.status = 'Online'
            this.stat.error = false
        });

        window.Echo.connector.socket.on('disconnect', () => {
            this.status = 'Offline'
            this.stat.error = true
        });
    },
    methods: {
        startEcho(persons, uid) {
            this.loading = true
            this.admins = []; this.doctors = []; this.receptions = [];
            this.labs = []; this.accounts = [];
            if(persons.length > 0) {
                try {
                    window.Echo.join('personnel_online')
                        .here((users) => {
                            console.log('person_available:'+users.length)
                            this.users = users
                            let index = 0
                            _.forEach(persons, (v,k) => {
                                const uob = this[(v.role.toLowerCase()+'s')]
                                uob.push(v)
                                // if(v.role == 'Admin')
                                //     this.admins.push(v)
                                // else if(v.role == 'Doctor')
                                //     this.doctors.push(v)
                                // else if(v.role == 'Reception')
                                //     this.receptions.push(v)
                                // else if(v.role == 'Lab')
                                //     this.labs.push(v)
                                // else if(v.role == 'Account')
                                //     this.accounts.push(v)
                                if(_.some(users, ['id', v.personid])) {
                                    v.status = 'Online'
                                    if(uob.length > 1) {
                                        const xi = _.findIndex(uob, ['personid', v.personid])
                                        if(xi != -1)
                                            uob.splice(0,0, uob.splice(xi,1)[0])
                                    }
                                }
                            })
                        })
                        .joining((user) => {
                            console.log('user joining:')
                            const uob = this[user.role.toLowerCase()+'s']
                            const xi = _.findIndex(uob, ['personid', user.id])
                            if(xi != -1) {
                                this.$set(uob[xi], 'status', 'Online')
                                if(uob.length > 1)
                                if(xi != 0)
                                    uob.splice(0,0, uob.splice(xi, 1)[0])
                            }
                            // if(user.role == 'Admin') {
                            //     const xi = _.findIndex(this.admins, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.admins[xi], 'status', 'Online')
                            //         if(xi != 0)
                            //             this.admins.splice(0,0, this.admins.splice(xi, 1)[0])
                            //     }
                            // }
                            // else if(user.role == 'Doctor') {
                            //     const xi = _.findIndex(this.doctors, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.doctors[xi], 'status', 'Online')
                            //         if(xi != 0) {
                            //             this.doctors.splice(0,0, this.doctors.splice(xi, 1)[0])
                            //         }
                            //     }
                            // }
                            // else if(user.role == 'Reception') {
                            //     const xi = _.findIndex(this.receptions, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.receptions[xi], 'status', 'Online')
                            //         if(xi != 0)
                            //             this.receptions.splice(0,0, this.receptions.splice(xi, 1)[0])
                            //     }
                            // }
                            // else if(user.role == 'Lab') {
                            //     const xi = _.findIndex(this.labs, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.labs[xi], 'status', 'Online')
                            //         if(xi != 0)
                            //             this.labs.splice(0,0, this.labs.splice(xi, 1)[0])
                            //     }
                            // }
                            // else if(user.role == 'Account') {
                            //     const xi = _.findIndex(this.accounts, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.accounts[xi], 'status', 'Online')
                            //         if(xi != 0)
                            //             this.accounts.splice(0,0, this.accounts.splice(xi, 1)[0])
                            //     }
                            // }
                            this.users.push(user)
                        })
                        .leaving((user) => {
                            console.log('leaving joining:')
                            const uob = this[user.role.toLowerCase()+'s']
                            const xi = _.findIndex(uob, ['personid', user.id])
                            if(xi != -1) {
                                this.$set(uob[xi], 'status', 'Offline')
                            }
                            // if(user.role == 'Admin') {
                            //     const xi = _.findIndex(this.admins, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.admins[xi], 'status', 'Offline')
                            //     }
                            // }
                            // else if(user.role == 'Doctor') {
                            //     const xi = _.findIndex(this.doctors, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.doctors[xi], 'status', 'Offline')
                            //     }
                            // }
                            // else if(user.role == 'Reception') {
                            //     const xi = _.findIndex(this.receptions, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.receptions[xi], 'status', 'Offline')
                            //     }
                            // }
                            // else if(user.role == 'Lab') {
                            //     const xi = _.findIndex(this.labs, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.labs[xi], 'status', 'Offline')
                            //     }
                            // }
                            // else if(user.role == 'Account') {
                            //     const xi = _.findIndex(this.accounts, ['personid', user.id])
                            //     if(xi != -1) {
                            //         this.$set(this.accounts[xi], 'status', 'Offline')
                            //     }
                            // }
                            // _.remove(this.users, function(u) {
                            //     console.log(u.id +" = "+user.id)
                            //     return _.isEqual(u.id, user.id)
                            // })
                            // this.$set(this, 'users', this.users)
                            this.users = this.users.filter(u => (u.id !== user.id))
                        });

                    this.stat.error = false

                } catch (err) {
                    console.log(err)
                }
            }
            this.loading = false
        }
    },
    computed: {
        admincount() {
            return _.filter(this.users, ['role','Admin']).length
        },
        doccount() {
            return _.filter(this.users, ['role','Doctor']).length
        },
        repcount() {
            return _.filter(this.users, ['role','Reception']).length
        },
        labcount() {
            return _.filter(this.users, ['role','Lab']).length
        },
        acccount() {
            return _.filter(this.users, ['role','Account']).length
        }
    }
}
</script>
