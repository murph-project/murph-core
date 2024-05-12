<template>
  <div class="block" :key="blockKey">
    <BuilderBlockItem
      v-for="(block, key) in value"
      :item="block"
      :widgets="widgets"
      :isFirst="key === 0"
      :isLast="key == Object.keys(value)[Object.keys(value).length -1]"
      @remove-item="removeBlock(key)"
      @move-item-up="moveBlockUp(key)"
      @move-item-down="moveBlockDown(key)"
    />
    <div class="container">
      <BuilderBlockCreate
        :container="value"
        :widgets="widgets"
        :allowedWidgets="[]"
      />
    </div>
    <textarea :name="name" class="d-none">{{ toJson(value) }}</textarea>
  </div>
</template>

<script>
import BuilderBlockItem from './BuilderBlockItem'
import BuilderBlockCreate from './BuilderBlockCreate'

const widgets = {
  bsContainer: {
    category: 'Boostrap',
    label: 'Container',
    settings: {
    },
    isContainer: true,
    widgets: [],
  },
  bsRow: {
    category: 'Boostrap',
    label: 'Row',
    settings: {
    },
    isContainer: true,
    widgets: ['bsColumn'],
  },
  bsColumn: {
    category: 'Boostrap',
    label: 'Column',
    settings: {
      size: {label: 'Size', type: 'input', attr: {type: 'number'}},
      sizeMd: {label: 'Size MD', type: 'input', attr: {type: 'number'}},
    },
    isContainer: true,
    widgets: [],
  },
  tinymce: {
    category: 'Editor',
    label: 'TinyMCE',
    settings: {
      value: {label: 'Value', type: 'textarea', attr: {'data-tinymce': ''}},
    },
    isContainer: false,
    widgets: [],
  },
  select: {
    category: null,
    label: 'Item with Select',
    settings: {
      value: {label: 'Value', type: 'select', attr: {}, options: [
        {value: 'a', text: 'A'},
        {value: 'b', text: 'B'},
      ]}
    },
  }
}

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
      widgets,
      blockKey: 0
    }
  },
  methods: {
    toJson(value) {
      return JSON.stringify(value)
    },
    triggerBuilderBlockEvent() {
      document.querySelector('body').dispatchEvent(new Event('builder_block.update'))
    },
    moveBlockUp(key) {
      const prev = this.value[key-1]
      const current = this.value[key]

      this.value[key-1] = current
      this.value[key] = prev
      ++this.blockKey
    },
    moveBlockDown(key) {
      const next = this.value[key+1]
      const current = this.value[key]

      this.value[key+1] = current
      this.value[key] = next
      ++this.blockKey
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
  },
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
  },
  mounted() {
    this.triggerBuilderBlockEvent()
  },
  created() {
    this.triggerBuilderBlockEvent()
  },
  updated() {
    this.triggerBuilderBlockEvent()
  }
}

/*

const blocks = [
  {
    widget: 'row',
    settings: {},
    children: [
      {
        widget: 'column',
        settings: {
          size: 12,
          sizeMd: 6,
        },
        children: [
          {
            widget: 'tinymce',
            settings: {
              value: '<p>Hello, world!</p>',
            },
            children: null
          },
        ],
      },
      {
        widget: 'column',
        settings: {
          size: 12,
          sizeMd: 6,
        },
        children: [],
      },
    ]
  }
]
*/
</script>
