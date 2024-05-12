<template>
  <div class="block" :key="blockKey" v-if="Object.keys(widgets).length">
    <BuilderBlockItem
      v-for="(block, key) in value"
      :key="block.id + '-' + key"
      :item="block"
      :widgets="widgets"
      :isFirst="key === 0"
      :isLast="key == Object.keys(value)[Object.keys(value).length -1]"
      @remove-item="removeBlock(key)"
      @move-item-up="moveBlockUp(key)"
      @move-item-down="moveBlockDown(key)"
    />
    <div class="container">
      <BuilderBlockCreate
        :container="value"
        :widgets="widgets"
        :allowedWidgets="[]"
      />
    </div>
    <textarea :name="name" class="d-none">{{ toJson(value) }}</textarea>
  </div>
</template>

<script>
import BuilderBlockItem from './BuilderBlockItem'
import BuilderBlockCreate from './BuilderBlockCreate'
import Routing from '../../../../../../../../../friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js'

const axios = require('axios').default
const routes = require('../../../../../../../../../../public/js/fos_js_routes.json')
Routing.setRoutingData(routes)

export default {
  name: 'BuilderBlock',
  props: {
    id: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    initialValue: {
      type: Array,
      required: false,
    }
  },
  data() {
    return {
      value: this.initialValue,
      widgets: {},
      blockKey: 0
    }
  },
  methods: {
    toJson(value) {
      return JSON.stringify(value)
    },
    triggerBuilderBlockEvent() {
      document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
    },
    moveBlockUp(key) {
      let newValue = this.value.map((x) => x)

      newValue[key-1] = this.value[key]
      newValue[key] = this.value[key-1]

      this.value = newValue

      ++this.blockKey
    },
    moveBlockDown(key) {
      let newValue = this.value.map((x) => x)

      newValue[key+1] = this.value[key]
      newValue[key] = this.value[key+1]

      this.value = newValue

      ++this.blockKey
    },
    removeBlock(key) {
      let newValue = []

      this.value.forEach((v, k) => {
        if (k !== key) {
          newValue.push(v)
        }
      })

      this.value = newValue

      // ++this.blockKey
    },
  },
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
  },
  mounted() {
    this.triggerBuilderBlockEvent()
    const that = this

    axios.get(Routing.generate('admin_editor_builder_block_widgets'))
      .then((response) => {
        that.widgets = response.data
      })
  },
  created() {
    this.triggerBuilderBlockEvent()
  },
  updated() {
    this.triggerBuilderBlockEvent()
  }
}
</script>
