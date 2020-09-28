<template>

    <page-layout heading="Payments" :menu="true" :subloader="loading">

        <template #left-menu>
            <button class="btn btn-light" @click="goBack"><i class="fas fa-arrow-left"></i> Back</button>
            <button class="btn btn-light" @click="goBack"><i class="fas fa-sync"></i> Refresh</button>
        </template>

        <template #right-menu>
            <div class="d-flex align-items-center">
                <div class="two-flexed-left-shrink applied-filters-md thin-light-border">
                    <div class="filter-icon"><i class="fas fa-filter"></i></div>
                    <div class="filter-items">
                        <span class="badge badge-secondary">Status: {{filters.stat}}</span>
                        <span class="badge badge-secondary">Date: {{filters.date == 'on' || filters.date == 'from' ? 'custom date' : filters.date | mixedCase}}</span>
                    </div>
                    <!-- <div class="filter-remove-icon" @click="resetFilter">
                        <span><i class="fas fa-times"></i></span>
                    </div> -->
                </div>
                <div class="dropdown filter-menu no-propagate">
                    <span class="btn btn-light dropdown-toggle" id="filterDrop" data-toggle="dropdown">
                        <i class="fas fa-sliders-h"></i>
                    </span>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="filterDrop">
                        <div class="dropdown-item-text clearfix">
                            <span @click="resetFilter"><i class="fas fa-redo-alt"></i></span>
                            <span @click="hideFilter" class="float-right"><i class="fas fa-times fa-lg"></i></span>
                        </div>
                        <div class="trans-overlay-bottom-up-three-quart" v-show="loading">

                        </div>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Payment Status</h6>
                        <div class="dropdown-item-text">
                            <div>
                                <select class="form-control form-control-sm" v-model="filters.stat" @change="getPayment()">
                                    <option :selected="filters.stat == 'All'">All</option>
                                    <option :selected="filters.stat == 'Pending'">Pending</option>
                                    <option :selected="filters.stat == 'Incomplete'">Incomplete</option>
                                    <option :selected="filters.stat == 'Complete'">Complete</option>
                                </select>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Date</h6>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" id="filter-all-date" type="radio" v-model="filters.date" @change="getPayment()" value="all">
                                <label class="form-check-label" for="filter-all-date">
                                    All time
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="filter-today" type="radio" value="today" v-model="filters.date" @change="getPayment()">
                                <label class="form-check-label" for="filter-today">
                                    Today
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="filter-yest" type="radio" value="yest" v-model="filters.date" @change="getPayment()">
                                <label class="form-check-label" for="filter-yest">
                                    Yesterday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="filter-on-date" type="radio" v-model="filters.date" value="on">
                                <label class="form-check-label" for="filter-on-date">
                                   On
                                </label>
                            </div>
                            <div class="form-group" v-if="filters.date == 'on'">
                                <datetimepicker ref="filterDatePicker" @input="applyFilter('on', $event)" v-model="filters.on_date" :input-id="'filter-on-input'" :input-class="{'form-control':true, 'form-control-sm':true}" :format="{'DateTime':'DATE_MED'}" v-bind:auto="true" value-zone="local">
                                </datetimepicker>
                                <!-- <input type="email" class="form-control form-control-sm" v-model="filters.custom_1" placeholder="name@example.com"> -->
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="filter-from-date" type="radio" v-model="filters.date" value="from">
                                <label class="form-check-label" for="filter-from-date">
                                    From
                                </label>
                            </div>
                            <div class="form-inline" v-if="filters.date == 'from'">
                                <datetimepicker @input="applyFilter('from', $event)" v-model="filters.from_dt1" :input-id="'filter-from-input1'" :input-class="{'form-control':true, 'form-control-sm':true}" :format="{'DateTime':'DATE_MED'}" v-bind:auto="true" value-zone="local">
                                </datetimepicker>
                                <label> to </label>
                                <datetimepicker @input="applyFilter('from', $event)" v-model="filters.from_dt2" :input-id="'filter-from-input2'" :input-class="{'form-control':true, 'form-control-sm':true}" :format="{'DateTime':'DATE_MED'}" v-bind:auto="true" value-zone="local">
                                </datetimepicker>
                            </div>

                            <!-- <input type="text" class="form-control" @input="activeFilter('search', $event)" :value="filters.search.val"> -->

                        </div>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Sort</h6>
                        <div class="dropdown-item-text flexed">
                            <div class="col">
                                <label class="fs-4">Sort by</label>
                                <select class="form-control form-control-sm px-1" v-model="filters.sort" @change="getPayment()">
                                    <option value="date">Date</option>
                                    <option value="name">Name</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="fs-4">Order</label>
                                <select class="form-control form-control-sm px-1" v-model="filters.order" @change="getPayment()">
                                    <option value="0">Asc</option>
                                    <option value="1">Desc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- <div class="thin-light-border flexed mb-1">
            <div>Status Pending</div>
            <div>Sort by Date</div>
        </div> -->

        <div class="card">
            <div class="card-body">
                <div v-if="payments.length">
                    <table class="table table-sm">
                        <tr>
                            <th>Name</th><th>Status</th><th>Date</th><th></th>
                        </tr>
                        <tr v-for="pay in payments" :key="pay.id">
                            <td>{{pay.firstname}} {{pay.lastname}}</td>
                            <td>{{pay.status}}</td>
                            <td>{{pay.created_at}}</td>
                            <td><router-link :to="{name:'bill', params: {pay:pay.hashid}}" class="btn btn-sm btn-outline-info"><i class="fas fa-money-bill"></i> Bill</router-link></td>
                        </tr>
                    </table>
                </div>
                <div v-else>
                    <div class="alert alert-secondary text-center" role="alert">
                        <span v-if="filters.date == 'today'" class="fs-7">
                            No payments made <strong>today</strong> with <strong>{{filters.stat.toLowerCase()}}</strong> status.
                        </span>
                        <span v-else-if="filters.date == 'yest'" class="fs-7">
                            No payments made <strong>yesterday</strong> with <strong>{{filters.stat.toLowerCase()}}</strong> status.
                        </span>
                        <span v-else-if="filters.date == 'on'" class="fs-7">
                            No payments made on <strong>{{filters.on_date}}</strong> with <strong>{{filters.stat.toLowerCase()}}</strong> status.
                        </span>
                        <span v-else-if="filters.date == 'from'" class="fs-7">
                            No payments made between <strong>{{filters.from_dt1}}</strong> and <strong>{{filters.from_dt2}}</strong> with <strong>{{filters.stat.toLowerCase()}}</strong> status.
                        </span>
                        <span v-else class="fs-7">No payments.</span>
                    </div>
                </div>

            </div>
        </div>

    </page-layout>

