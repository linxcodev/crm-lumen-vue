<template>
  <div class="c-body">
    <main class="c-main">
      <div class="container-fluid">
        <div class="fade-in">
          <!-- component -->
          <form-pengaturan />

          <system-log />
          <!-- end component -->
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import FormPengaturan from '../../components/system/Pengaturan.vue'
import SystemLog from '../../components/system/Log.vue'

export default {
  name: "Pengaturan",
  components: {
    FormPengaturan,
    SystemLog
  },
  created() {
  },
};
</script>