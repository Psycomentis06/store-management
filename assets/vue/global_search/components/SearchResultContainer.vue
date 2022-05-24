<template>
  <div>
    <div v-if="isEntityResponse(searchStore.result) && searchStore.searchQuery.length > 0">
      <h4> Entity </h4>
      <hr>
      <ul class="list-unstyled">
        <li v-for="d in searchStore.result.content">
          <EntitySearchItem :data="d" :redirect-url="searchStore.result.redirectUrl || '' " />
        </li>
      </ul>
    </div>
    <div v-else-if="isRouteResponse(searchStore.result) && searchStore.searchQuery.length > 0">
      <h4> Links </h4>
      <hr>
      <ul class="list-unstyled">
        <li v-for="d in searchStore.result.content">
          <RouteSearchItem :data="d" />
        </li>
      </ul>
    </div>
    <div v-else-if="searchStore.searchQuery.length > 0">
      <h2 class="text-center"> Nothing found </h2>
    </div>
    <div v-else>
      <h2 class="text-center"> Search for something </h2>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue';
import {useSearchStore} from '../stores/searchStore';
import RouteSearchItem from "./RouteSearchItem.vue";
import EntitySearchItem from "./EntitySearchItem.vue";
import {IEntitySearchResponse} from "@/assets/vue/global_search/interfaces/IEntitySearchResponse";
import {IRouteSearchResponse} from "@/assets/vue/global_search/interfaces/IRouteSearchResponse";
import {ISearchResponse} from "@/assets/vue/global_search/interfaces/ISearchResponse";

export default defineComponent({
  name: "SearchResultContainer",
  components: {RouteSearchItem, EntitySearchItem},
  setup() {
    return {
      searchStore: useSearchStore()
    }
  },
  methods: {
    isEntityResponse(obj: ISearchResponse): boolean {
      return (obj?.content?.length > 0 && obj.content[0].hasOwnProperty('title'))
    },
    isRouteResponse(obj: ISearchResponse): boolean {
      return (obj?.content?.length > 0 && obj?.content[0].hasOwnProperty('path'))
    }
  }
})
</script>

<style scoped>

</style>