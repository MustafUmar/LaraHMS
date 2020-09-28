<template>
    <div>
        <page-heading heading="Staffs"></page-heading>
        <div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <router-link to="/hpl/staff/new" class="btn btn-light rounded-btn text-primary"><i class="fas fa-plus fa-lg"></i></router-link>
                            <button v-show="selected !== null" @click="view" class="btn btn-light rounded-btn"><i class="fas fa-eye fa-lg"></i></button>
                            <button v-show="selected !== null" @click="edit" class="btn btn-light rounded-btn"><i class="fas fa-pen fa-lg"></i></button>
                            <button v-show="selected !== null" @click="selected = null" class="btn btn-light rounded-btn"><i class="fas fa-times-circle fa-lg"></i></button>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="three-flexed-center-grow applied-filters thin-light-border" v-if="isFilter">
                                <div class="filter-icon"><i class="fas fa-filter"></i></div>
                                <div class="filter-items">
                                    <span class="badge badge-secondary" v-show="filters.search.active"><i class="fas fa-search"></i> {{filters.search.val}}</span>
                                    <span class="badge badge-secondary" v-show="filters.type.active"><i class="fas fa-stethoscope"></i> {{filters.type.val}}</span>
                                </div>
                                <div class="filter-remove-icon" @click="resetFilter">
                                    <span><i class="fas fa-times"></i></span>
                                </div>
                            </div>
                            <div class="dropdown filter-menu">
                                <span class="btn btn-light dropdown-toggle" id="filterDrop" data-toggle="dropdown">
                                    <i class="fas fa-sliders-h"></i>
                                </span>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="filterDrop">
                                    <div class="dropdown-item-text clearfix">
                                        <span><i class="fas fa-redo-alt" @click="resetFilter"></i></span>
                                        <span class="float-right" @click="hideFilter"><i class="fas fa-times fa-lg"></i></span>
                                    </div>
                                    <h6 class="dropdown-header">Search</h6>
                                    <div class="dropdown-item-text">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                            <input type="text" class="form-control" @input="activeFilter('search', $event)" :value="filters.search.val">
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ">
                        <div class="trans-loader" v-show="loading">
                            <div class="d-flex justify-content-center align-items-center loader-wrapper">
                                <div class="loader-content fa-lg">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-lightgray">
                            <div v-if="staffs.length">
                                <table class="table doctor-list-table">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Job Title</th>
                                        <th>Active</th>
                                        <th>Last Seen</th>
                                        <td></td>
                                    </tr>
                                    <tr v-for="(staff,index) in staffs" :key="'doc'+staff.personid" @click="setSelected(index)" :class="{'highlight-table-row':selected === index}">
                                        <td>{{staff.personid}}</td>
                                        <td><router-link :to="{name:'staff', params:{stf:staff.hashid}}" class="unlink">{{staff.fullname}}</router-link></td>
                                        <td>{{staff.job_title ? staff.job_title : 'N/A'}}</td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="dropdown list-short-menu">
                                                <button class="btn btn-secondary rounded-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="-25,0">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <router-link :to="{name:'staff', params:{stf:staff.hashid}}" class="btn btn-outline-dark dropdown-item"><i class="fas fa-eye"> View</i></router-link>
                                                    <router-link :to="{name:'staff_edit', params:{stf:staff}}" class="btn btn-outline-dark dropdown-item"><i class="fas fa-pen"> Edit</i></router-link>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div v-else>
                                <div class="alert alert-secondary text-center" role="alert">
                                    <p class="fs-8">No staffs !!</p>
                                    <div><button class="btn btn-light" @click="$router.push('/hpl/staff/new')">Add staff</button></div>
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
            staffs: [],
            selected: null,
            filters: {
                search: { active: false, val:'' },
                type: { active: false, val:'All' },
                account: { active: false, val:'active'}
            },
            loading: true
        }
    },
    created() {
        this.getStaffs()
        this.debounceStaff = _.debounce(this.getStaffs, 1500)
    },
    methods: {
        getStaffs() {
            axios.get('api/staffs', {params: this.filterParam}).then(response => {
                this.staffs = response.data
                this.loading = false
            }).catch(error => {
                console.log(error)
                this.loading = false
            })
        },
        setSelected(i) {
            // this.$set(this, 'selected', i)
            this.selected = i
        },
        view() {
            this.$router.push({name:'staff', params:{stf: this.staffs[this.selected].hashid}})
        },
        edit() {
            this.$router.push({name:'staff_edit', params:{stf: this.staffs[this.selected]}})
        },
        activeFilter(filter, e) {

            switch (filter) {
                case 'search':
                    if(!!e.target.value) {
                        this.$set(this.filters.search, 'active', true)
                        this.$set(this.filters.search, 'val', e.target.value)
                        // this.filters.search.active = true
                        // this.filters.search.val = e.target.value
                    } else {
                        this.filters.search.active = false
                        this.filters.search.val = ''
                    }
                break;
                case 'type':
                    if(e.target.value === 'All') {
                        this.filters.type.active = false
                        this.filters.type.val = 'All'
                    } else if(e.target.value === 'General' || e.target.value === 'Specialist') {
                        this.filters.type.active = true
                        this.filters.type.val = e.target.value
                    }
                break;
                default:
                    this.resetFilter()
                break;
            }
        },
        resetFilter() {
            this.$set(this.filters.search, 'active', false)
            this.$set(this.filters.search, 'val', '')
            this.filters.type.active = false
            this.filters.type.val = 'All'
        },
        hideFilter() {
            $('#filterDrop').dropdown('hide')
        }
    },
    computed: {
        isFilter() {
            return _.some(this.filters, 'active')
        },
        filterParam() {
            let param = {};
            _.forOwn(this.filters, function(v,k) {
                if(v.active)
                    param[k] = v.val
            });
            return param;
        }
    },
    watch: {
        filters: {
            handler: function() {
                this.loading = true
                this.debounceStaff()
            },
            deep: true
        }
    }
}
</script>
