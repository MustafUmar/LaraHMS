<template>
    <div>
        <page-heading heading="Patients"></page-heading>

        <div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-sm-11">

                    <div class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                        <div>
                            <router-link to="/hpl/file/new" class="btn btn-light rounded-btn text-primary"><i class="fas fa-plus fa-lg"></i></router-link>
                            <button class="btn btn-light">Merge</button>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="term" @keyup.enter="search" aria-label="Patient Search">
                                <div class="input-group-append">
                                    <button class="btn" type="button" @click="search"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="trans-loader" v-show="loading">
                                    <div class="d-flex justify-content-center align-items-center loader-wrapper">
                                        <div class="loader-content fa-lg">
                                            <i class="fas fa-spinner fa-pulse"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-pat-search-tab" data-toggle="tab" href="#nav-pat-search" role="tab" aria-controls="nav-pat-search" aria-selected="true">Search</a>
                                            <a class="nav-item nav-link" id="nav-pat-waiting-tab" data-toggle="tab" href="#nav-pat-waiting" role="tab" aria-controls="nav-pat-waiting" aria-selected="false">Awaiting</a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-pat-search" role="tabpanel" aria-labelledby="nav-pat-search-tab">
                                            <div v-if="patients.length === 0 && files.length === 0">
                                                <h6 class="text-muted text-center">Search patient</h6>
                                                <p v-if="searched" class="fs-14 text-danger px-2"><em>No result ...</em></p>
                                            </div>
                                            <div v-else>
                                                <div v-show="files.length">
                                                    <h6>Files</h6>
                                                    <div class="list-group">
                                                        <router-link :to="{name:'patfile', params:{fid:file.hashid}}" v-for="file in files" :key="file.fileno" class="list-group-item list-group-item-action">
                                                            <span class="fs-bold">{{file.fileno}}</span>
                                                            <p class="dim-txt fs-3 m-0">
                                                                <span class="ml-1">Type: {{file.type}}</span>
                                                            </p>
                                                        </router-link>
                                                    </div>
                                                </div>
                                                <div v-show="patients.length">
                                                    <h6>Patients</h6>
                                                    <div class="list-group">
                                                        <router-link :to="{name: 'patient', params:{pid:pat.hashid}}" v-for="pat in patients" :key="pat.patno" class="list-group-item list-group-item-action">
                                                            <p class="fs-4 m-0">#<span >{{pat.patno}}</span></p>
                                                            <span class="fs-bold">{{pat.fullname}}</span>
                                                            <p class="dim-txt fs-3 m-0">
                                                                <span class="ml-1">Phone: {{pat.phone}}</span> |
                                                                <span class="mr-1">Email: {{pat.email}}</span>
                                                            </p>
                                                        </router-link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-pat-waiting" role="tabpanel" aria-labelledby="nav-pat-waiting-tab">
                                            <h6>Awaiting</h6>
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="display-6">
                                                        PT0767983
                                                    </div>
                                                    <div>John Doe</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Recently viewed
                                </div>
                                <div class="card-body">
                                    <nav>
                                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Patient</a>
                                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">File</a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">...</div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
                                    </div>
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
            patients: [],
            files: [],
            term: '',
            loading: false,
            searched: false
        }
    },
    methods: {
        search() {
            this.searched = false
            this.loading = true
            if(!!this.term && this.term.length > 1 && this.term.length < 15) {
                axios.get('api/patient/search', {params: {'search': this.term}} ).then(response => {
                    this.patients = response.data.patients
                    this.files = response.data.files
                    this.searched = true
                    this.loading = false
                }).catch(error => {
                    console.log(error)
                })
            }
        }
    }
}
</script>
