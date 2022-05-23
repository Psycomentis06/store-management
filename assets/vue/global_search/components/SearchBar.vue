<template>

  <div v-if="searchStore.searchMode === 'SIMPLE' ">
    <div class="input-group mb-3 position-relative">
      <div class="input-group-prepend">
        <button @click="searchStore.toggleSearchMode" class="input-group-text" id="basic-addon-simple"><i
            class="las la-sliders-h"></i></button>
      </div>
      <input v-model="searchStore.searchQuery" @keyup="searchInputHandler()" type="text" class="form-control"
             placeholder="Search for any thing">
    </div>
  </div>
  <div v-else-if="searchStore.searchMode === 'ADVANCED' ">
    <div class="input-group mb-3 position-relative">
      <div class="input-group-prepend">
        <button @click="searchStore.toggleSearchMode" class="input-group-text"><i
            class="las la-search"></i></button>
      </div>
      <div class="input-group-prepend">
        <select name="s" class="input-group-text form-control" v-model="searchStore.searchScopeSelect">
          <option value="/e">Entity</option>
          <option value="/r">Page</option>
        </select>
      </div>
      <div class="input-group-prepend" v-if="searchStore.searchScopeSelect === '/e' ">
        <input v-model="searchStore.searchEntityName" class="input-group-text" type="text"
               placeholder="Entity Name">
      </div>
      <div class="input-group-prepend" v-if="searchStore.searchEntityName.length > 2 && searchStore.searchScopeSelect === '/e' ">
        <input v-model="searchStore.searchEntityFieldName" class="input-group-text" type="text"
               placeholder="Field Name">
      </div>
      <input v-model="searchStore.searchQuery" @keyup="searchInputHandler()" type="text" class="form-control"
             placeholder="Search for any thing">
    </div>
  </div>

</template>

<script lang="ts">
import {defineComponent} from 'vue'
import { useSearchStore } from '../stores/searchStore'

export default defineComponent({
  name: "SearchBar",
  setup() {
    const searchStore = useSearchStore();
    return {
      searchStore
    }
  },
  data() {
    return {

    }
  },
  methods: {
    searchInputHandler: function () {
      this.searchStore.searchHandler();
    },
  }
})
</script>

<style lang="scss" scoped>

</style>