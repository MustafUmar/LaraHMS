<template>
    <div>
        <page-heading heading="Patient"></page-heading>

        <div class="row justify-content-center">
            <div class="col-md-11">

                <div v-if="!error.stat">

                    <div class="trans-loader" v-if="loading">
                        <div class="d-flex justify-content-center align-items-center loader-wrapper">
                            <div class="loader-content fa-lg">
                                <span><i class="fas fa-spinner fa-pulse"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="thin-light-border mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                            <button @click="modalSession" class="btn btn-light"><i class="fas fa-plus-square"></i> New Session</button>
                        </div>

                    </div>

                    <div v-if="patient" class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><span class="fs-4">FL{{patient.file.fileno}} | {{patient.file.type}}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="fs-bold fs-7">{{patient.fullname}}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Gender: {{patient.gender == 'F'?'Female':'Male'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Age: {{patient.age}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div v-if="!patient.file.active" class="alert alert-warning" role="alert">
                                This patient's file is not active!
                            </div>
                            <div v-if="patient.outstand.service || patient.outstand.prescribed" class="card mb-1">
                                <div class="card-header">Oustanding Payment</div>
                                <div class="card-body">
                                    <div v-if="patient.outstand.service && patient.outstand.service.length">
                                        <div v-for="due in patient.outstand.service" :key="due.name" class="d-flex justify-content-between fs-4">
                                            <div><i class="fas fa-caret-right mr-2"></i>{{due.name}}</div>
                                            <div class="text-right py-1"><span class="badge badge-primary">{{due.status}}</span></div>
                                        </div>
                                    </div>
                                    <div v-if="patient.outstand.prescribed && patient.outstand.prescribed.length > 0">
                                        <div v-for="(presc, index) in patient.outstand.prescribed" :key="'presc'+index" class="fs-4">
                                            <i class="fas fa-caret-right mr-2"></i>Prescribed medicine(s) on {{getDate(presc.updated_at)}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Last Session</div>
                                <div class="card-body">

                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Appointments</div>
                                <div class="card-body"></div>
                            </div>
                            <div class="card">
                                <div class="card-header">Scheduled Treatment</div>
                                <div class="card-body"></div>
                            </div>

                        </div>
                    </div>

                </div>

                <div v-else class="row justify-content-center">
                    <div class="col pd-3 bg-lightgray text-center">
                        <p class="text-muted fs-8">No such record !</p>
                        <p><router-link to="/hpl/patients" replace class="btn btn-secondary">Back to patients</router-link></p>
                    </div>
                </div>

            </div>
        </div>

        <!-- modal  -->
        <div class="modal fade new-session-modal" tabindex="-1" role="dialog" aria-labelledby="new-session-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div v-if="patient" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div v-if="!patient.file.active" class="alert alert-warning text-center" role="alert">
                        <h5 class="text-center">Cannot create session !!</h5>
                        <p>This patient's file is not active.</p>
                    </div>
                    <div v-else-if="patient.outstand.length > 0" class="alert alert-warning text-center" role="alert">
                        <h5 class="text-center">Cannot create session !!</h5>
                        <p>This patient has unsettled payment.</p>
                    </div>
                    <div v-else class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div v-if="elm_block" class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['pid'],
    data() {
        return {
            patient: null,
            loading: true,
            error: {
                stat: false,
                message: ''
            }
        }
    },
    mounted() {
        this.getPatient()
    },
    methods: {
        getPatient() {
            axios.get('api/patient/info/'+this.pid).then(response => {
                this.loading = false
                this.patient = response.data
            }).catch(error => {
                this.loading = false
                console.log(error)
            })
        },
        modalSession() {
            $('.modal.new-session-modal').modal('toggle')
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
    },
    computed: {
        elm_block() {
            if(!(this.patient))
                return false
            if(this.patient.file.active || _.isEmpty(this.patient.outstand)) {
                return true
            } else
                return false
        }
    }
}
</script>
