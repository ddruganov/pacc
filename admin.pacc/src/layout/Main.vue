<template>
  <div v-if="dataLoaded" class="d-flex flex-column">
    <topbar @toggleSidebar="toggleSidebar" />
    <div class="basic-layout d-flex" @click="tryCloseSidebar">
      <transition name="slide">
        <sidebar v-show="showSidebar" />
      </transition>
      <div class="content-container d-flex flex-column w-100" @click="showSidebar && toggleSidebar(false)">
        <div class="content container p-0">
          <router-view />
        </div>
        <Footer />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.basic-layout {
  min-height: calc(100vh - 71px);
  width: 100%;
  .content-container {
    flex: 1;
    .content {
      flex: 1;
    }
  }
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.5s ease;
}
.slide-enter-active {
  animation: slide-in 0.5s;
}
.slide-leave-active {
  animation: slide-in 0.5s reverse;
}
@keyframes slide-in {
  0% {
    transform: translateX(-250px);
  }
  100% {
    transform: translateX(0px);
  }
}
</style>

<script lang="ts">
import Topbar from "./Topbar.vue";
import Footer from "./Footer.vue";
import Sidebar from "./Sidebar.vue";

import { activityStore, LOAD_ACTIVITIES } from "@/store/modules/activity.store";
import { authStore, GET_CURRENT_USER } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { Sidebar, Topbar, Footer },
})
export default class MainLayout extends Vue {
  dataLoaded = false;
  showSidebar = false;

  mounted() {
    this.load();
  }
  load() {
    authStore
      .context(this.$store)
      .dispatch(GET_CURRENT_USER)
      .then(() => {
        this.dataLoaded = true;
        activityStore.context(this.$store).dispatch(LOAD_ACTIVITIES);
      });
  }
  toggleSidebar() {
    this.showSidebar = !this.showSidebar;
  }
}
</script>
