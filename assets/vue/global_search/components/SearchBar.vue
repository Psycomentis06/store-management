<template>
  <div class="input-group mb-3 position-relative">
    <div class="input-group-prepend">
      <span class="input-group-text" id="basic-addon1"><i class="las la-search"></i></span>
    </div>
    <input v-model="searchValue" @keyup="searchInputHandler" type="text" class="form-control"
           placeholder="Search for any thing" aria-label="search" aria-describedby="basic-addon1">
    <span class="search-autocomplete">{{ getHint('Hello') }}</span>
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
    getHint: function (phrase: string) {
      const hintLength = this.searchValue.length
      let spaces = ""
      for (let i = 0; i < hintLength; i++) {
        spaces += " ";
      }
      return phrase.replace(this.searchValue, spaces);
    }
  }
})
</script>

<style lang="scss" scoped>
.search-autocomplete {
  color: var(--light);
  top: 11px;
  left: 48px;
  font-size: 14px;
  font-stretch: 100%;
  position: absolute;
  z-index: 50;
}

</style>