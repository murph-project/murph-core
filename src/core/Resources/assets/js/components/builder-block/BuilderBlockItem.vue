<template>
  <div>
    <div
      class="block"
      :class="'block-depth-' + depth"
      v-if="widget"
      :key="blockKey"
    >
      <div class="block-header d-flex justify-content-between">
        <div>
          <div
            class="block-header-item block-label"
            :title="item.widget"
          >
            <span
              class="block-icon"
              v-if="widget.icon"
              v-html="widget.icon"
            >
            </span>

            {{ widget.label }}
          </div>

          <button
            type="button"
            class="block-header-item btn btn-sm btn-outline-secondary"
            v-on:click="toggleSettings"
            v-if="Object.keys(widget.settings).length"
          >
            <span class="fa fa-cog"></span>
          </button>

          <span class="fa fa-arrows-alt dragger"></span>
        </div>

        <div
          v-if="widget.preview && typeof item.settings[widget.preview] == 'string'"
          class="block-preview"
        >
          {{ truncate(item.settings[widget.preview]) }}
        </div>

        <div>
          <span class="block-id">
            {{ item.id }}
          </span>
          <button
            type="button"
            class="block-header-item btn btn-sm text-white bg-danger"
            v-on:click="removeMe(item)"
          >
            <span class="fa fa-trash"></span>
          </button>
        </div>
      </div>

      <div class="block-settings" v-if="Object.keys(widget.settings).length" :class="{'d-none': !showSettings}">
        <div class="row">
          <BuilderBlockSetting
            class="mb-0"
            v-for="(params, setting) in widget.settings"
            :key="item.id + '-' + setting"
            :class="widget.class"
            :item="item"
            :params="params"
            :setting="setting"
          />
        </div>
      </div>

      <div v-if="widget.isContainer" class="container">
        <BuilderBlockCreate
          :container="item.children"
          :widgets="widgets"
          :openedBlocks="openedBlocks"
          :allowedWidgets="widget.widgets"
          v-if="item.children.length > 0"
          position="top"
        />
      </div>

      <Draggable
        v-if="widget.isContainer"
        v-model="item.children"
        ghost-class="ghost"
        group="children"
        @start="dragStart"
        @end="dragEnd"
        :animation="200"
        handle=".dragger"
        class="block-dropzone"
      >
        <template v-if="item.children !== null && item.children.length > 0">
          <BuilderBlockItem
            v-for="(child, key) in item.children"
            :key="child.id"
            :item="child"
            :widgets="widgets"
            :openedBlocks="openedBlocks"
            :depth="depth + 1"
            @remove-item="removeBlock(key)"
            @drag-start="dragStart"
            @drag-end="dragEnd"
          >
            <template #action v-if="(key+1) !== item.children.length">
              <BuilderBlockCreate
                :container="item.children"
                :widgets="widgets"
                :position="key"
                :openedBlocks="openedBlocks"
                :allowedWidgets="widget.widgets"
              />
            </template>
          </BuilderBlockItem>
        </template>
      </Draggable>

      <div v-if="widget.isContainer" class="container">
        <div class="d-flex justify-content-between">
          <BuilderBlockCreate
            :container="item.children"
            :widgets="widgets"
            :openedBlocks="openedBlocks"
            :allowedWidgets="widget.widgets"
            position="bottom"
          />
          <BuilderBlockCodeEditor
            ref="dialog"
            :value="item.children"
            :widgets="widgets"
            @update="codeUpdate"
          />
        </div>
      </div>
    </div>
    <slot name="action"></slot>
  </div>
</template>

<script>
import BuilderBlockCreate from './BuilderBlockCreate'
import BuilderBlockCodeEditor from './BuilderBlockCodeEditor'
import BuilderBlockSetting from './BuilderBlockSetting'
import Draggable from 'vuedraggable'

export default {
  name: 'BuilderBlockItem',
  components: {
    BuilderBlockCreate,
    BuilderBlockSetting,
    BuilderBlockCodeEditor,
    Draggable,
  },
  props: {
    widgets: {
      type: Object,
      required: true
    },
    item: {
      type: Object,
      required: true
    },
    openedBlocks: {
      type: Object,
      required: true
    },
    depth: {
      type: Number,
      required: true
    },
  },
  data() {
    return {
      widget: null,
      showSettings: this.openedBlocks[this.item.id] === true,
      blockKey: 0,
    }
  },
  methods: {
    toggleSettings() {
      this.openedBlocks[this.item.id] = !this.openedBlocks[this.item.id]
      this.showSettings = !this.showSettings
    },
    truncate(value) {
      return value.replace(/(<([^>]+)>)/ig, '').trim()
    },
    removeMe() {
      this.$emit('remove-item')
    },
    codeUpdate(nextValue) {
      this.item.children = nextValue
    },
    removeBlock(key) {
      let children = []

      this.item.children.forEach((v, k) => {
        if (k !== key) {
          children.push(v)
        }
      })

      this.item.children = children
      ++this.blockKey
    },
    dragStart() {
      this.$emit('drag-start')
    },
    dragEnd() {
      this.$emit('drag-end')
      ++this.blockKey
    },
  },
  mounted() {
    this.widget = this.widgets[this.item.widget]
  },
  updated() {
    document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
  }
}
</script>
