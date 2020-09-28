<template>
    <div class="page-heading">
        <h1>{{heading}}</h1>
    </div>
</template>
<script>
export default {
    props: {
        heading: { type: String, required:true }
    }
}
</script>
