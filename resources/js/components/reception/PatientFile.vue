<template>
    <div>
        <page-heading heading="File"></page-heading>

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

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                    <div v-if="patfile && !patfile.active" class="alert alert-warning" role="alert">
                        This file is not active!
                    </div>
                    <div class="row" v-if="patfile">
                        <div class="col-md">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <span class="fs-bold">File {{patfile.fileno}}</span>
                                    <p class="fs-4 m-0"><span >Type: {{patfile.type}}</span></p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header"><h6>Persons</h6></div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <router-link :to="{name: 'patient', params:{pid:person.hashid}}" v-for="person in patfile.patients" :key="person.patid" class="list-group-item list-group-item-action">
                                            <p class="mb-0 text-muted fs-4">ID: {{person.patno}}</p>
                                            <p class="mb-0 fs-bold">{{person.fullname}}</p>
                                            <p v-if="person.patrecord[0]" class="mb-0 text-muted fs-4" >
                                                Last Session: {{getDate(person.patrecord[0].updated_at)}}
                                            </p>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="patfile.outstand.service || patfile.outstand.prescribed" class="col-md-4">
                            <div class="card mb-1">
                                <div class="card-header"><h6>Outstanding Payment</h6></div>
                                <div class="card-body">
                                    <div v-if="patfile.outstand.service && patfile.outstand.service.length">
                                        <div v-for="due in patfile.outstand.service" :key="due.name" class="d-flex justify-content-between fs-4">
                                            <div><i class="fas fa-caret-right mr-2"></i>{{due.name}}</div>
                                            <div class="text-right py-1"><span class="badge badge-primary">{{due.status}}</span></div>
                                        </div>
                                    </div>
                                    <div v-if="patfile.outstand.prescribed && patfile.outstand.prescribed.length > 0">
                                        <div v-for="(presc, index) in patfile.outstand.prescribed" :key="'presc'+index" class="fs-4">
                                            <i class="fas fa-caret-right mr-2"></i>Prescribed medicine(s) on {{getDate(presc.updated_at)}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="row justify-content-center">
                        <div class="col pd-3 bg-lightgray text-center">
                            <p class="text-muted fs-8">No such record !</p>
                            <p><router-link to="/hpl/patients" replace class="btn btn-secondary">Back</router-link></p>
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

    </div>
</template>

<script>
export default {
    props: ['fid'],
    data() {
        return {
            patfile: null,
            loading: true,
            error: {
                stat: false,
                message: ''
            }
        }
    },
    mounted() {
        this.getFile()
    },
    methods: {
        getFile() {
            if(this.fid)
                axios.get('api/patient/file/'+this.fid).then(response => {
                    this.loading = false
                    this.patfile = response.data
                }).catch(error => {
                    this.loading = false
                    console.log(error)
                })
            else {
                this.error.stat = true
                this.error.message = 'No identifier'
                this.loading = false
            }
        },
        getDate(dt) {
            return this.$root.luxonDate.fromSQL(dt).toLocaleString(this.$root.luxonDate.DATETIME_MED);
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
    },
    computed: {
        outstandDue() {
            if(this.patfile.outstand) {
                var charge = _.reduce(this.patfile.outstand, function(ac, val) {
                    if(val.status !== 'Complete' )
                        return ac + val.charge - val.paid
                }, 0)

            } else
                return null
        }
    }
}
</script>
