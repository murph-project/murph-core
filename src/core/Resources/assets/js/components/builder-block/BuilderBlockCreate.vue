<style scoped>
.categories {
  padding: 10px;
  text-align: left;
}

.category {
  padding: 10px 0;
}

.category-label {
  font-weight: bold;
  margin-bottom: 5px;
}

.widget {
  display: inline-block;
}

.widget-content {
  background: #fff;
  padding: 10px;
  text-align: center;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 5px;
  margin-bottom: 5px;
  border: 1px solid #1e2430;
}

.widget-icon {
  margin-top: 5px;
  font-size: 25px;
}

.widget:hover {
  background: #eee;
}

.widget-label {
  font-weight: bold;
}
</style>

<template>
  <div class="builder-add">
    <span class="btn btn-sm btn-secondary" v-on:click="togglePicker">
      <span class="fa fa-plus"></span>
    </span>

    <div class="categories mt-2 list-group" :class="{'d-none': !showPicker}">
      <div
        v-for="category in categories()"
        v-if="Object.keys(category.widgets).length"
        class="category"
      >
        <div
          v-if="category.label != 'none'"
          v-text="category.label"
          class="category-label row"
        ></div>

        <div
          v-for="(widget, name) in category.widgets"
          v-on:click="add(name, widget)"
          class="widget col-3"
        >
          <div class="widget-content">
            <div class="widget-label">
              {{ widget.label }}
            </div>

            <div class="widget-icon" v-if="widget.icon" v-html="widget.icon">
            </div>
          </div>
        </div>
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
      showPicker: false,
    }
  },
  methods: {
    add(name, widget) {
      let settings = {}

      for (let i in widget.settings) {
        settings[i] = widget.settings[i].default
      }

      this.container.push({
        id: this.makeId(),
        widget: name,
        settings,
        children: [],
      })

      this.$emit('updateContainer', this.container)
      this.togglePicker()
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
    togglePicker() {
      this.showPicker = !this.showPicker
    },
    categories() {
      let items = {}

      for (let widgetName in this.widgets) {
        let value = this.widgets[widgetName]

        if (!value.category) {
          value.category = 'none'
        }

        if (typeof items[value.category] === 'undefined') {
          items[value.category] = {
            label: value.category,
            widgets: {},
          }
        }

        if (!this.allowedWidgets.length || this.allowedWidgets.includes(widgetName)) {
          items[value.category].widgets[widgetName] = value
        }
      }

      return items
    }
  },
}
</script>
