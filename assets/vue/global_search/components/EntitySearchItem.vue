<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Special title treatment</h4>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
      <a href="#" class="btn btn-primary btn-block">Go somewhere</a>
    </div>
  </div>
</template>

<script>
export default {
  name: "EntitySearchItem",
  props: {
    data: {
      required: true
    }
  }
}
</script>

<style scoped>

</style>