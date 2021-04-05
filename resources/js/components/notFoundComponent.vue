<template>
    <div id="notFound" class="mt-3 mb-5">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="notfound-box text-center text-danger">
                        <span><i class="fas fa-3x fa-exclamation-triangle"></i></span>
                        <h1><strong>Opps</strong></h1>
                        <h1><strong>404! Sorry, Page Not Found</strong></h1>
                    </div>
                    <div class="download-action mt-3 text-center">
                        <router-link class="btn btn-info primary-button" :to="{name: 'root.index'}">Home, Start again?</router-link>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'page-not-found',
    components: {
        
    },
    data() {
        return {
            
        }
    },
    watch: {
        
    },
    computed: {
        
    },
    validations: {
        
    },
    methods: {
        
    },
    mounted() {
        var _this = this;
        _this.$root.isPageLoadingActive = false;
        _this.$swal.close();
    }
}
</script>

<style scoped>

</style>
