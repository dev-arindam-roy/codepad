<template>
    <div id="appIndex">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">
                <img src="/public/assets/images/logo.png" style="width:32px;">
                <span><strong>CodePad++</strong></span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <router-link class="nav-link btn btn-black" :to="{name: 'create.tab'}"><i class="fas fa-plus-square"></i> Create Tab</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <router-view :key="$route.path"></router-view>
    </div>
</template>

<script>
export default {
    name : 'app-index',
    props:{
        
    },
    components: {
        
    },
    data() {
        return {
            
        }
    },
    mounted() {
        var _this = this;
    }
}
</script>

<style scoped>

</style>
