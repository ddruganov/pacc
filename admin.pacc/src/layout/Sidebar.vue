<template>
  <div class="sidebar bg-dark">
    <div class="bullets d-flex flex-column">
      <a
        v-for="(entry, i) in menu"
        :key="i"
        class="bullet"
        :class="{ active: isActive(entry.route) }"
        :href="entry.route"
      >
        <i :class="entry.icon" class="me-3" style="font-size: 20px; width: 25px" />
        <span>{{ entry.name }}</span>
      </a>
    </div>
  </div>
</template>

<style lang="scss">
.sidebar {
  width: 250px;
  position: absolute;
  height: 100%;
  z-index: 2;
  .bullets {
    .bullet {
      color: white !important;
      text-decoration: none;
      padding: 1rem;
      &.active,
      &:hover {
        background-color: rgba(white, 0.25);
      }
      &.active {
        border-left: 1rem solid rgba(white, 0.5);
      }
      &:focus {
        background-color: rgba(white, 0.5);
      }
    }
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class Sidebar extends Vue {
  menu = [
    { route: "/", name: "Главная", icon: "fas fa-home" },
    { route: "/passes", name: "Абонементы", icon: "fas fa-ticket-alt" },
    { route: "/clients", name: "Клиенты", icon: "fas fa-user-friends" },
    { route: "/staff", name: "Персонал", icon: "fas fa-users" },
    { route: "/coaches", name: "Тренеры", icon: "far fa-id-badge" },
    { route: "/activities", name: "Виды активности", icon: "fas fa-basketball-ball" },
    { route: "/calendar", name: "Календарь", icon: "far fa-calendar-alt" },
    { route: "/news", name: "Новости", icon: "far fa-newspaper" },
    { route: "/stats", name: "Статистика", icon: "fas fa-chart-line" },
  ];
  isActive(route: string) {
    const currentRoute = this.$router.currentRoute.value.fullPath;
    return route === "/" ? currentRoute === "/" : currentRoute.includes(route);
  }
}
</script>