</template>

<script>
import { Datetime } from 'vue-datetime';

export default {
    mounted() {
        this.getPayment()
        // this.$refs.filterDatePicker.$vnode.child.weekdays = ["M", "T", "W", "T", "F", "S", "S"];
    },
    data() {
        return {
            payments:[],
            filters: {
                stat: 'All',
                date: 'today',
                on_date: '',
                from_dt1: '',
                from_dt2: '',
                sort: 'date',
                order: 1
            },
            loading: true
        }
    },
    methods: {
        getPayment() {
            this.loading = true
            axios.post('api/payments', this.filters).then(response => {
                // console.log(response.data)
                this.payments = response.data
                this.loading = false
            }).catch(err => {
                console.log(err.response.data)
                this.loading = false
            })
        },
        applyFilter(v, e) {
            switch (v) {
                case 'on':
                    if(!_.isEmpty(this.filters.on_date)) {
                        if(this.filters.on_date !== "Invalid DateTime") {
                            console.log(this.filters.on_date)
                            this.filters.on_date = this.$root.luxonDate.fromISO(e).toFormat('yyyy-MM-dd')
                            this.getPayment()
                        }
                    }
                break;
                case 'from':
                    if(!_.isEmpty(this.filters.from_dt1) && !_.isEmpty(this.filters.from_dt2)) {
                        if(this.filters.from_dt1 !== "Invalid DateTime" && this.filters.from_dt2 !== "Invalid DateTime") {
                            console.log('from date')
                            this.filters.from_dt1 = this.$root.luxonDate.fromISO(this.filters.from_dt1).toFormat('yyyy-MM-dd')
                            this.filters.from_dt2 = this.$root.luxonDate.fromISO(this.filters.from_dt2).toFormat('yyyy-MM-dd')
                            this.getPayment()
                        }
                    }
                break;
            }
        },
        resetFilter() {
            this.$set(this.filters, 'stat', 'All')
            this.$set(this.filters, 'date', 'today')
            this.$set(this.filters, 'on_date', '')
            this.$set(this.filters, 'from_dt1', '')
            this.$set(this.filters, 'from_dt2', '')
            this.$set(this.filters, 'sort', 'date')
            this.$set(this.filters, 'order', '1')
        },
        hideFilter() {
            $('#filterDrop').dropdown('hide')
        },
        goBack() {
            window.history.length > 1 ? this.$router.go(-1) : this.$router.replace('/hpl/payment-summary')
        },
    },
    components: {
        'datetimepicker': Datetime
    }
}
</script>
