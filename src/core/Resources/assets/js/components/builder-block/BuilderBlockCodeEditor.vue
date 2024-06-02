<template>
  <div>
    <button
      type="button"
      class="btn btn-sm"
      v-on:click="open"
    >
      <i class="fas fa-code"></i>
    </button>
    <dialog ref="dialog" class="modal-dialog modal-dialog-large builder-code-editor">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Code editor</h5>
          <button
            type="button"
            class="close"
            v-on:click="close"
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
            v-on:click="close"
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
  </div>
</template>

<script>
export default {
  name: 'BuilderBlockCodeEditor',
  props: {
    value: {
      type: Array,
      required: true,
    },
    widgets: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      nextValue: null
    }
  },
  methods: {
    toJson(value) {
      return JSON.stringify(value, null, 2)
    },
    open() {
      this.nextValue = this.toJson(this.value)
      this.$refs.dialog.showModal()
    },
    close() {
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
          this.$emit('update', this.updateNextValueRecursiveIds(data))
          this.close()
        }

      } catch (e) {
        hasError = true
      }

      return this.$refs.codeEditor.classList.toggle('is-invalid', hasError)
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
  },
  mounted() {
  },
  created() {
  },
  updated() {
  }
}
</script>
