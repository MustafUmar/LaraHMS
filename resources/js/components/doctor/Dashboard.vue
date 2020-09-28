<template>
    <div class="pd-3">

        <div class="row mb-3">

            <div class="col">
                <div class="row">
                    <div class="col-8">
                        <p><em>No Awaiting Patient</em></p>
                    </div>
                    <div class="col-4 text-center">
                        <div>
                            Online status
                            <hr class="hr"/>
                            <router-link to="/hpl/diagnosis" class="btn btn-info">
                                <em>Start Diagnosis</em> <i class="fas fa-angle-double-right"></i>
                            </router-link>
                        </div>
                    </div>
                    <!-- <div class="w-100"></div>
                    <div class="col">
                        <div class="text-right"></div>
                    </div> -->
                </div>
            </div>
            <div class="col">
                Clock
            </div>

        </div>

        <div class="row">

            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <strong>Awaiting Patients</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Cras justo odio
                                <span class="badge badge-primary badge-pill">1 min ago</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Dapibus ac facilisis in
                                <span class="badge badge-primary badge-pill">5 mins ago</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Morbi leo risus
                                <span class="badge badge-primary badge-pill">10 mins ago</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <strong>Appointments</strong>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-fill" id="appoint-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="today-appoint-tab" data-toggle="tab" href="#today-appoint" role="tab" aria-controls="today-appoint" aria-selected="true">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tomorrow-appoint-tab" data-toggle="tab" href="#tomorrow-appoint" role="tab" aria-controls="tomorrow-appoint" aria-selected="false">Tomorrow</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="otherday-appoint-tab" data-toggle="tab" href="#otherday-appoint" role="tab" aria-controls="otherday-appoint" aria-selected="false">Other days</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="today-appoint" role="tabpanel" aria-labelledby="today-appoint-tab">
                                Today
                            </div>
                            <div class="tab-pane fade" id="tomorrow-appoint" role="tabpanel" aria-labelledby="tomorrow-appoint-tab">
                                Tomorrow
                            </div>
                            <div class="tab-pane fade" id="otherday-appoint" role="tabpanel" aria-labelledby="otherday-appoint-tab">
                                Other days
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
