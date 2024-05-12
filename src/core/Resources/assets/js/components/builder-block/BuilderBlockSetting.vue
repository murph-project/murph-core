<template>
  <div class="col-12 form-group">
    <label
      v-if="params.label"
      v-text="params.label">
    </label>

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

    <select
      v-if="params.type == 'select'"
      v-model="item.settings[setting]"
      v-bind="params.attr"
      class="form-control"
    >
      <option :value="v.value" v-for="(v, k) in params.options" :key="k">
        {{ v.text }}
      </option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'BuilderBlockSetting',
  props: {
    item: {
      type: Object,
      required: true,
    },
    params: {
      type: Object,
      required: true,
    },
    setting: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
    }
  },
}
</script>
