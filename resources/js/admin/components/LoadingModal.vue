<template>
  <div>
    <b-modal ref="loading_modal" centered :title="title" hide-footer no-close-on-backdrop no-close-on-esc hide-header-close>
      <p class="my-4"><b-spinner></b-spinner> {{ message }}</p>
    </b-modal>
  </div>
</template>

<script>
  export default {
    props: {
      title: {
        type: String,
        default: 'Loading'
      },
      message: {
        type: String,
        default: 'Loading...'
      },
    },
    methods: {
      show() {
        this.$refs.loading_modal.show();
      },
      hide() {
        this.$refs.loading_modal.hide();
      }
    }
  }
</script>
