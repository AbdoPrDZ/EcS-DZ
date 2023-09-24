<template>
  <div>
    <b-form @submit="onSubmit" @reset="onReset">

      <h1 style="text-align: center">Register</h1>
      <hr>

      <b-form-group label="Username:" label-for="username">
        <b-form-input id="username" v-model="form.username"
                      placeholder="Enter your username" required
                      :state="errors.username.state"
                      aria-invalid="username-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="username-feedback">
          {{ errors.username.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="First name:" label-for="first_name">
        <b-form-input id="first_name" v-model="form.first_name"
                      placeholder="Enter your first name" required
                      :state="errors.first_name.state"
                      aria-invalid="first_name-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="first_name-feedback">
          {{ errors.first_name.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="last name:" label-for="last_name">
        <b-form-input id="last_name" v-model="form.last_name"
                      placeholder="Enter your last name" required
                      :state="errors.last_name.state"
                      aria-invalid="last_name-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="last_name-feedback">
          {{ errors.last_name.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Email address:" label-for="email">
        <b-form-input id="email" v-model="form.email" type="email"
                      placeholder="Enter your email" required
                      :state="errors.email.state"
                      aria-invalid="email-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="email-feedback">
          {{ errors.email.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Phone number:" label-for="phone">
        <b-form-input id="phone" v-model="form.phone"
                      placeholder="Enter your phone" required
                      :state="errors.phone.state"
                      aria-invalid="phone-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="phone-feedback">
          {{ errors.phone.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Address:" label-for="address">
        <b-form-input id="address" v-model="form.address"
                      placeholder="Enter your address"
                      :state="errors.address.state"
                      aria-invalid="address-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="address-feedback">
          {{ errors.address.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Gander">
        <b-form-radio-group
          v-model="form.gander" class="mb-2" style="display: flex; gap: 10px;" name="gander"
                   :options="[{text: 'Male', value: 'male'}, {text: 'Female', value: 'female'}]">
      </b-form-radio-group>
      </b-form-group>

      <b-form-group label="Password:" label-for="password">
        <b-form-input id="password" v-model="form.password" type="password"
                      placeholder="Enter your password" required
                      :state="errors.password.state"
                      aria-invalid="password-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="password-feedback">
          {{ errors.password.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Confirm Password:" label-for="confirm-password">
        <b-form-input id="confirm-password" v-model="form.confirm" type="password"
                      placeholder="Confirm your password" required
                      :state="errors.confirm.state"
                      aria-invalid="confirm-feedback"
        ></b-form-input>
        <b-form-invalid-feedback id="confirm-feedback">
          {{ errors.confirm.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <br>

      <b-button type="submit" variant="primary" style="width: 100%;">Register</b-button>
      <hr>
      <p>
        Do you have already an account <a href="#/Login">login</a>?
      </p>

      <loading-modal ref="loading"></loading-modal>
      <message-modal ref="message" title="Admin Register" :message="message"></message-modal>

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
          username: '',
          first_name: '',
          last_name: '',
          email: '',
          phone: '',
          address: '',
          gander: 'male',
          password: '',
          confirm: '',
        },
        errors: {
          username: {},
          first_name: {},
          last_name:  {},
          email: {},
          phone: {},
          address: {},
          gander: {},
          password: {},
          confirm: {},
        }
      }
    },
    methods: {
      async onSubmit(event) {
        event.preventDefault()
        this.errors = {
          username: {},
          first_name: {},
          last_name:  {},
          email: {},
          phone: {},
          address: {},
          gander: {},
          password: {},
          confirm: {},
        }

        if (this.form.username.length < 4) {
          this.errors.username = {state: false, error: 'Username is too short'};
          return;
        } else if (this.form.password.length < 6) {
          this.errors.password = {state: false, error: 'Password must not be less then 6 characters'};
          return;
        } else if (this.form.password != this.form.confirm) {
          this.errors.password = {state: false, error: 'Password and confirm must be equals'};
          this.errors.confirm = {state: false, error: 'Password and confirm must be equals'};
          return;
        }
        this.$refs.loading.show()
        var response = await this.$axios.post('/auth/register', {
          username: this.form.username,
          first_name: this.form.first_name,
          last_name: this.form.last_name,
          email: this.form.email,
          phone: this.form.phone,
          address: this.form.address,
          gander: this.form.gander,
          password: this.form.password,
        });
        this.message = response.data.message ?? response.statusText;
        this.$refs.message.show();
        if (response.data.success) {
          window.emailVerifyToken = response.data.token;
          this.$router.push('/email_verify');
        }
        this.$refs.loading.hide()
      },
      onReset(event) {
        event.preventDefault()
        this.form = {
          first_name: '',
          last_name: '',
          email: '',
          phone: '',
          address: '',
          gander: 'male',
          password: '',
          confirm: '',
        },
        this.errors = {
          first_name: {},
          last_name:  {},
          email: {},
          phone: {},
          address: {},
          gander: {},
          password: {},
          confirm: {},
        }
      }
    }
  }
</script>
