<template>
  <div class="builder-add">
    <span class="btn btn-sm btn-secondary" v-on:click="togglePicker">
      <span class="fa fa-plus"></span>
    </span>

    <div class="picker mt-2 list-group" :class="{'d-none': !showPicker}">
      <div v-for="(widget, name) in widgets" v-if="allowedWidgets.length == 0 || allowedWidgets.includes(name)" class="list-group-item">
        <div class="float-right">
          <span class="btn btn-sm btn-primary" v-on:click="add(name, widget)">
            <span class="fa fa-plus"></span>
          </span>
        </div>

        {{ widget.label }}
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'BuilderBlockCreate',
  props: {
    container: {
      type: Array,
      required: true
    },
    widgets: {
      type: Object,
      required: true
    },
    allowedWidgets: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      showPicker: false
    }
  },
  methods: {
    add(name, widget) {
      this.container.push({
        widget: name,
        settings: {},
        children: [],
      })

      this.$emit('updateContainer', this.container)
      this.togglePicker()
    },
    togglePicker() {
      this.showPicker = !this.showPicker
    }
  },
  components: {
  },
  mounted() {
  }
}
</script>
