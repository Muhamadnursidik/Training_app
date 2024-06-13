<template>
    <div class="page-header row no-gutters py-4 mb-3 border-bottom">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">{{ subtitle }}</span>
            <h3 class="page-title">{{ title }}</h3>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'subtitle'],
        mounted() {
            // console.log('Component mounted.')
        }
    }
</script>
