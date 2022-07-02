<template>
    <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
            <span class="c-header-toggler-icon"></span>
        </button>
        <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <span class="c-header-toggler-icon"></span>
        </button>
        <ul class="c-header-nav d-md-down-none ml-auto">
            <li class="c-header-nav-item text-muted">{{ authenticated.nama }}</li>    
        </ul>
        <ul class="c-header-nav mr-4 ">
            <li class="c-header-nav-item dropdown">
                <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="c-avatar"><img class="c-avatar-img" src="/img/user.png"></div>
                </a>

                <div class="dropdown-menu dropdown-menu-right pt-0">
                    <a class="dropdown-item" href="#" @click.prevent="logout">
                        <i class="flaticon-logout"></i> &nbsp; Logout
                    </a>
                </div>
            </li>
        </ul>
        <div class="c-subheader px-3">
            <breadcrumb></breadcrumb>
        </div>
        <b-modal id="modal-profile" centered no-close-on-backdrop title="Ubah password">
            <div class="form-group">
                <label>Password</label>
                <input type="password" v-model="password" class="form-control" placeholder="Password" name="">
            </div>
            <div class="form-group">
                <label>Re-password</label>
                <input type="password" v-model="password2" class="form-control" placeholder="Re password" name="">
                <small class="text-danger" v-show="error">Password tidak sesuai</small>
            </div>
            <template v-slot:modal-footer="{ cancel }">
              <b-button size="sm" variant="secondary" @click="cancel()">
                Batal
              </b-button>
              <b-button size="sm" variant="primary" :disabled="error" @click="changePass">
                Ubah password
              </b-button>
            </template>
        </b-modal>
    </header>
</template>
<script>
import Breadcrumb from './Breadcrumb.vue'
import { mapState, mapActions } from 'vuex'
import { successToas, errorToas} from '../entities/notif'
export default {
    data() {
        return {
            password: '',
            password2: '',
            error: false
        }
    },
    computed: {
        ...mapState('user', ['authenticated'])
    },
    created() {
        this.getUserLogin()
    },
    components: {
      'breadcrumb' : Breadcrumb,
    },
    methods: {
        ...mapActions('user', ['getUserLogin']),

        async logout() {
            localStorage.removeItem('token')
            this.$store.state.token = localStorage.getItem('token')
            this.$router.push('/login')
        },
        async changePass() {
            if(this.error) {
                this.$swal({
                    title: 'Perhatikan password 2',
                    text: "Pastikan password sama",
                    icon: 'error',
                })
                return
            }
            try {
                await this.changeUserPassword({ password: this.password })
                this.$bvToast.toast('Password berhasil diubah', successToas())
                this.$bvModal.hide('modal-profile')
                this.password = ''
                this.password2 = ''
            } catch (error) {
                this.$bvToast.toast(error.message, errorToas())
            }
        }
    },
    watch:{
        password2(val) {
            if(this.password != val) {
                this.error = true
            } else {
                this.error = false
            }
        }
    }
}
</script>
