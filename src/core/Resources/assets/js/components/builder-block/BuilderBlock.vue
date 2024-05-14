<template>
  <Draggable
    v-if="Object.keys(widgets).length"
    v-model="value"
    :key="blockKey"
    :animation="200"
    group="children"
    ghost-class="ghost"
    @start="dragStart"
    @end="dragEnd"
    handle=".dragger"
    :class="{'block-show-dropzone': showDragDrop}"
    class="block"
  >
    <BuilderBlockItem
      v-for="(block, key) in value"
      :key="block.id + '-' + key"
      :item="block"
      :widgets="widgets"
      :openedBlocks="openedBlocks"
      :depth="1"
      @remove-item="removeBlock(key)"
      @drag-start="dragStart"
      @drag-end="dragEnd"
    />
    <div class="container">
      <BuilderBlockCreate
        :container="value"
        :widgets="widgets"
        :openedBlocks="openedBlocks"
        :allowedWidgets="[]"
      />
    </div>
    <textarea :name="name" class="d-none">{{ toJson(value) }}</textarea>
  </Draggable>
</template>

<script>
import BuilderBlockItem from './BuilderBlockItem'
import BuilderBlockCreate from './BuilderBlockCreate'
import Routing from '../../../../../../../../../friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js'
import Draggable from 'vuedraggable'

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
      openedBlocks: {},
      blockKey: 0,
      showDragDrop: false,
    }
  },
  methods: {
    toJson(value) {
      return JSON.stringify(value)
    },
    triggerBuilderBlockEvent() {
      document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
    },
    removeBlock(key) {
      let newValue = []

      this.value.forEach((v, k) => {
        if (k !== key) {
          newValue.push(v)
        }
      })

      this.value = newValue

      ++this.blockKey
    },
    dragStart() {
      this.showDragDrop = true
    },
    dragEnd() {
      this.showDragDrop = false

      ++this.blockKey
    },
  },
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
    Draggable,
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
