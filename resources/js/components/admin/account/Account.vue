<template>
    <div>
        <page-heading heading="Accountant"></page-heading>

        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-11">

                <div v-if="!error.stat">

                    <div class="trans-loader" v-if="loading">
                        <div class="d-flex justify-content-center align-items-center loader-wrapper">
                            <div class="loader-content fa-lg">
                                <span><i class="fas fa-spinner fa-pulse"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                            <router-link  :to="{name:'acc_edit', params:{'acc':account}}" class="btn btn-light"><i class="fas fa-pen"></i> Edit</router-link>
                            <span class="dropdown">
                                <button class="btn btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="0,0">
                                    <i class="fas fa-user-circle"></i> Account
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="btn btn-outline-dark dropdown-item"><i class="fas fa-user-lock"> Lock</i></button>
                                    <button class="btn btn-outline-dark dropdown-item" @click="resetAcount"><i class="fas fa-sync"> Reset</i></button>
                                </div>
                            </span>
                            <span class="dropdown">
                                <button class="btn btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="0,0">
                                    <i class="fas fa-exchange-alt"></i> Change role
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="btn btn-outline-dark dropdown-item" @click="swapRole"><i class="fas fa-pen"> Staff</i></button>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <h5>Personal Information</h5>
                            <div class="row">
                                <div class="col-sm-3 order-sm-2">
                                    <div>
                                        <img :src="account.phurl?account.phurl:'/images/img-invalid.png'" alt="profile" class="img-thumbnail sc3by4-5"/>
                                    </div>
                                    <hr class="hr"/>
                                    <div class="row">
                                        <div class="col-7">
                                            <label>Active: </label>
                                        </div>
                                        <div class="col-5">
                                            <i v-if="account.active" class="fas fa-check text-success"></i>
                                            <i v-else class="fas fa-times text-danger"></i>
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="col-7"><label>Last Activity: </label></div>
                                        <div class="col-5">None</div>

                                    </div>
                                </div>
                                <div class="col-sm-9 order-sm-1">
                                    <table class="table">
                                        <tr>
                                            <th>Id</th><td>{{account.personid}}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th><td>{{account.fullname}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th><td>{{account.email}}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th><td>{{account.phone}}</td>
                                        </tr>
                                    </table>
                                </div>

                            </div>


                            <h5>Activity</h5>
                            <div class="row">
                                <div class="col-sm-9">
                                    <table class="table">
                                        <tr>
                                            <th>Patients diagnosed</th><td>None</td>
                                        </tr>
                                        <tr>
                                            <th>Medicines Prescibed</th><td> - </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div v-else class="row justify-content-center">
                    <div class="col pd-3 bg-lightgray text-center">
                        <p class="text-muted fs-8">No such record !</p>
                        <p><router-link to="/hpl/accounts" replace class="btn btn-secondary">Back to accounts</router-link></p>
                    </div>
                </div>


            </div>
        </div>




    </div>
</template>

<script>
export default {
    props: ['acc'],
    data() {
        return {
            account: {},
            loading: true,
            error: {
                stat: false,
                message: 'No such record.'
            }
        }
    },
    created() {
        if(!this.acc) {
            this.$set(this.error, 'stat', true)
            this.$set(this.error, 'message', 'No such record.')
            this.loading = false
        } else
            this.getAcc()
    },
    methods: {
        getAcc() {
            axios.get(`api/account/${this.acc}`).then(response => {
                this.$set(this, 'account', response.data)
                this.loading = false
            }).catch(error => {
                this.loading = true
                this.$set(this.error, 'stat', true)
                this.$set(this.error, 'message', 'No such record.')
                if(error.response)
                    console.log(error.response)
            })
        },
        swapRole() {
            this.loading = true
            axios.post(`api/staff/swap/${this.acc}`, {role: 'Staff'}).then(response => {
                this.loading = false
                this.$router.replace({name:'staff', params:{stf: this.acc}}, () => {
                    this.$root.fireNotifier('Role changed', 'success')
                })
            }).catch(error => {
                this.loading = false
                console.log(error)
                this.$root.fireNotifier('Unable to change role', 'danger')
            })
        },
        resetAcount() {
            this.loading = true
            axios.post(`api/account/reset/${this.acc}`).then(response => {
                this.loading = false
                this.$root.fireNotifier('Account has been reset', 'success')
            }).catch(error => {
                this.loading = false
                this.$root.fireNotifier(error.response.data.message, 'error')
            })
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/accounts')
        },
    }
}
</script>
