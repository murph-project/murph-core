<style scoped>
.builder-block-picker {
  padding: 8px;
  border: 1px solid #333;
  border-radius: 4px;
  background: #fff;
}

.builder-block-picker-menu {
  width: 150px;
}

.builder-block-picker-widgets {
  width: calc(100% - 150px - 10px);
  padding-left: 5px;
}

.nav-item {
  cursor: pointer;
  width: 100%;
}

.widget-icon {
  margin-right: 3px;
}

.widget {
  background: #fff;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 7px;
  margin-bottom: 9px;
  border: 1px solid #b4b4b4;
  font-weight: bold;
}

.widget:hover {
  background: #eee;
  border: 1px solid #1e2430;
}

.search {
  max-width: 300px;
}
</style>

<template>
  <div class="builder-add" :class="{'builder-add-top': position === 'top'}">
    <button type="button" class="btn btn-secondary" v-on:click="togglePicker">
      <span class="fa fa-plus"></span>
    </button>

    <div class="builder-block-picker mt-2 row" :class="{'d-none': !showPicker}">
      <div class="col-auto builder-block-picker-menu">
        <ul class="nav nav-pills pl-0">
          <li
            v-for="(category, key) in categories()"
            v-if="Object.keys(category.widgets).length"
            class="nav-item d-block"
          >
            <a
              class="nav-link d-block mb-1"
              :class="{'active': activeCategory == key}"
              v-on:click="activeCategory = key"
            >
              {{ category.label }}
            </a>
          </li>
        </ul>
      </div>
      <div
        v-for="(category, key) in categories()"
        v-if="Object.keys(category.widgets).length"
        class="col-auto builder-block-picker-widgets"
        :class="{'d-none': activeCategory !== key}"
      >
        <div class="row">
          <div class="col-12 mb-4">
            <input v-model="search" placeholder="Type to search..." class="form-control search">
          </div>
          <div
            v-for="(widget, name) in category.widgets"
            v-on:click="add(name, widget)"
            v-if="matchSearch(widget.label) || matchSearch(name)"
            class="widget col-auto"
          >
            <span class="widget-icon" v-if="widget.icon" v-html="widget.icon"></span>
            {{ widget.label }}
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
    openedBlocks: {
      type: Object,
      required: true
    },
    position: {
      type: [String, Number],
      required: true
    },
  },
  data() {
    return {
      showPicker: false,
      activeCategory: 'all',
      search: '',
    }
  },
  methods: {
    add(name, widget) {
      let settings = {}

      for (let i in widget.settings) {
        settings[i] = widget.settings[i].default
      }

      const block = {
        id: this.makeId(),
        widget: name,
        settings,
        children: [],
      }


      if (this.position === 'bottom') {
        this.container.push(block)
        this.$emit('updateContainer', this.container)
      } else if (this.position === 'top') {
        this.container.unshift(block)
      } else {
        this.container.splice(this.position+1, 0, block)
      }

      this.$emit('updateContainer', this.container)
      this.openedBlocks[block.id] = true
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
    matchSearch(name) {
      if (!this.search.trim().length) {
        return true
      }

      return name.toLowerCase().includes(this.search.toLowerCase())
    },
    togglePicker() {
      this.showPicker = !this.showPicker
    },
    categories() {
      let items = {
        all: {label: 'All', widgets: {}},
      }

      for (let widgetName in this.widgets) {
        let value = this.widgets[widgetName]

        if (!value.category) {
          value.category = 'all'
        }

        if (typeof items[value.category] === 'undefined') {
          items[value.category] = {
            label: value.category,
            widgets: {},
          }
        }

        if (!this.allowedWidgets.length || this.allowedWidgets.includes(widgetName)) {
          items[value.category].widgets[widgetName] = value
          items['all'].widgets[widgetName] = value
        }
      }

      return items
    }
  },
}
</script>
