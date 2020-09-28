<template>
    <div>
        <page-heading heading="Patient"></page-heading>

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
                            <!-- <div class="input-group">
                                <input type="text" class="form-control" v-model="term" @keyup.enter="search" aria-label="Patient Search">
                                <div class="input-group-append">
                                    <button class="btn" type="button" @click="search"><i class="fas fa-search"></i></button>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <div class="card mb-4">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>{{patient.fullname}}</td>
                                                </tr>
                                            </table>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Pending Payments</td>
                                                </tr>
                                                <tr>
                                                    <td>Incomplete Payments</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <h5>Total dues</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Date</th>
                                            <th>Session</th>
                                            <th>Status</th>
                                            <td></td>
                                        </tr>
                                        <tr v-for="rec in patient.payment" :key="rec.id">
                                            <td>{{rec.created_at}}</td>
                                            <td>{{rec.patrecord.pat_purpose}}</td>
                                            <td>{{rec.status}}</td>
                                            <td>
                                                <router-link :to="{name:'bill', params: {pay:rec.hashid}}" class="btn btn-sm btn-outline-success">Bill</router-link>
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
    props: ['pat'],
    data() {
        return {
            patient: {},
            loading: true
        }
    },
    mounted() {
        this.getPatient()
    },
    methods: {
        getPatient() {
            axios.get('api/payment/patient/'+this.pat).then(response => {
                this.loading = false
                console.log(response.data)
                this.patient = response.data
            }).catch(err => {
                this.loading = false
                console.log(err)
            })
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
    }
}
</script>
