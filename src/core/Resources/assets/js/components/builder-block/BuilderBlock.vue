<template>
  <Draggable
    v-if="Object.keys(widgets).length && value !== null"
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
    <BuilderBlockCreate
      :container="value"
      :widgets="widgets"
      :openedBlocks="openedBlocks"
      :allowedWidgets="[]"
      v-if="value.length > 0"
      position="top"
    />
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
          <button
            type="button"
            class="btn btn-sm"
            v-on:click="openCodeEditor"
          >
            <i class="fas fa-code"></i>
          </button>
        </div>
      </div>
    </div>
    <textarea :name="name" class="d-none">{{ toJson(value) }}</textarea>
    <dialog ref="dialog" class="modal-dialog modal-dialog-large">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Code editor</h5>
          <button
            type="button"
            class="close"
            v-on:click="closeCodeEditor"
          >
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <textarea
              class="form-control"
              rows="10"
              ref="codeEditor"
              v-model="nextValue"
            >
            </textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            v-on:click="closeCodeEditor"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            v-on:click="checkAndSaveNextValue"
          >
            Save
          </button>
        </div>
      </div>
    </dialog>
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
    openCodeEditor() {
      this.nextValue = this.toJson(this.value)
      this.$refs.dialog.showModal()
    },
    closeCodeEditor() {
      this.$refs.codeEditor.classList.toggle('is-invalid', false)
      this.$refs.dialog.close()
    },
    isNextValueItemValueValid(item) {
      const hasId = typeof item.id === 'string'
      const hasWidget = typeof item.widget === 'string' && this.widgets[item.widget]
      const hasSettings = typeof item.settings === 'object'
      const hasChildren = Array.isArray(item.children)

      if (!hasId || !hasWidget || !hasSettings || !hasChildren) {
        return false
      }

      for (let child of item.children) {
        if (!this.isNextValueItemValueValid(child)) {
          return false
        }
      }

      return true
    },
    updateNextValueRecursiveIds(data) {
      if (Array.isArray(data)) {
        data.forEach((value, key) => {
          data[key] = this.updateNextValueRecursiveIds(value)
        })
      } else {
        data.id = this.makeId()
        data.children = this.updateNextValueRecursiveIds(data.children)
      }

      return data
    },
    checkAndSaveNextValue() {
      this.$refs.codeEditor.classList.toggle('is-invalid', false)
      let hasError = false

      try {
        let data = JSON.parse(this.nextValue)

        if (!Array.isArray(data)) {
          hasError = true
        } else {
          for (let item of data) {
            if (!this.isNextValueItemValueValid(item)) {
              hasError = true
            }
          }
        }

        if (!hasError) {
          this.value = this.updateNextValueRecursiveIds(data)
          ++this.blockKey
        }

      } catch (e) {
        console.log(e)
        hasError = true
      }

      return this.$refs.codeEditor.classList.toggle('is-invalid', hasError)
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
    makeId() {
      let result = ''
      const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'
      const charactersLength = characters.length

      for (let i = 0; i < 7; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength))
      }

      return `block-${result}`
    },
  },
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
    Draggable,
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
