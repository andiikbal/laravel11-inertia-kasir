<template>
  <div class="c-app">
    <div
      class="c-sidebar c-sidebar-dark c-sidebar-fixed"
      :class="{
        'c-sidebar-lg-show': !$page.url.startsWith('/apps/transaksis'),
      }"
      id="sidebar"
    >
      <div class="c-sidebar-brand d-lg-down-none" style="background: #894b9d">
        <img
          :src="toko.logo"
          class="bg-light rounded shadow-sm p-1"
          width="35"
        />
        <span class="ml-2 font-weight-bold">{{ toko.nama }}</span>
      </div>

      <!-- sidebar -->
      <Sidebar />
      <!-- end sidebar -->
    </div>
    <div class="c-wrapper c-fixed-components">
      <!-- header -->
      <Header />
      <!-- end header -->

      <div class="c-body">
        <!-- content -->
        <slot />
        <!-- end content -->

        <footer class="c-footer">
          <div>
            <strong>{{ toko.nama }}</strong> &copy; 2024 -
            {{ new Date().getFullYear() }}
          </div>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../Components/Header.vue";
import Sidebar from "../Components/Sidebar.vue";

export default {
  components: {
    Header,
    Sidebar,
  },

  props: { toko: Object },
};
</script>

<style>
</style>
