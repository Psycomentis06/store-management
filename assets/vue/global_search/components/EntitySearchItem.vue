<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">{{ data.title }}</h4>
      <p class="card-text">{{ data.body }}.</p>
      <a :href="createEntityLink()" class="btn btn-primary btn-block">More Details</a>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {IEntitySearchResponse} from "@/assets/vue/global_search/interfaces/IEntitySearchResponse";

export default defineComponent({
  name: "EntitySearchItem",
  props: {
    data: {
      required: true,
    },
    redirectUrl: {
      type: String,
      required: true
    }
  },
  methods: {
    createEntityLink(): string {
      const d = this.data as IEntitySearchResponse;
      return this.redirectUrl + d.id;
    }
  }
})
</script>

<style scoped>

</style>