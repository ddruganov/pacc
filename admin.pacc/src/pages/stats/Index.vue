<template>
  <div class="d-flex align-items-center bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Статистика</h3>
    <a href="#" @click.prevent="createStat()" class="btn btn-primary btn-sm ms-3 h-fit-content">
      <i class="fas fa-plus" />
    </a>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3 d-flex flex-column">
    <template v-if="stats.length">
      <div
        class="d-flex align-items-center"
        v-for="(stat, i) in stats"
        :key="stat.id"
        :class="{ 'mb-3': i < stats.length - 1 }"
      >
        <a :href="`/stats/${stat.id}/edit`" class="link-primary" title="Редактирование">{{ stat.name }}</a>
        <a :href="`/stats/${stat.id}/view`" class="ms-3 link-primary" title="Просмотр">
          <i class="fas fa-external-link-alt" />
        </a>
        <button class="btn btn-sm btn-danger ms-auto" @click="deleteStat(stat.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
    </template>
    <span v-else class="text-muted">У Вас пока нет созданных статистик</span>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import { Vue } from "vue-class-component";
import Stat from "@/types/stats/Stat";

export default class StatIndex extends Vue {
  stats: Stat[] = [];

  mounted() {
    this.load();
  }
  load() {
    Api.stats.getAll().then((response) => {
      this.stats = response.data;
    });
  }
  createStat() {
    Api.stats.save({ name: "Новая статистика" }).then((response) => {
      response.success && this.$router.push({ path: `/stats/${response.data.id}/edit` });
    });
  }
  deleteStat(statId: number) {
    Api.stats.delete(statId).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }
}
</script>
