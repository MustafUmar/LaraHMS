<template>
    <div>
        <page-heading heading="Diagnosis"></page-heading>

        <div class="row justify-content-center">
            <div class="col-sm-11">

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
                            <button class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</button>
                            <button class="btn btn-light"><i class="fas fa-bell"></i> Call</button>
                        </div>
                        <div>
                            <router-link to="/hpl/diagnosis/patient" class="btn btn-light">
                                <i class="fas fa-stethoscope"></i> Diagnose <i class="fas fa-angle-right"></i>
                            </router-link>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <div class="row mx-0">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Patient</label>
                                        <p class="form-control">John Doe</p>
                                    </div>
                                </div>
                                <div class="col-sm-9 thin-light-border py-1">

                                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="true">Summary</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-diagnosis-tab" data-toggle="pill" href="#pills-diagnosis" role="tab" aria-controls="pills-diagnosis" aria-selected="false">Diagnosis</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-lab-tests-tab" data-toggle="pill" href="#pills-lab-tests" role="tab" aria-controls="pills-lab-tests" aria-selected="false">Lab Tests</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-history-tab" data-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="false">History</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                                            <hr class="hr"/>
                                            Requested to see a doctor
                                        </div>
                                        <div class="tab-pane fade" id="pills-diagnosis" role="tabpanel" aria-labelledby="pills-diagnosis-tab">
                                            <hr class="hr"/>
                                            <h5>Prior Diagnosis</h5>
                                            No prior diagnosis
                                            <div>
                                                <h6>Dr. Tillman's Diagnosis</h6>
                                                <div class="form-group">
                                                    <label>Symptoms Identified</label>
                                                    <div class="form-control">Head ache, Body weakness</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Diagnosis</label>
                                                    <div class="form-control">Malaria</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Medication Prescribed</label>
                                                    <div class="form-control">Chloriquine</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Additional Note</label>
                                                    <div class="form-control">None</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-lab-tests" role="tabpanel" aria-labelledby="pills-lab-tests-tab">
                                            <hr class="hr"/>
                                            <div>
                                                <h5>Test Name</h5>
                                                <div class="form-group">
                                                    <label>Conducted by</label>
                                                    <div class="form-control">Dr. Tillman Jr.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Test type</label>
                                                    <div class="form-control">Malaria</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Specimen</label>
                                                    <div class="form-control">Blood</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Result</label>
                                                    <div class="form-control">Positive</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">

                                        </div>
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
            loading: false,
            error: {
                stat: false,
                message: ''
            }
        }
    }
}
</script>
