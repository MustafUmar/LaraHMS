<template>
    <div>
        <page-heading heading="Payments"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <!-- <div class="trans-loader" v-show="loading">
                        <div class="d-flex justify-content-center align-items-center loader-wrapper">
                            <div class="loader-content fa-lg">
                                <i class="fas fa-spinner fa-pulse"></i>
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-8">

                            <div class="row">
                                <div class="col">

                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="two-flexed-right-shrink fs-bold">
                                                <div class="py-2">Pending</div>
                                                <div class="px-4 py-2 border-left fs-10 text-secondary">
                                                    <div v-if="loading" class="text-secondary">
                                                        <i class="fas fa-spinner fa-pulse"></i>
                                                    </div>
                                                    <div v-else>
                                                        {{payments['Pending'] ? payments['Pending'] : 0}}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#" class="stretched-link"></a>
                                        </div>
                                    </div>

                                </div>
                                <div class="col">

                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="two-flexed-right-shrink fs-bold">
                                                <div class="py-2">Incomplete</div>
                                                <div class="px-4 py-2 border-left fs-10 text-secondary">
                                                    <div v-if="loading" class="text-secondary">
                                                        <i class="fas fa-spinner fa-pulse"></i>
                                                    </div>
                                                    <div v-else>
                                                        {{payments['Incomplete'] ? payments['Incomplete'] : 0}}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#" class="stretched-link"></a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    Today
                                </div>
                                <div class="card-body">
                                    <div v-if="loading" class="text-secondary">
                                        <i class="fas fa-spinner fa-pulse"></i>
                                    </div>
                                    <div v-else>
                                        <table class="table table-sm">
                                            <tr>
                                                <td>Patient</td><td>Date</td><td>Status</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <router-link to="/hpl/payments">See More...</router-link>
                            </div>

                        </div>
                        <div class="col-md-4">

                            <div class="card mb-4">
                                <div class="card-header">
                                    Recent Session
                                </div>
                                <div class="card-body">

                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    Recently viewed
                                </div>
                                <div class="card-body">

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
            payments: {},
            term: '',
            loading: true
        }
    },
    mounted() {
        this.getRecent()
    },
    methods: {
        getRecent() {
            axios.get('api/payment/recent').then(response => {
                this.loading = false
                console.log(response.data)
                this.payments = response.data.paystat
                this.recents = response.data.recent
            }).catch(err => {
                this.loading = false
                console.log(err)
            })
        },
        search() {
            this.loading = true
            if(!!this.term && this.term.length > 1 && this.term.length < 15) {
                axios.get('api/patient/search', {params: {'search': this.term}} ).then(response => {
                    this.loading = false
                    console.log(response.data)
                    this.patients = response.data.patients
                    this.files = response.data.files
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
