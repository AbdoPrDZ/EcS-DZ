<template>
  <div>
    <b-form @submit="onSubmit">

      <h1>Email Verify</h1>
      <hr>

      <!-- <b-button variant="primary" @click="resend">Resend verification email</b-button> -->

      <b-form-group label="Email verify code:" label-for="verify-code">
        <b-form-input id="verify-code" v-model="form.code"
                      placeholder="Enter your email verify code" required
                      :state="errors.code.state"
                      aria-invalid="code-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="code-feedback">
          {{ errors.code.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <br>
      <b-button type="submit" variant="primary" style="width: 100%;">Submit</b-button>
      <hr>
      <b-link :href="defaultAction" @click="resend">Resend verification email</b-link>

      <loading-modal ref="loading"></loading-modal>
      <message-modal ref="message" title="Email verification" :message="message"></message-modal>

    </b-form>
  </div>
</template>

<script>
  import LoadingModal from '../../components/LoadingModal.vue';
  import MessageModal from '../../components/MessageModal.vue';

  export default {
    components: {
    'loading-modal': LoadingModal,
    'message-modal': MessageModal,
    },
    data() {
      return {
        message: '',
        form: {
          code: '',
        },
        errors: {
          code: {},
        }
      }
    },
    methods: {
      async onSubmit(event) {
        event.preventDefault()
        this.errors = {
          code: {},
        }
        if (this.form.code.length != 6) {
          this.errors.code = {state: false, error: 'Verify code must be 6 characters'};
          return;
        }
        this.$refs.loading.show()
        const response = await this.$axios.post('/auth/email_verify', {
          code: this.form.code,
        }, {
          headers: {Authorization: `Bearer ${window.emailVerifyToken}`}
        })
        this.message = response.data.message ?? response.statusText
        this.$refs.message.show()
        if (response.data.success) this.$router.push('/login')
        this.$refs.loading.hide()
      },
      async resend(event) {
        this.$refs.loading.show()
        const response = await this.$axios.get('/auth/email_resend', {
          headers: {Authorization: `Bearer ${window.emailVerifyToken}`}
        })
        this.message = response.data.message ?? response.statusText
        this.$refs.message.show()
        if (response.data.success) window.emailVerifyToken = response.data.token
        this.$refs.loading.hide()
      },
      onReset(event) {
        event.preventDefault()
        this.form.code = ''
        this.errors = {
          code: {},
        }
      }
    }
  }
</script>
