<template>
    <div>
        <page-heading v-if="heading" :heading="heading"></page-heading>

        <div class="row justify-content-center">

            <div :class="['col-md-'+smd, 'col-sm-'+ssm]">

                <div v-if="menu" class="thin-light-border two-flexed-spacebtw mb-2 p-1">
                    <div>
                        <slot name="left-menu"></slot>
                    </div>
                    <div>
                        <slot name="right-menu"></slot>
                    </div>
                </div>

                <div>
                    <div class="trans-loader" v-if="subloader">
                        <div class="d-flex justify-content-center align-items-center loader-wrapper">
                            <div class="loader-content fa-lg">
                                <span><i class="fas fa-spinner fa-pulse"></i></span>
                            </div>
                        </div>
                    </div>

                    <slot/>

                </div>



            </div>

        </div>

    </div>
</template>
<script>
export default {
    props: {
        heading: {
            type: String,
            default: null,
        },
        smd: {
            type: Number,
            default: 10
        },
        ssm: {
            type: Number,
            default: 11
        },
        loader: {
            type: Boolean,
            default: false
        },
        subloader: {
            type: Boolean,
            default: false
        },
        menu: {
            type: Boolean,
            default: false
        }
    }
}
</script>
