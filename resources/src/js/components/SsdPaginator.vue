<template>
  <div>
    <slot :previous="previous" :next="next" :is-first="isFirst" :is-last="isLast" :of-label="ofLabel" :paginator="paginator" :options="options">
      <form class="inline-flex items-center rounded overflow-hidden border border-gray-300 bg-white text-sm">
        <a :href="previous" class="px-3 py-2 bg-gray-200 cursor-pointer shrink" v-if="!isFirst">
          <AngleLeft class="w-5 h-5" />
        </a>
        <span role="button" aria-disabled="true" class="px-3 py-2 bg-gray-200 opacity-50 cursor-default shrink" v-if="isFirst">
          <AngleLeft class="w-5 h-5" />
        </span>
        <span class="px-3 shrink">Page</span>
        <select @change="change" v-model="paginator" class="bg-gray-200 border-none py-2 text-sm">
          <option v-for="(option, page) in options" :value="option" v-text="page"></option>
        </select>
        <span class="px-3 shrink" v-text="ofLabel"></span>
        <a :href="next" class="px-3 py-2 bg-gray-200 cursor-pointer shrink" v-if="!isLast">
          <AngleRight class="w-5 h-5" />
        </a>
        <span role="button" aria-disabled="true" class="px-3 py-2 bg-gray-200 opacity-50 cursor-default shrink" v-if="isLast">
          <AngleRight class="w-5 h-5" />
        </span>
      </form>
    </slot>
  </div>
</template>
<script>
import AngleLeft from './AngleLeft'
import AngleRight from './AngleRight'
export default {
  name: 'SsdPaginator',
  components: { AngleLeft, AngleRight },
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
  data () {
    return {
      paginator: this.current
    }
  },
  computed: {
    isFirst () {
      return this.paginator === this.first
    },
    isLast () {
      return this.paginator === this.last
    },
    ofLabel () {
      return 'of ' + this.numberOfPages
    }
  },
  methods: {
    change () {
      window.location.href = this.paginator
    }
  }
}
</script>