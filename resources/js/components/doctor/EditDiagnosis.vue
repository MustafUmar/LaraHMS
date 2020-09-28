<template>
    <div>
        <page-heading heading="Patient Diagnosis"></page-heading>

        <div class="row justify-content-center">
            <div class="col-sm-10">

                <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                    <div>
                        <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="form-group col-md">
                            <label for="docfirstname">Symptoms</label>
                            <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.symptoms.$error || getError('symptoms')}" id="patsymptoms" v-model="form.symptoms" placeholder="First Name">
                            <div class="invalid-feedback">
                                <span v-if="$v.form.symptoms.$error">Please enter first name.</span>
                                <span v-if="getError('symptoms')">{{errors.symptoms[0]}}</span>
                            </div>
                        </div>

                        <div class="form-group col-md">
                            <label for="docfirstname">Diagnosis</label>
                            <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.diagnosis.$error || getError('diagnosis')}" id="patdiagnosis" v-model="form.diagnosis" placeholder="First Name">
                            <div class="invalid-feedback">
                                <span v-if="$v.form.diagnosis.$error">Please enter first name.</span>
                                <span v-if="getError('diagnosis')">{{errors.diagnosis[0]}}</span>
                            </div>
                        </div>

                        <div class="form-group col-md">
                            <label for="docfirstname">Medications</label>
                            <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.prescribe.$error || getError('prescribe')}" id="patprescribe" v-model="form.prescribe" placeholder="First Name">
                            <div class="invalid-feedback">
                                <span v-if="$v.form.prescribe.$error">Please enter first name.</span>
                                <span v-if="getError('prescribe')">{{errors.prescribe[0]}}</span>
                            </div>
                        </div>

                        <hr class="hr">
                        <div class="form-group">
                            <button @click="submitForm" class="btn btn-primary" :disabled="disable">
                                Save
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>

import { required, minLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            form: {
                symptoms: '',
                diagnosis: '',
                prescribe: ''
            },
            disable: false,
            loading: false,
            errors: {}
        }
    },
    methods: {
        submitForm() {
            this.errors = {}
            this.$v.form.$touch()
            if(!this.$v.form.$invalid) {
                this.$v.form.$reset()
                this.disable = true

                axios.post('api/pat/diag/', this.form)
                .then(response => {
                    console.log(response.data)
                    this.$router.replace('/hpl/diagnosis', () => {
                        this.$root.fireNotifier('Success', 'success')
                    })
                }).catch(error => {
                    if(error.response) {
                        if(error.response.status == 422) {
                            this.errors = error.response.data.errors
                            this.disable = false
                        }
                        else {
                            console.log(error.response.data)
                            this.$router.replace('/hpl/diagnosis', () => {
                                this.$root.fireNotifier('Error, '+error.response.data.message, 'error')
                            })
                        }
                    }
                })
            }
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/dashboard')
        },
        getError(field) {
            if(_.has(this.errors, field))
                return this.errors[field][0]
            else return null
        }
    },
    validations: {
        form: {
            symptoms: {required},
            diagnosis: {required},
            prescribe: {required},
        }

    }
}
</script>
