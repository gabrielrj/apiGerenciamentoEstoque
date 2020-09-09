<template>
    <Loading :active="loading" :is-full-page="isFullPage"></Loading>
</template>

<script>
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "VLoading",
    components: {
        Loading
    },
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        isFullPage: {
            type: Boolean,
            default: false,
        }
    }
}
</script>

<style scoped>

</style>
