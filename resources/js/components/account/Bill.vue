<template>
    <div>
        <page-heading heading="Bill"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-11">

                    <div v-if="!error.stat">

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
                                <button v-if="serviceStat == 'Complete' && prescStat == 'Complete'" class="btn btn-light"><i class="fas fa-print"></i> Print Receipt</button>
                            </div>
                            <div class="d-flex align-items-center">
                            </div>
                        </div>

                        <div v-if="Object.keys(errors).length > 0" class="alert alert-warning" role="alert">
                            <div>
                                <i class="fas fa-exclamation-circle"></i>
                                <span class="text-danger"> {{getSingleError()}}</span>
                            </div>
                        </div>

                        <!-- <div v-if="Object.keys(errors).length > 0" class="alert alert-warning" role="alert">
                            <div v-if="getError('amount')">
                                <i class="fas fa-exclamation-circle"></i>
                                <span class="text-danger"> {{getError('amount')}}</span>
                            </div>
                            <div v-else-if="getError('pid')">
                                <i class="fas fa-exclamation-circle"></i>
                                <span class="text-danger"> Missing payment reference.</span>
                            </div>
                        </div> -->

                        <div class="card mb-4">

                            <div v-if="payment.patient" class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card mb-1">
                                            <div class="card-body">
                                                <div class="two-flexed-spacebtw">
                                                    <div>
                                                        <span class="dim-txt">{{payment.patient.patno}}</span>
                                                        <div class="fs-bolder">{{payment.patient.fullname}}</div>
                                                    </div>
                                                    <div class="fs-4 badge badge-info pb-1">
                                                        <p class="p-1 m-0 border-bottom">Status</p>
                                                        <div class="py-2">
                                                            <span v-if="serviceStat == 'Complete' && prescStat == 'Complete'">
                                                                Complete <i class="fas fa-check-circle ml-3"></i>
                                                            </span>
                                                            <span v-else-if="serviceStat == 'Incomplete' || prescStat == 'Incomplete'">
                                                                Incomplete <i class="fas fa-minus-circle ml-3"></i>
                                                            </span>
                                                            <span v-else>
                                                                Pending <i class="fas fa-minus-circle ml-3"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="card mb-1">
                                            <div class="card-header two-flexed-spacebtw">
                                                <h6>Services</h6>
                                                <div class="fs-3 dim-txt">
                                                    <span v-if="serviceStat == 'Complete'" class="badge badge-success">
                                                        Full
                                                    </span>
                                                    <span v-else-if="serviceStat == 'Incomplete'" class="badge badge-info">
                                                        Partial
                                                    </span>
                                                    <span v-else class="badge badge-secondary">
                                                        No pay
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div v-if="totalService">
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>Name</th><th>Price</th><th>Amount Paid</th>
                                                        </tr>
                                                        <tr v-for="sv in payment.patservice" :key="sv.id">
                                                            <td>{{sv.services.name}}</td>
                                                            <td>{{sv.services.charge}}</td>
                                                            <td>{{sv.paid}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total</th><th>{{totalService}}</th>
                                                            <th>
                                                                Balance:
                                                                <span v-if="(serviceBalance - totalService) < 0" class="text-danger ml-2">{{serviceBalance - totalService}}</span>
                                                                <span v-else class="text-success ml-2">{{serviceBalance - totalService}}</span>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div v-else>
                                                    <p>None</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-1">
                                            <div class="card-header two-flexed-spacebtw">
                                                <h6>Prescription</h6>
                                                <div class="fs-3 dim-txt">
                                                    <span v-if="prescStat == 'Complete'" class="badge badge-success">
                                                        Full
                                                    </span>
                                                    <span v-else-if="prescStat == 'Incomplete'" class="badge badge-info">
                                                        Partial
                                                    </span>
                                                    <span v-else class="badge badge-secondary">
                                                        No pay
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div v-if="totalMeds">
                                                    <table class="table table-sm mb-1" v-for="(presc,index) in payment.patpresc" :key="presc.id">
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Price</th>
                                                            <th>Amount paid</th>
                                                        </tr>
                                                        <tr>
                                                            <td>4 unit of Medicine name</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr v-for="(med,xin) in presc.meds" :key="med.id">
                                                            <td>{{med.qty+' '+med.unit+' of '+med.pharm.name}}</td>
                                                            <td>{{medPrice(index,xin)}}</td>
                                                            <td>{{med.amount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total</th><th>{{totalPerPresc(index)}}</th>
                                                            <th>
                                                                Balance:
                                                                <span v-if="prescBalance(index) < 0" class="text-danger ml-2">{{prescBalance(index)}}</span>
                                                                <span v-else class="text-success ml-2">{{prescBalance(index)}}</span>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div>
                                                    None
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Bill details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped table-sm fs-5">
                                                    <tr>
                                                        <td>Medicines:</td><td>{{totalMeds ? totalMeds: 'None'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Services:</td><td>{{totalService ? totalService: 'None'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fs-bold">Subtotal:</span></td>
                                                        <td>{{subTotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7.5% VAT:</td><td>{{0.075*subTotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fs-bold fs-6 text-primary">Total:</span></td>
                                                        <td>{{grandTotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="text-info">Amount Paid:</span></td>
                                                        <td>{{parseFloat(serviceBalance) + parseFloat(medsBalance)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fs-blod fs-6 text-info">Balance:</span></td>
                                                        <td>{{(parseFloat(serviceBalance) + parseFloat(medsBalance)) - parseFloat(grandTotal)}}</td>
                                                    </tr>
                                                </table>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#bill-charge-modal">
                                                    Charge
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- modal -->
                        <div class="modal bill-payment" tabindex="-1" role="dialog" id="bill-charge-modal">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-info p-1">
                                            <small>Only complete payment is accepted for bills below 2000.</small>
                                        </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="two-flexed-spacebtw alert alert-primary p-1 mb-2">
                                                <label class="m-0">Balance</label>
                                                <p class="m-0 fs-7 fs-bold">
                                                    {{ totalBalance }}
                                                </p>
                                                <!-- <p class="m-0 fs-7 fs-bold">
                                                    {{ parseFloat(grandTotal) - (parseFloat(serviceBalance) + parseFloat(medsBalance)) }}
                                                </p> -->
                                            </div>
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" v-model.number="amount" placeholder="Enter amount">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">.00</span>
                                                    </div>
                                                </div>
                                                <small v-if="$v.amount.$error" class="form-text text-danger">Enter valid amount.</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Payment type</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-credit-card"></i>
                                                        </span>
                                                    </div>
                                                    <select class="custom-select" v-model="type">
                                                        <option disabled selected>Choose...</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="credit_card">Credit Card</option>
                                                    </select>
                                                </div>
                                                <small v-if="$v.type.$error" class="form-text text-danger">Select payment type.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="chargeBill">Proceed</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            </div>
        </div>




    </div>
</template>

<script>
// import { parse } from 'path';
import { required, minValue, maxValue } from 'vuelidate/lib/validators'
export default {
    props: ['pay'],
    data() {
        return {
            payment: {},
            amount: null,
            type: '',
            loading: true,
            error: {
                stat: false,
                message: 'No such record.'
            },
            errors: {}
        }
    },
    mounted() {
        this.getBill()
    },
    methods: {
        getBill() {
            axios.get('api/payment/bill/'+this.pay).then(response => {
                this.loading = false
                console.log(response.data)
                this.payment = response.data
            }).catch(err => {
                this.error.stat = true
                this.loading = false
                console.log(err)
            })
        },
        chargeBill() {
            this.$v.$touch()
            if(!this.$v.$invalid) {
                if(!!this.pay) {
                    this.loading = true
                    $('#bill-charge-modal').modal('hide')
                    this.errors = {}
                    axios.post('api/payment/bill/charge', {pid:this.pay, amount: this.amount, type: this.type}).then(response => {
                        console.log(response.data)
                        this.loading = false
                        alertify.set('notifier','position', 'top-center');
                        alertify.notify('Success', 'success', 5,);
                    }).catch(err => {
                        this.loading = false
                        if(err.response) {
                            console.log(err.response)
                            if(err.response.status == 422) {
                                this.errors = err.response.data.errors
                                this.disable = false
                            }
                            // else {
                            //     console.log(error.response.data)
                            //     this.$router.replace('/hpl/accounts', () => {
                            //         this.$root.fireNotifier('Error, '+error.response.data.message, 'error')
                            //     })
                            // }
                        } else console.log(err)
                    })
                }
            }
        },
        getError(field) {
            if(_.has(this.errors, field))
                return this.errors[field][0]
            else return null
        },
        getSingleError() {
            if(!_.isEmpty(this.errors)) {
                for (const prop in this.errors) {
                    return this.errors[prop][0]
                    break
                }
            }
        },
        totalPerPresc(i) {
            return this.payment.patpresc[i].meds.reduce(this.prescPriceUnit(), 0)
        },
        prescBalance(i) {
            this.payment.patpresc[i].meds.reduce((ac, val) => ac + parseFloat(+val.amount), 0)
        },
        prescPriceUnit(ac, val) {
            switch (meds.unit) {
                case 'sat':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_sat))
                break;
                case 'gram':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_gram))
                break;
                case 'pill':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_pill))
                break;
                case 'tab':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_tab))
                break;
                case 'pack':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_pack))
                break;
                case 'ml':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_ml))
                break;
                case 'shot':
                    return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_shot))
                break;
                default:
                    return ac + 0
                break;
            }
        },
        medPrice(i,y) {
            const med = this.payment.patpresc[i].meds[y];
            switch (med.unit) {
                case 'sat':
                    return med.qty * parseFloat(+med.pharm.price_per_sat)
                break;
                case 'gram':
                    return med.qty * parseFloat(+med.pharm.price_per_gram)
                break;
                case 'pill':
                    return med.qty * parseFloat(+med.pharm.price_per_pill)
                break;
                case 'tab':
                    return med.qty * parseFloat(+med.pharm.price_per_tab)
                break;
                case 'pack':
                    return med.qty * parseFloat(+med.pharm.price_per_pack)
                break;
                case 'ml':
                    return med.qty * parseFloat(+med.pharm.price_per_ml)
                break;
                case 'shot':
                    return med.qty * parseFloat(+med.pharm.price_per_shot)
                break;
                default:
                    return 0
                break;
            }
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/patients')
        },
    },
    computed: {
        totalMeds() {
            if(this.payment.patpresc && this.payment.patpresc.length > 0) {
                let total = 0
                this.payment.patpresc.forEach(v => {
                    total += v.meds.reduce((ac, val) => {
                        switch (meds.unit) {
                            case 'sat':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_sat))
                            break;
                            case 'gram':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_gram))
                            break;
                            case 'pill':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_pill))
                            break;
                            case 'tab':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_tab))
                            break;
                            case 'pack':
                                return ac + (parseInt(val.qty) * parseFloat+(val.pharm.price_per_pack))
                            break;
                            case 'ml':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_ml))
                            break;
                            case 'shot':
                                return ac + (parseInt(val.qty) * parseFloat(+val.pharm.price_per_shot))
                            break;
                            default:
                                return ac + 0
                            break;
                        }
                    }, 0)
                });

                return total.toFixed(2)
            } else
                return null;
        },
        medsBalance() {
            if(this.payment.patpresc && this.payment.patpresc.length > 0) {
                let total = 0
                this.payment.patpresc.forEach(v => {
                    total += v.meds.reduce((ac, val) => {
                        ac + parseFloat(+val.amount)
                    }, 0)
                });

                return total.toFixed(2)
            } else
                return 0;
        },
        totalService() {
            if(this.payment.patservice && this.payment.patservice.length > 0) {
                return this.payment.patservice.reduce((ac, val) => {
                    return ac + parseFloat(+val.services.charge)
                }, 0).toFixed(2)
                // return _.reduce(this.payment.patservice, function(ac, val) {
                //     return ac + (val.services.charge)
                // }, 0)
            } else
                return null;
        },
        serviceBalance() {
            if(this.payment.patservice && this.payment.patservice.length > 0) {
                return (this.payment.patservice.reduce(function(ac, val) {
                    return ac + parseFloat(+val.paid)
                }, 0)).toFixed(2)
            } else
                return 0;
        },
        subTotal() {
            return ((!!this.totalMeds ? parseFloat(this.totalMeds) : 0) + (!!this.totalService ? parseFloat(this.totalService) : 0)).toFixed(2)
        },
        grandTotal() {
            return ((parseFloat(this.subTotal) * 0.075) + parseFloat(this.subTotal)).toFixed(2)
        },
        amountPaid() {
            return parseFloat(this.serviceBalance) + parseFloat(this.medsBalance)
        },
        totalBalance() {
            return parseFloat(+this.payment.paid) < parseFloat(this.grandTotal) ? Math.abs(parseFloat(this.grandTotal) - parseFloat(this.payment.paid)) : 0
            // return parseFloat(this.amountPaid) < parseFloat(this.grandTotal) ? Math.abs(parseFloat(this.grandTotal) - parseFloat(this.amountPaid)) : 0
        },
        serviceStat() {
            let status = 'Pending';
            let compcount = 0;
            if(this.payment.patservice) {
                for (let i = 0; i < this.payment.patservice.length; i++) {
                    if(this.payment.patservice[i].status == 'Incomplete') {
                        status = 'Incomplete'
                        break
                    }
                    if(this.payment.patservice[i].status == 'Complete') {
                        compcount++
                    }
                    if(compcount == this.payment.patservice.length) {
                        status = 'Complete'
                    }
                }
            }

            return status;
        },
        prescStat() {
            let status = 'Pending';
            let compcount = 0;

            if(this.payment.patpresc) {
                for (let i = 0; i < this.payment.patpresc.length; i++) {
                    if(this.payment.patpresc[i].status == 'Incomplete') {
                        status = 'Incomplete'
                        break
                    }
                    if(this.payment.patpresc[i].status == 'Complete') {
                        compcount++
                    }
                    if(compcount == this.payment.patservice.length) {
                        status = 'Complete'
                    }
                }
            }

            return status;
        }
    },
    validations: {
        amount: { required, minValue:minValue(5), maxValue:maxValue(10000000) },
        type: { required },
    }
}
</script>
