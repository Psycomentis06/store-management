<template>
  <ModalButton />
  <SearchModal />
</template>

<script lang="ts">
import SearchModal from './components/SearchModal.vue';
import ModalButton from './components/ModalButton.vue';
export default {
  name: "App",
  components: {
    SearchModal,
    ModalButton
  }
}
</script>

<style scoped>

</style>