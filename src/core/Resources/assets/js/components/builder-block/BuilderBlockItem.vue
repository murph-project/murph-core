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
        v-if="!isFirst"
        v-on:click="moveMeUp"
        class="block-header-item block-settings-inverse"
      >
          <span class="fas fa-arrow-up"></span>
        </div>
      <div
        v-if="!isLast"
        v-on:click="moveMeDown"
        class="block-header-item block-settings-inverse"
      >
          <span class="fas fa-arrow-down"></span>
        </div>
    </div>

    <div class="block-settings" v-if="Object.keys(widget.settings).length" :class="{'d-none': !showSettings}">
      <div class="row">
        <BuilderBlockSetting
          v-for="(params, setting) in widget.settings"
          :key="item.id + '-' + setting"
          :class="widget.class"
          :item="item"
          :params="params"
          :setting="setting"
        />
      </div>
    </div>

    <BuilderBlockItem
      v-if="item.children !== null && item.children.length > 0"
      v-for="(child, key) in item.children"
      :key="child.id"
      :item="child"
      :widgets="widgets"
      :isFirst="key === 0"
      :isLast="key == Object.keys(item.children)[Object.keys(item.children).length -1]"
      @remove-item="removeBlock(key)"
      @move-item-up="moveBlockUp(key)"
      @move-item-down="moveBlockDown(key)"
    />

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
    isFirst: {
      type: Boolean,
      required: true
    },
    isLast: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      widget: null,
      showSettings: false,
      blockKey: 0
    }
  },
  methods: {
    toggleSettings() {
      this.showSettings = !this.showSettings
    },
    removeMe() {
      this.$emit('remove-item')
    },
    moveMeUp() {
      this.$emit('move-item-up')
    },
    moveMeDown() {
      this.$emit('move-item-down')
    },
    moveBlockUp(key) {
      let newValue = this.item.children.map((x) => x)

      newValue[key-1] = this.item.children[key]
      newValue[key] = this.item.children[key-1]

      this.item.children = newValue

      ++this.blockKey
    },
    moveBlockDown(key) {
      let newValue = this.item.children.map((x) => x)

      newValue[key+1] = this.item.children[key]
      newValue[key] = this.item.children[key+1]

      this.item.children = newValue

      ++this.blockKey
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
  },
  components: {
    BuilderBlockCreate,
    BuilderBlockSetting,
  },
  mounted() {
    this.widget = this.widgets[this.item.widget]
  },
  updated() {
    document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
  }
}
</script>
