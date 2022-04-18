<template>
  <template v-if="stat">
    <div class="bg-white rounded-10 p-3 mt-5">
      <div class="d-flex flex-column">
        <h3 class="m-0">Просмотр статистики «{{ stat.name }}»</h3>
        <go-back link="/stats" />
      </div>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <table class="table">
        <thead>
          <tr>
            <th v-for="field in stat.fields" :key="field.id" scope="col">{{ field.alias }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(data, i) in stat.data" :key="i">
            <td v-for="(fieldData, j) in data" :key="j">
              {{ fieldData }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
</template>

<style lang="scss" scoped></style>

<script lang="ts">
import Api from "@/common/api";
import { Options, Vue } from "vue-class-component";
import Stat from "@/types/stats/Stat";
import GoBack from "@/components/GoBack.vue";

@Options({
  components: { GoBack },
})
export default class StatEdit extends Vue {
  stat: Stat | null = null;

  get statId() {
    return Number(this.$route.params.statId);
  }

  mounted() {
    this.load();
  }

  load() {
    Api.stats.view(this.statId).then((response) => {
      this.stat = response.data;
    });
  }
}
</script>
