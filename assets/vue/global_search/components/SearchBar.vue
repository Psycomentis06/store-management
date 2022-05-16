<template>
  <div class="input-group mb-3 position-relative">
    <div class="input-group-prepend">
      <span class="input-group-text" id="basic-addon1"><i class="las la-search"></i></span>
    </div>
    <input v-model="searchValue" @keyup="searchInputHandler" type="text" class="form-control"
           placeholder="Search for any thing" aria-label="search" aria-describedby="basic-addon1">
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "SearchBar",
  data() {
    return {
      searchValue: ""
    }
  },
  methods: {
    searchInputHandler: function (event: KeyboardEvent) {
      console.log(this.searchValue)
      const target = event.target as HTMLInputElement
    },
  }
})
</script>

<style lang="scss" scoped>

</style>