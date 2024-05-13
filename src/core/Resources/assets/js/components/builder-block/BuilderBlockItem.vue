<template>
  <div class="block" v-if="widget" :key="blockKey">
    <div class="block-header">
      <div class="float-right">
        <span class="block-id">
          {{ item.id }}
        </span>
        <div class="block-header-item text-white bg-danger" v-on:click="removeMe(item)">
          <span class="fa fa-trash"></span>
        </div>
      </div>
      <div
        class="block-header-item block-label"
        :title="item.widget"
      >
        {{ widget.label }}
      </div>
      <div
        class="block-header-item block-settings-inverse"
        v-on:click="toggleSettings"
        v-if="Object.keys(widget.settings).length"
      >
        <span class="fa fa-cog"></span>
      </div>
      <div
        class="block-header-item block-settings-inverse dragger"
      >
          <span class="fa fa-arrows-alt"></span>
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
      <BuilderBlockItem
        v-if="item.children !== null && item.children.length > 0"
        v-for="(child, key) in item.children"
        :key="child.id"
        :item="child"
        :widgets="widgets"
        @remove-item="removeBlock(key)"
        @drag-start="dragStart"
        @drag-end="dragEnd"
      />
    </Draggable>

    <div v-if="widget.isContainer" class="container">
      <BuilderBlockCreate
        :container="item.children"
        :widgets="widgets"
        :allowedWidgets="widget.widgets"
      />
    </div>
  </div>
</template>

<script>
import BuilderBlockCreate from './BuilderBlockCreate'
import BuilderBlockSetting from './BuilderBlockSetting'
import Draggable from 'vuedraggable'

export default {
  name: 'BuilderBlockItem',
  props: {
    widgets: {
      type: Object,
      required: true
    },
    item: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      widget: null,
      showSettings: false,
      blockKey: 0,
    }
  },
  methods: {
    toggleSettings() {
      this.showSettings = !this.showSettings
    },
    removeMe() {
      this.$emit('remove-item')
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
  components: {
    BuilderBlockCreate,
    BuilderBlockSetting,
    Draggable,
  },
  mounted() {
    this.widget = this.widgets[this.item.widget]
  },
  updated() {
    document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
  }
}
</script>
