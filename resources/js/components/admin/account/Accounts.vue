<template>
    <div>
        <page-heading heading="Accountants"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                            <router-link to="/hpl/account/new" class="btn btn-light rounded-btn text-primary"><i class="fas fa-plus fa-lg"></i></router-link>
                        </div>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>

                    <div class="card">
                        <div class="trans-loader" v-show="loading">
                            <div class="d-flex justify-content-center align-items-center loader-wrapper">
                                <div class="loader-content fa-lg">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div v-if="accounts.length" class="list-group">
                                <div class="list-group-item" v-for="acc in accounts" :key="'acc'+acc.personid">
                                    <div class="row thin-light-border bg-lightgray align-items-center p-2">
                                        <div class="col-md-2">
                                            <div class="img-size-4">
                                                <img :src="acc.phurl ? acc.phurl :'/images/abstrct_doc.png'" class="img-thumbnail img-fluid" alt="...">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>{{acc.fullname}}</h5>
                                            <div>Last seen: </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <router-link :to="{name:'account', params:{'acc':acc.hashid}}" class="btn btn-outline-dark"><i class="fas fa-eye"></i> View</router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="alert alert-secondary text-center" role="alert">
                                    <span class="fs-7">No accountants !!</span>
                                    <div><button class="btn btn-light" @click="$router.push('/hpl/account/new')">Add Accountant</button></div>
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
            accounts:[],
            loading: true
        }
    },
    created() {
        this.getAccount()
    },
    methods: {
        getAccount() {
            axios.get('api/accounts').then(response => {
                this.accounts = response.data
                this.loading = false
            }).catch(error => {
                console.log(error)
                this.loading = false
            })
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/dashboard')
        },
    }
}
</script>
