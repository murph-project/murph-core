<template>
  <div
    class="block block-root"
    v-if="Object.keys(widgets).length && value !== null"
  >
    <div class="container">
      <BuilderBlockCreate
        :container="value"
        :widgets="widgets"
        :openedBlocks="openedBlocks"
        :allowedWidgets="[]"
        v-if="value.length > 0"
        position="top"
      />
    </div>
    <Draggable
      v-model="value"
      :key="blockKey"
      :animation="200"
      group="children"
      ghost-class="ghost"
      @start="dragStart"
      @end="dragEnd"
      handle=".dragger"
      :class="{'block-show-dropzone': showDragDrop}"
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
        <div class="d-flex justify-content-between">
          <BuilderBlockCreate
            :container="value"
            :widgets="widgets"
            :openedBlocks="openedBlocks"
            :allowedWidgets="[]"
            position="bottom"
          />
          <div>
            <BuilderBlockCodeEditor
              ref="dialog"
              :value="value"
              :widgets="widgets"
              @update="codeUpdate"
            />
          </div>
        </div>
      </div>
      <textarea :name="name" class="d-none">{{ toJson(value) }}</textarea>
    </Draggable>
  </div>
</template>

<script>
import BuilderBlockItem from './BuilderBlockItem'
import BuilderBlockCodeEditor from './BuilderBlockCodeEditor'
import BuilderBlockCreate from './BuilderBlockCreate'
import Routing from '../../../../../../../../../friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js'
import Draggable from 'vuedraggable'

const axios = require('axios').default
const routes = require('../../../../../../../../../../public/js/fos_js_routes.json')
Routing.setRoutingData(routes)

export default {
  name: 'BuilderBlock',
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
    Draggable,
    BuilderBlockCodeEditor,
  },
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
      value: null,
      nextValue: null,
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
    codeUpdate(nextValue) {
      this.value = nextValue
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
    fixSettings(data) {
      if (Array.isArray(data)) {
        data.forEach((value, key) => {
          data[key] = this.fixSettings(value)
        })
      } else {
        const widget = this.widgets[data.widget]

        if (!widget) {
          return data
        }

        const nextSettings = {}

        for (let setting in widget.settings) {
          if (data.settings.hasOwnProperty(setting)) {
            nextSettings[setting] = data.settings[setting]
          } else {
            nextSettings[setting] = widget.settings[setting].default
          }
        }

        data.settings = nextSettings
        data.children = this.fixSettings(data.children)
      }

      return data
    },
  },
  mounted() {
    const that = this

    axios.get(Routing.generate('admin_editor_builder_block_widgets'))
      .then((response) => {
        that.widgets = response.data
        that.value = that.fixSettings(that.initialValue)
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
