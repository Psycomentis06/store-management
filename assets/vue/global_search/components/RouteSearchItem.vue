<template>
  <div class="card">
    <div class="card-body">
      <!--
      <h4 class="card-title" v-html="highlightText(data.path, searchStore.searchQuery)"></h4>
      <p class="card-text" v-html="highlightText(data.description, searchStore.searchQuery)"></p>
      -->
      <h4 class="card-title">{{ data.path }}</h4>
      <p class="card-text">{{ data.description }}</p>
      <a :href="data.path" class="btn btn-primary btn-block">Go To</a>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import { highlightText }  from '../utils/highlightText';
import { useSearchStore } from '../stores/searchStore'
export default defineComponent({
  name: "RouteSearchItem",
  props: {
    data: {
      required: true
    }
  },
  setup() {
    return {
      searchStore: useSearchStore
    }
  }
})
</script>

<style scoped>

</style>