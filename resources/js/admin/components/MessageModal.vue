<template>
  <div>
    <b-modal ref="message_modal" centered :title="title" ok-only hide-header-close>
      <p class="my-4">{{ message }}</p>
    </b-modal>
  </div>
</template>

<script>
  export default {
    props: {
      title: String,
      message: String,
    },
    methods: {
      show() {
        this.$refs.message_modal.show();
      },
      hide() {
        this.$refs.message_modal.hide();
      }
    }
  }
</script>
