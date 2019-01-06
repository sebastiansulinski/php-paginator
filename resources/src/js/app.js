import Vue from 'vue';

Vue.component('ssd-paginator', require('./components/Paginator/Select.vue').default);

new Vue({
    el: '#app',
    mounted() {
        $(function() {
            $('.ssd-paginator select').on('change', function() {
                window.location.href = $(this).val();
            });
        });
    }
});