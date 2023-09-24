<template>
  <div>
    <b-form @submit="onSubmit">

      <h1 style="text-align: center">Login</h1>
      <hr>

      <b-form-group label="Email address:" label-for="email">
        <b-form-input id="email" v-model="form.email" type="email"
                      placeholder="Enter email" required
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Your Password:" label-for="password">
        <b-form-input id="password" v-model="form.password" type="password"
                      placeholder="Password" required
        ></b-form-input>
      </b-form-group>

      <br>
      <b-button type="submit" variant="primary" style="width: 100%;">Login</b-button>
      <hr>

      <p>
        Are you don't have an account <a href="#/Register">create</a> new account?<br>
        Do you <a href="#/PasswordForget">forgot your password</a>?
      </p>

      <loading-modal ref="loading"></loading-modal>
      <message-modal ref="message" title="Admin Login" :message="message"></message-modal>

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
        message:'',
        form: {
          email: '',
          password: '',
        },
      }
    },
    methods: {
      async onSubmit(event) {
        event.preventDefault()
        this.$refs.loading.show()
        const response = await this.$axios.post('/auth/login', {
          email: this.form.email,
          password: this.form.password,
        })
        console.log(response);
        this.message = response.data.message ?? response.statusText;
        this.$refs.message.show();
        if (response.data.success) window.location.href = '';
        this.$refs.loading.hide()
      }
    }
  }
</script>
