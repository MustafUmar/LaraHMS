<template>
    <div>
        <page-heading heading="Edit Receptionist"></page-heading>

        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-11">

                <div v-if="!err.stat">

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
                    </div>

                    <div class="card">
                        <div class="card-body p-3">
                            <form action="javascript:void(0)" enctype="multipart/form-data">
                                <h5>Personal Information</h5>
                                <hr class="hr">
                                <div class="form-row">
                                    <div class="form-group col-md">
                                        <label for="docfirstname">First Name</label>
                                        <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.firstname.$error || getError('firstname')}" id="docfirstname" v-model="form.firstname" placeholder="First Name">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.firstname.$error">Please enter first name.</span>
                                            <span v-if="getError('firstname')">{{errors.firstname[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="docothername">Other Name</label>
                                        <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.othername.$error || getError('othername')}" id="docothername" v-model="form.othername" placeholder="Other Name (Optional)">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.othername.$error">.</span>
                                            <span v-if="getError('othername')">{{errors.othername[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="doclastname">Last Name</label>
                                        <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.lastname.$error || getError('lastname')}" id="doclastname" v-model="form.lastname" placeholder="Last Name">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.firstname.$error">Please enter last name.</span>
                                            <span v-if="getError('lastname')">{{errors.lastname[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md">
                                        <label for="docemail">Email</label>
                                        <input type="email" :class="{'form-control':true, 'is-invalid':$v.form.email.$error || getError('email')}" id="docemail" v-model="form.email" placeholder="Email">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.email.$error">Please enter valid email adddress.</span>
                                            <span v-if="getError('email')">{{errors.email[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="docphone">Phone</label>
                                        <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.phone.$error || getError('phone')}" id="docphone" v-model="form.phone" placeholder="Phone">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.phone.$error">Please enter phone number.</span>
                                            <span v-if="getError('phone')">{{errors.phone[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <div>
                                            <img :src="preview" alt="profile" :class="{'img-thumbnail':true, 'border-danger':getError('photo'), 'sc3by4-5':true}"/>
                                            <div class="btn btn-sm btn-block btn-secondary upload-btn">
                                                <i class="fa fa-upload"></i>
                                                {{!!image ? 'Change' : 'Upload Image'}}
                                                <input class="doc-upload-image" type="file" @change="imageUpload($event)"/>
                                            </div>
                                            <button v-if="!!image" class="btn btn-sm btn-block btn-secondary" @click="resetUpload">
                                                <i class="fas fa-sync"></i> Reset
                                            </button>
                                        </div>
                                        <div :class="{'invalid-feedback':true, 'd-block': getError('photo')}">
                                            <span v-if="getError('photo')">{{errors.photo[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <h5>Account Information</h5>
                                <hr class="hr">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" v-model="form.account" true-value="1" false-value="0" id="account-switch">
                                        <label class="custom-control-label" for="account-switch">Create sign-in account</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="docemailacc">Account ID</label>
                                        <input type="email" class="form-control" disabled id="docemailacc" :value="form.email" placeholder="Email">
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <p class="text-center"><em>A default password will be set and sent to this doctor to activate his/her account.</em></p>
                                    </div>
                                </div>
                                <hr class="hr">
                                <div class="form-group">
                                    <button @click="submitForm" class="btn btn-primary" :disabled="disable">
                                        Save
                                    </button>
                                    <!-- <button @click="goBack" class="btn btn-outline-dark">Cancel</button> -->
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div v-else class="row justify-content-center">
                    <div class="col pd-3 bg-lightgray text-center">
                        <p class="text-muted fs-8">No such record !</p>
                        <p><router-link to="/hpl/receptions" replace class="btn btn-secondary">Back to receptions</router-link></p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>

import { required, minLength, email, alpha } from 'vuelidate/lib/validators'

export default {
    props:['rep'],
    data() {
        return {
            form: {
                firstname: '',
                lastname: '',
                othername: '',
                email: '',
                phone: '',
                account: '0',
                photo: null,
                phurl: null
            },
            orig: {},
            image: null,
            disable: false,
            loading: false,
            errors: {},
            err: {
                stat: false,
                message: ''
            }
        }
    },
    mounted() {
        if(_.isEmpty(this.rep)) {
            this.$set(this.err, 'stat', true)
            this.$set(this.err, 'message', 'No such record.')
        } else {
            _.assign(this.form, this.rep)
            this.orig = JSON.parse(JSON.stringify(this.rep))
            this.$set(this.form, 'account',this.rep.active)
        }

    },
    methods: {
        // getStaff() {
        //     axios.get(`api/staff/${this.rep}`).then(response => {
        //         console.log(response.data)
        //         this.$set(this, 'staff', JSON.parse(JSON.stringify(response.data)))
        //         this.orig = JSON.parse(JSON.stringify(response.data))
        //     }).catch(error => {
        //         if(error.response)
        //             console.log(error.response)
        //     })
        // },
        submitForm() {
            this.loading = true
            this.errors = {}
            this.$v.form.$touch()
            if(!this.$v.form.$invalid) {
                this.$v.form.$reset()
                this.disable = true
                var fmdata = new FormData()
                _.forOwn(this.form, function(v,k) {
                    fmdata.append(k,v)
                })

                axios.post('api/reception/update/'+this.rep.id, fmdata, {headers: { 'Content-Type': 'multipart/form-data' }})
                .then(response => {
                    this.loading = false
                    this.$router.replace('/hpl/receptions', () => {
                        this.$root.fireNotifier('Receptionist record updated successfully', 'success')
                    })
                }).catch(error => {
                    this.loading = false
                    if(error.response) {
                        if(error.response.status == 422) {
                            this.errors = error.response.data.errors
                            this.disable = false
                        }
                        else {
                            console.log(error.response.data)
                            this.$router.replace('/hpl/receptions', () => {
                                this.$root.fireNotifier('Error, '+error.response.data.message, 'error')
                            })
                        }
                    }
                })
            }
        },
        imageUpload(e) {
            _.unset(this.errors,'photo')
            if(e.target.files && e.target.files[0]) {
                const file = e.target.files[0]
                const filetype = file.type

                if (new RegExp("(.*?)\.(jpg|jpeg|gif|png)$").test(filetype)) {
                    var reader = new FileReader();
                    reader.onload = (ev) => {
                        this.image = ev.target.result
                    }
                    reader.readAsDataURL(file)

                    this.form.photo = file
                } else {
                    this.$set(this.errors, 'photo', ['Please upload a valid image file.'])
                    this.$set(this, 'image', '/images/img-invalid.png')
                }

            }
        },
        resetUpload() {
            _.unset(this.errors,'photo')
            this.$set(this, 'image', null)
            this.$set(this.form, 'photo', this.orig.photo)
            this.$set(this.form, 'phurl', this.orig.phurl)
            $('.doc-upload-image').val('')
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/receptions')

        },
        getError(field) {
            if(_.has(this.errors, field))
                return this.errors[field][0]
            else return null
        }
    },
    computed: {
        preview() {
            return !!this.image ? this.image : this.form.phurl ? this.form.phurl : '/images/abstrct_doc.png'
        }
    },
    validations: {
        form: {
            firstname: {required},
            lastname: {required},
            othername: {alpha},
            email: {required, email},
            phone: {required},
        }

    }
}
</script>
