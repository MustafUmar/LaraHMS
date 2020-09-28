<template>
    <div>
        <page-heading heading="Patients"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <div class="trans-loader" v-show="loading">
                        <div class="d-flex justify-content-center align-items-center loader-wrapper">
                            <div class="loader-content fa-lg">
                                <i class="fas fa-spinner fa-pulse"></i>
                            </div>
                        </div>
                    </div>

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="term" @keyup.enter="search" aria-label="Patient Search">
                                <div class="input-group-append">
                                    <button class="btn" type="button" @click="search"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">

                        <div class="card-body">
                            <div>
                                <div v-if="patients.length === 0">
                                    <h6>Recent sessions</h6>
                                    <!-- <div class="list-group" v-if="recents.length > 0">
                                        <router-link :to="{name:'patfile', params:{rid:rec.id}}" v-for="rec in recents" :key="rec.id" class="list-group-item list-group-item-action">
                                            <span class="dim-txt fs-4">ID: {{rec.patno}}</span>
                                            <p class="fs-bold m-0">
                                                <span>{{rec.firstname+' '+rec.lastname}}</span>
                                            </p>
                                            <span class="dim-txt fs-4">time</span>
                                        </router-link>
                                    </div> -->
                                    <table class="table" v-if="recents.length > 0">
                                        <!-- <tr>
                                            <th>ID</th><th>Name</th><th>Time</th><th>Status</th><td></td>
                                        </tr> -->
                                        <tr v-for="rec in recents" :key="rec.id">
                                            <td><i class="fas fa-caret-right"></i></td>
                                            <td><span class="dim-txt fs-4">#{{rec.patient.patno}}</span></td>
                                            <td class="fs-bold wd35">{{rec.patient.fullname}}</td>
                                            <td>time</td>
                                            <td>
                                                <div class="badge badge-primary">Pending</div>
                                            </td>
                                            <td>
                                                <router-link :to="{name: 'patient', params: {pat:rec.patient.hashid}}" class="btn btn-outline-info btn-sm">View</router-link>
                                            </td>
                                        </tr>
                                    </table>
                                    <div v-else>
                                        No sessions recently
                                    </div>
                                </div>
                                <div v-else>
                                    <h6>Patient search</h6>
                                    <table class="table table-striped">
                                        <!-- <tr>
                                            <th>ID</th><th>Name</th><th>Time</th><th>Status</th><td></td>
                                        </tr> -->
                                        <tr v-for="pat in patients" :key="pat.hashid">
                                            <td><i class="fas fa-caret-right"></i></td>
                                            <td><span class="dim-txt fs-4">#{{pat.patno}}</span></td>
                                            <td class="fs-bold wd35">{{pat.fullname}}</td>
                                            <td>time</td>
                                            <td>
                                                <div class="badge badge-primary">Pending</div>
                                            </td>
                                            <td>
                                                <router-link :to="{name: 'patient', params: {pat:pat.hashid}}" class="btn btn-outline-info btn-sm">View</router-link>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            recents: [],
            patients: [],
            term: '',
            loading: true
        }
    },
    mounted() {
        this.getRecent()
    },
    methods: {
        getRecent() {
            axios.get('api/payment/patients').then(response => {
                this.loading = false
                console.log(response.data)
                this.recents = response.data
            }).catch(err => {
                this.loading = false
                console.log(err)
            })
        },
        search() {
            if(!!this.term && this.term.length > 1 && this.term.length < 15) {
                this.loading = true
                axios.get('api/payment/patient/search', {params: {'search': this.term}} ).then(response => {
                    this.loading = false
                    console.log(response.data)
                    this.patients = response.data
                }).catch(error => {
                    this.loading = false
                    console.log(error)
                })
            }
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
    }
}
</script>
