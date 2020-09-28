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
            <div v-if="!stat.error" class="list-group">
                <div class="list-group" v-if="doctors.length > 0">
                    <div class="list-group-item p-1" v-for="user in doctors" :key="user.hashid">
                        <div class="media">
                            <img src="/images/abstrct_doc.png" class="img-thumbnail img-size-2 align-self-center mr-2" alt="...">
                            <div class="media-body ml-1">
                                <h6 class="mt-1">{{user.name}}</h6>
                                <p class="mb-0">
                                    <span class="badge badge-success">Online</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="text-muted text-center p-3"><em>No one here!</em></p>
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
    data() {
        return {
            doctors: [],
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

        window.Echo.connector.socket.on('disconnect', function() {
            this.status = 'Offline'
            this.stat.error = true
        }.bind(this));
    },
    methods: {
        startEcho(persons, uid) {
            this.doctors = []
            this.loading = true
            if(persons.length > 0) {
                try {
                    window.Echo.join('personnel_online')
                        .here((users) => {
                            console.log('person_available:'+users.length)
                            console.log('user store:'+this.$store.getters.getUser)
                            _.forEach(users, (v,k) => {
                                if(v.role == 'Doctor' && this.$store.getters.getUser != v.id)
                                    this.doctors.push(v)
                            })
                        })
                        .joining((user) => {
                            console.log('user joining:')
                            if(user.role == 'Doctor') {
                                this.doctors.splice(0, 0, user)
                            }

                        })
                        .leaving((user) => {
                            console.log('leaving joining:')
                            if(user.role == 'Doctor') {
                                // this.doctors.splice(0, 0, user)
                                const xi = _.findIndex(this.doctors, ['id', user.id])
                                if(xi != -1)
                                    this.doctors.splice(xi, 1)
                            }
                        });

                    this.stat.error = false

                } catch (err) {
                    console.log(err)
                }
            }
            this.loading = false
        }
    }
}
</script>
