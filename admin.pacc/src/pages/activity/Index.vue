<template>
  <div class="d-flex align-items-center justify-content-between bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Виды активности</h3>
    <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <div class="create d-flex" style="max-width: 375px">
      <input v-model="newActivityName" class="form-control form-control-sm" placeholder="Введите название активности" />
      <button :disabled="!newActivityName" class="btn btn-primary btn-sm ms-3" @click="create()">Добавить</button>
    </div>
  </div>

  <div class="d-flex flex-wrap mt-3">
    <div
      v-for="activity in activities"
      :key="activity.id"
      class="bg-white d-flex justify-content-between align-items-center rounded-10 p-3 mb-3 me-3 w-fit-content"
    >
      <!-- REGULAR INFO VIEW -->
      <input
        v-model="activity.name"
        class="form-control w-fit-content border-0"
        role="button"
        @change="(e) => saveActivity({ id: activity.id, name: e.target.value })"
      />
      <!-- CONTROLS -->
      <div class="d-flex justify-content-around align-items-center">
        <button class="btn btn-sm btn-danger ms-1" @click="remove(activity.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";

import { activityStore, LOAD_ACTIVITIES } from "@/store/modules/activity.store";
import { Options, Vue } from "vue-class-component";
import Activity from "@/types/activity/Activity";
import SaveIndicator from "@/components/SaveIndicator.vue";

@Options({
  components: { SaveIndicator },
})
export default class ActivityIndex extends Vue {
  newActivityName: string = "";
  currentlySaving: number = 0;
  currentlySavingSuccess: boolean = false;
  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  get [LOAD_ACTIVITIES]() {
    return () => activityStore.context(this.$store).dispatch(LOAD_ACTIVITIES);
  }
  increment = 0;
  decrement = 0;

  mounted() {
    this.load();
  }
  load() {
    this[LOAD_ACTIVITIES]();
  }
  create() {
    this.saveActivity({ id: undefined, name: this.newActivityName }).finally(() => {
      this.newActivityName = "";
    });
  }
  saveActivity(activity: Activity) {
    this.currentlySaving++;
    return Api.activity
      .save(activity)
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        response.success ? this.load() : this.$notifications.error("Ошибка сохранения активности!");
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }
  remove(activityId: number) {
    this.currentlySaving++;
    Api.activity
      .delete(activityId)
      .then((response) => {
        response.success ? this.load() : this.$notifications.error("Ошибка удаления активности!");
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }
}
</script>
