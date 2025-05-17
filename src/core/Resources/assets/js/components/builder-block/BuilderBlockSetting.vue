<template>
  <label class="form-group mb-2">
    <span v-if="params.label && params.type !== 'checkbox'" v-text="params.label"></span>

    <input
      v-if="['number', 'checkbox', 'text', 'color', 'range'].includes(params.type)"
      v-model="item.settings[setting]"
      v-bind="params.attr"
      :type="params.type"
      :class="{'form-control': params.type !== 'checkbox'}"
    />

    <span v-if="params.label && params.type == 'checkbox'" v-text="params.label"></span>

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
  </label>
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
}
</script>

<style scoped>
label > span {
  margin-bottom: 3px;
}
</style>
