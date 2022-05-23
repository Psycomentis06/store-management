<template>
  <div>
    <ul class="list-unstyled">
      <li>
        <EntitySearchItem data=""/>
        <EntitySearchItem data=""/>
        <EntitySearchItem data=""/>
        <EntitySearchItem data=""/>
        <EntitySearchItem data=""/>
        <EntitySearchItem data=""/>
      </li>
    </ul>
  </div>
</template>

<script>
import EntitySearchItem from "./EntitySearchItem";
export default {
  name: "SearchResultContainer",
  components: {EntitySearchItem}
}
</script>

<style scoped>

</style>