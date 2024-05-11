<template>
  <div class="block" v-if="widget" :key="blockKey">
    <div class="block-header">
      <div class="float-right">
        <div class="block-header-item text-white bg-danger" v-on:click="removeMe(item)">
          <span class="fa fa-trash"></span>
        </div>
      </div>
      <div class="block-header-item block-label" data-toggle="tooltip" data-placement="top" :title="item.widget">
        {{ widget.label }}
      </div>
      <div class="block-header-item block-settings-toggler" v-on:click="toggleSettings" v-if="Object.keys(widget.settings).length">
        <span class="fa fa-cog"></span>
      </div>
    </div>

    <div class="block-settings" v-if="Object.keys(widget.settings).length" :class="{'d-none': !showSettings}">
      <div class="row">
        <div
          v-for="(params, setting) in widget.settings"
          class="col-12 form-group"
        >
          <label v-text="params.label"></label>

          <input
            v-if="params.type == 'input'"
            v-model="item.settings[setting]"
            v-bind="params.attr"
            class="form-control"
          />
          <textarea
            v-if="params.type == 'textarea'"
            v-model="item.settings[setting]"
            v-bind="params.attr"
            class="form-control"
          ></textarea>
        </div>
      </div>
    </div>

    <div v-if="item.children !== null && item.children.length > 0" v-for="(child, key) in item.children">
      <BuilderBlockItem
        :item="child"
        :widgets="widgets"
        @remove-item="removeBlock(key)"
      />
    </div>

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
    removeBlock(key) {
      let children = []

      this.item.children.forEach((v, k) => {
        if (k !== key) {
          children.push(v)
        }
      })

      this.item.children = children
      ++this.blockKey
    }
  },
  components: {
    BuilderBlockCreate,
  },
  mounted() {
    this.widget = this.widgets[this.item.widget]
  }
}
</script>
