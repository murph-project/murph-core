<template>
  <div class="block" :key="blockKey">
    <BuilderBlockItem
      v-for="(block, key) in value"
      :item="block"
      :widgets="widgets"
      @remove-item="removeBlock(key)"
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
import BuilderBlockForm from './BuilderBlockForm'

const widgets = {
  row: {
    label: 'Bootstrap row',
    settings: {
    },
    isContainer: true,
    widgets: ['column'],
  },
  column: {
    label: 'Bootstrap column',
    settings: {
      size: {label: 'Size', type: 'input', attr: {type: 'number'}},
      sizeMd: {label: 'Size MD', type: 'input', attr: {type: 'number'}},
    },
    isContainer: true,
    widgets: [],
  },
  tinymce: {
    label: 'TinyMCE',
    settings: {
      value: {label: 'Value', type: 'textarea', attr: {'data-tinymce': ''}},
    },
    isContainer: false,
    widgets: [],
  }
}

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
    removeBlock(key) {
      let newValue = []

      this.value.forEach((v, k) => {
        if (k !== key) {
          newValue.push(v)
        }
      })

      console.log(newValue)

      this.value = newValue
      ++this.blockKey
    }
  },
  components: {
    BuilderBlockItem,
    BuilderBlockCreate,
    BuilderBlockForm,
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
</script>
