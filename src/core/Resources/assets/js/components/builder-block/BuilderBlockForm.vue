<template>
  <div>
    <div v-if="value !== null && typeof value === 'object'">
      <BuilderBlockForm v-for="(item, key) in value" :name="name + '[' + key + ']'" :value="item" />
    </div>
    <div v-else>
      <input type="text" :name="name" :value="value">
    </div>
  </div>
</template>

<script>
export default {
  name: 'BuilderBlockForm',
  props: {
    name: {
      type: name,
      required: true
    },
    value: {
      required: true
    }
  },
  data() {
    return {
    }
  },
}
</script>
