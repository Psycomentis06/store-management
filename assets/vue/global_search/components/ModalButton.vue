<template>
  <button class="btn btn-primary search-btn" @click="openModal"><i class="ri-search-line"></i></button>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default  defineComponent({
  name: "ModalButton",
  mounted() {
    const modalSelector = $('#search-modal');
    if (window.location.hash === '#global-search') {
      this.openModal();
    }

    modalSelector.on('shown.bs.modal', function () {
      window.location.hash = '#global-search'
    })

    modalSelector.on('hide.bs.modal', function () {
      window.location.hash = '#'
    })
  },
  methods: {
    openModal: () => {
      $('#search-modal').appendTo("body").modal('show');
    },
    closeModal: () => {
      $('search-modal').modal('hide')
    },
  }
})
</script>

<style lang="scss">
  .search-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
  }
</style>