<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Настройки</h3>
  </div>

  <div class="d-flex mt-3 mb-5">
    <div class="bg-white rounded-10 w-100 w-fit-content h-fit-content">
      <ul class="menu">
        <li
          class="item"
          :class="{ active: currentRoute.includes('general') }"
          @click="$router.push({ path: '/settings/general' })"
        >
          Общие
        </li>
        <li
          class="item"
          :class="{ active: currentRoute.includes('security') }"
          @click="$router.push({ path: '/settings/security' })"
        >
          Безопасность
        </li>
      </ul>
    </div>
    <div class="w-100 ms-3">
      <router-view />
    </div>
  </div>
</template>

<style lang="scss">
.menu {
  list-style: none;
  margin: 0;
  padding: 0;
  .item {
    padding: 0.5rem 2rem;
    cursor: pointer;
    &.active,
    &:hover {
      background-color: #eee;
    }
    &:active,
    &:focus {
      background-color: #ddd;
    }
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class SettingIndex extends Vue {
  get currentRoute() {
    return this.$router.currentRoute.value.fullPath;
  }
}
</script>
