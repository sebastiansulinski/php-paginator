<template>
    <form class="ssd-paginator">
        <a :href="previous" class="paginator-button" v-if="!isFirst"><i class="fa fa-angle-left"></i></a>
        <span class="paginator-button disabled" v-if="isFirst"><i class="fa fa-angle-left"></i></span>
        <span class="paginator-label">Page</span>
        <select @change="change()" v-model="paginator">
            <option v-for="(option, page) in options" :value="option" v-text="page"></option>
        </select>
        <span class="paginator-label" v-text="ofLabel"></span>
        <a :href="next" class="paginator-button" v-if="!isLast"><i class="fa fa-angle-right"></i></a>
        <span class="paginator-button disabled" v-if="isLast"><i class="fa fa-angle-right"></i></span>
    </form>
</template>
<script>
    export default {
        props: {
            options: {
                type: [Object, Array],
                required: false,
                default: []
            },
            current: {
                type: String,
                required: true
            },
            previous: {
                type: String,
                required: true
            },
            next: {
                type: String,
                required: true
            },
            first: {
                type: String,
                required: true
            },
            last: {
                type: String,
                required: true
            },
            numberOfPages: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                paginator: this.current
            }
        },
        computed: {
            isFirst() {
                return this.paginator === this.first;
            },
            isLast() {
                return this.paginator === this.last;
            },
            ofLabel() {
                return 'of ' + this.numberOfPages;
            }
        },
        methods: {
            change() {
                window.location.href = this.paginator;
            }
        }
    };
</script>