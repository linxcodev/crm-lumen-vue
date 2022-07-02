<template>
  <div class="c-body login">
    <main class="c-main">
      <div class="container-fluid">
        <div class="fade-in">
          <!-- login -->
          <div class="login-page">
            <div style="text-align: center; color: white" class="mb-4">
              <h1>SoftCRM</h1>
              
              <h4 class="small-text">
                Customer relationship management system
              </h4>
            </div>

            <div class="form">
              <input
                type="email"
                v-model="data.email"
                placeholder="Write your email here ..."
              />

              <input
                type="password"
                v-model="data.password"
                placeholder="Write your password here ..."
              />

              <button @click="doLogin()">login</button>
            </div>
          </div>
          <!-- end login -->
        </div>
        <!-- end fade -->
      </div>
    </main>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import { errorToas } from '../entities/notif'

export default {
  name: "Login",
  data() {
    return {
      data: {
        email: 'admin@admin.com',
        password: 'admin'
      }
    }
  },
  created() {
    if (this.isAuth) {
      this.$router.push({ name: 'dashboard' })
    }
  },
  computed: {
    ...mapGetters(['isAuth'])
  },
  methods: {
    ...mapActions('auth', ['submit']),

    async doLogin() {
      try {
        await this.submit(this.data)
        
        if (this.isAuth) {
          this.$router.push({ name: 'dashboard' })
        }
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas())
      }
    }
  }
};
</script>