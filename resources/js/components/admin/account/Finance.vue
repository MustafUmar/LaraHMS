<template>
    <div>
        <page-heading heading="Finance"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <!-- <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <router-link to="/hpl/account/new" class="btn btn-light rounded-btn text-primary"><i class="fas fa-plus fa-lg"></i></router-link>
                        </div>
                        <div class="d-flex align-items-center">

                        </div>
                    </div> -->

                    <div class="card">
                        <!-- <div class="trans-loader" v-show="loading">
                            <div class="d-flex justify-content-center align-items-center loader-wrapper">
                                <div class="loader-content fa-lg">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                            </div>
                        </div> -->

                        <div class="card-body">

                            <div class="card-deck">
                                <div class="card m-5 unlink-dark text-center">
                                    <div class="card-img-top p-5">
                                        <i class="fas fa-chart-line fa-5x"></i>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Revenue</h5>
                                    </div>
                                </div>
                                <router-link to="/hpl/accounts" class="card m-5 unlink-dark text-center">
                                    <span class="card-img-top p-5">
                                        <i class="fas fa-cash-register fa-5x"></i>
                                    </span>
                                    <span class="card-body">
                                        <h5 class="card-title">Accountants</h5>
                                    </span>
                                </router-link>
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
    // methods: {
    //     goBack() {
    //         window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/dashboard')
    //     },
    // }
}
</script>
