<template>
    <div>
        <page-heading heading="New File"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h6>File Type</h6>
                                <select v-model="form.filetype" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.filetype.$error || getError('filetype')}">
                                    <option value="" disabled selected>-- Select file type --</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Family">Family</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="$v.form.filetype.$error">Select file type.</span>
                                    <span v-if="getError('filetype')">{{errors.filetype[0]}}</span>
                                </div>
                            </div>
                            <hr class="hr"/>
                            <h6>Person</h6>
                            <div class="thin-light-border p-2 mb-2">
                                <div class="form-row">
                                    <div class="col-sm-12 px-2"><label><i class="fas fa-user"></i> Person (Main)</label></div>
                                    <div class="form-group col-md">
                                        <label for="patfirstname">First Name</label>
                                        <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.firstname.$error || getError('main.firstname')}" id="patfirstname" v-model="form.main.firstname" placeholder="First Name">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.main.firstname.$error">Please enter first name.</span>
                                            <span v-if="getError('main.firstname')">{{errors['main.firstname'][0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="patothername">Other Name</label>
                                        <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':getError('main.othername')}" id="patothername" v-model="form.main.othername" placeholder="Other Name (Optional)">
                                        <div class="invalid-feedback">
                                            <!-- <span v-if="$v.form.main.othername.$error">.</span> -->
                                            <span v-if="getError('main.othername')">{{errors['main.othername'][0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="patlastname">Last Name</label>
                                        <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.lastname.$error || getError('main.lastname')}" id="patlastname" v-model="form.main.lastname" placeholder="Last Name">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.main.lastname.$error">Please enter first name.</span>
                                            <span v-if="getError('main.lastname')">{{errors['main.lastname'][0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md">
                                        <label>Gender</label>
                                        <select v-model="form.main.gender" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.gender.$error || getError('main.gender')}">
                                            <option value="" disabled selected>-- Select gender --</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.main.gender.$error">Select gender.</span>
                                            <span v-if="getError('main.gender')">{{errors['main.gender'][0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label>Date of birth</label>
                                        <!-- <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.main.dob.$error || getError('dob')}" id="patdob" v-model="form.main.dob" placeholder="Date of birth" data-provide="datepicker"> -->
                                        <datetimepicker @input="getDob($event)" v-model="form.main.dob" :input-class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.dob.$error || getError('main.dob')}" :format="{'DateTime':'DATE_SHORT'}" v-bind:auto="true" value-zone="local" placeholder="Date of birth">
                                            <div class="invalid-feedback" slot="after">
                                                <span v-if="$v.form.main.dob.$error">Select date of birth.</span>
                                                <span v-if="getError('main.dob')">{{errors['main.dob'][0]}}</span>
                                            </div>
                                        </datetimepicker>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md">
                                        <label for="patemail">Email</label>
                                        <input type="email" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.email.$error || getError('main.email')}" id="patemail" v-model="form.main.email" placeholder="Email">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.main.email.$error">Please enter valid email.</span>
                                            <span v-if="getError('main.email')">{{errors['main.email'][0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="patphone">Phone</label>
                                        <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.main.phone.$error || getError('main.phone')}" id="patphone" v-model="form.main.phone" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            <span v-if="$v.form.main.phone.$error">Please enter phone number.</span>
                                            <span v-if="getError('main.phone')">{{errors['main.phone'][0]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.filetype == 'Family'">
                                <button class="btn btn-sm " data-toggle="collapse" data-target="#more-pat-collapse">
                                    <i class="fas fa-plus"></i> More Family Members
                                </button>
                                <div class="collapse" id="more-pat-collapse">
                                    <hr class="hr"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Members</h6>
                                            <div v-if="form.members.length > 0">
                                                <div v-for="(pat, index) in form.members" :key="'adult'+index" class="thin-light-border p-2 mb-2">
                                                    <div class="form-row">
                                                        <div class="col-sm-12 px-2"><label><i class="fas fa-user"></i> Member {{index+1}}</label></div>
                                                        <div class="form-group col-md">
                                                            <label for="patfirstname">First Name</label>
                                                            <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.members.$each.$iter[index].firstname.$error || getError('members.'+index+'.firstname')}" v-model="form.members[index].firstname" placeholder="First Name">
                                                            <div class="invalid-feedback">
                                                                <span v-if="$v.form.members.$each.$iter[index].firstname.$error">Please enter first name.</span>
                                                                <span v-if="getError('members.'+index+'.firstname')">Please enter correct firstname.</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label for="patlastname">Last Name</label>
                                                            <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.members.$each.$iter[index].lastname.$error || getError('members.'+index+'.lastname')}" v-model="form.members[index].lastname" placeholder="Last Name">
                                                            <div class="invalid-feedback">
                                                                <span v-if="$v.form.members.$each.$iter[index].lastname.$error">Please enter last name.</span>
                                                                <span v-if="getError('members.'+index+'.lastname')">Please enter correct lastname.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md">
                                                            <label>Gender</label>
                                                            <select v-model="form.members[index].gender" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.members.$each.$iter[index].gender.$error || getError('members.'+index+'.gender')}">
                                                                <option value="" disabled selected>-- Select gender --</option>
                                                                <option value="M">Male</option>
                                                                <option value="F">Female</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                <span v-if="$v.form.members.$each.$iter[index].gender.$error">Select gender.</span>
                                                                <span v-if="getError('members.'+index+'.gender')">Please select gender</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label>Date of birth</label>
                                                            <!-- <input type="text" :class="{'form-control':true, 'is-invalid':$v.form.members.$each.$iter[index].dob.$error || getError('dob')}" v-model="form.members[index].dob" placeholder="Last Name"> -->
                                                            <datetimepicker @input="getMemDob(index, $event)" v-model="form.members[index].dob" :input-class="{'form-control':true, 'form-control-sm':true, 'is-invalid':$v.form.members.$each.$iter[index].dob.$error || getError('members.'+index+'.dob')}" :format="{'DateTime':'DATE_SHORT'}" v-bind:auto="true" value-zone="local" placeholder="Choose date of birth">
                                                                <div class="invalid-feedback" slot="after">
                                                                    <span v-if="$v.form.members.$each.$iter[index].dob.$error">Select date of birth.</span>
                                                                    <span v-if="getError('members.'+index+'.dob')">Please select date of birth</span>
                                                                </div>
                                                            </datetimepicker>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md">
                                                            <label for="patemail">Email</label>
                                                            <input type="email" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid': getError('members.'+index+'.email')}" v-model="form.members[index].email" placeholder="Email">
                                                            <div class="invalid-feedback">
                                                                <span v-if="$v.form.members.$each.$iter[index].email.$error">Please enter valid email.</span>
                                                                <span v-if="getError('members.'+index+'.email')">Please enter valid email.</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label for="patphone">Phone</label>
                                                            <input type="text" :class="{'form-control':true, 'form-control-sm':true, 'is-invalid': getError('members.'+index+'.phone')}" v-model="form.members[index].phone" placeholder="Phone Number">
                                                            <div class="invalid-feedback">
                                                                <!-- <span v-if="$v.form.members.$each.$iter[index].email.$error">Please enter valid email.</span>
                                                                <br/> -->
                                                                <span v-if="getError('members.'+index+'.phone')">Please enter valid phone number.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <p class="text-muted"><em>Add member</em></p>
                                            </div>
                                            <div class="form-group">
                                                <button @click="addMember" class="btn btn-outline-primary btn-sm" :disabled="disable">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button @click="removeMember" class="btn btn-outline-primary btn-sm" :disabled="disable">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <hr class="hr"/>
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
    </div>
</template>

<script>
import { Datetime } from 'vue-datetime';
import { DateTime as LuxonDateTime } from 'luxon';
import { required, minLength, email } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            form: {
                filetype: '',
                main: {firstname: '', othername: '', lastname: '', gender: '', dob: '', email: '', phone: ''},
                members: []
            },
            disable: false,
            errors: {}
        }
    },
    methods: {
        addMember() {
            if(!(this.form.members.length >= 8)) {
                this.form.members.push({firstname: '', othername:'', lastname: '', gender: '', dob: '', email: '', phone: ''})
            }
        },
        removeMember() {
            if(!(this.form.members.length <= 0))
                this.form.members.pop()
        },
        submitForm() {
            this.errors = {}
            this.$v.form.$touch()
            if(!this.$v.form.$invalid) {
                this.$v.form.$reset()
                // this.disable = true

                axios.post('api/patient/file/new', this.form)
                .then(response => {
                    console.log(response.data)
                    const patfile = response.data.file //file returning null
                    if(!!patfile)
                        this.$router.replace({name:'patfile', params:{ fid:patfile.hashid } }, () => {
                            this.$root.fireNotifier('File created successfully', 'success')
                        })
                    else
                        this.$router.replace('/hpl/patients', () => {
                            this.$root.fireNotifier('File created successfully', 'success')
                        })
                }).catch(error => {
                    if(error.response) {
                        console.log(error.response.data)
                        if(error.response.status == 422) {
                            this.errors = error.response.data.errors
                            this.disable = false
                        }
                        else {
                            console.log(error.response.data)
                            this.$router.replace('/hpl/patients', () => {
                                this.$root.fireNotifier('Error, '+error.response.data.message, 'error')
                            })
                        }
                    }
                })
            }
        },
        getDob(e) {
            if(!!e)
                this.form.main.dob = LuxonDateTime.fromISO(e).toFormat('yyyy-MM-dd')
        },
        getMemDob(i,e) {
            if(!!e)
                this.form.members[i].dob = LuxonDateTime.fromISO(e).toFormat('yyyy-MM-dd')
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
        getError(field) {
            if(_.has(this.errors, field))
                return this.errors[field][0]
            else return null
        }
    },
    validations: {
        form: {
            filetype: { required },
            main: {
                firstname: { required },
                lastname: { required },
                gender: { required },
                dob: { required },
                email: { email },
                phone: { required },
            },
            members: {
                $each: {
                    firstname: { required },
                    lastname: { required },
                    gender: { required },
                    dob: { required },
                    email: { email }
                }
            },
        }

    },
    components: {
        'datetimepicker': Datetime
    }
}
</script>
