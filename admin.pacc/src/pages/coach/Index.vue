<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Тренеры</h3>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <custom-remote-select
      :maxModelCount="1"
      modelName="staff"
      v-model="selectedUser"
      promptText="Начните вводить имя пользователя"
      class="mb-1"
    />
    <input
      type="number"
      min="0"
      max="100"
      v-model="newCoach.payRate"
      class="form-control form-control-sm mb-1"
      placeholder="Ставка, %"
    />
    <select v-if="activities" v-model="newCoach.activityId" class="form-control form-control-sm mb-1">
      <option :value="undefined" :disabled="true" selected>Выберите вид активности</option>
      <option v-for="activity in activities" :key="activity.id" :value="activity.id">
        {{ activity.name }}
      </option>
    </select>
    <button class="btn btn-primary btn-sm lh-normal" @click="create">Добавить тренера</button>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <coach-filter @apply="applyFilter" @reset="resetFilter" />
  </div>

  <div class="bg-white rounded-10 p-3 my-3">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Имя</th>
          <th scope="col">Телефон</th>
          <th scope="col">Ставка, %</th>
          <th scope="col">Вид активности</th>
          <th scope="col">Активен</th>
          <th scope="col" class="text-end">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="coach in coaches" :key="coach.id">
          <td>{{ coach.id }}</td>
          <td>{{ coach.name }}</td>
          <td>{{ coach.email }}</td>
          <td>
            <input
              v-model="coach.payRate"
              type="number"
              class="form-control p-0 pay-rate border-0"
              role="button"
              @change="save(coach)"
            />
          </td>
          <td>
            <select v-model="coach.activityId" @change="save(coach)" class="form-control p-0 border-0" role="button">
              <option v-for="activity in activities" :key="activity.id" :value="activity.id">
                {{ activity.name }}
              </option>
            </select>
          </td>
          <td>
            <div @click="toggle(coach.id)" class="coach-toggle rounded" role="button">
              <i v-if="coach.active" class="fas fa-check text-success" />
              <i v-else class="fas fa-times text-danger" />
            </div>
          </td>
          <td>
            <div class="controls d-flex justify-content-end">
              <button class="btn btn-sm btn-danger" @click="remove(coach.id)">
                <i class="far fa-trash-alt" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <pagination v-if="pageCount > 1" :currentPage="currentPage" :pageCount="pageCount" @change="changePage" />
</template>

<style lang="scss">
.coach-toggle {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  &:hover {
    background-color: #eee;
  }
}

.pay-rate {
  width: 50px;
  appearance: none;
  &::-webkit-outer-spin-button,
  &::-webkit-inner-spin-button {
    appearance: none;
  }
}
</style>

<script lang="ts">
import Api from "@/common/api/index";
import { LOAD_ALL_COACHES } from "@/store/modules/coach.store";
import CustomRemoteSelect from "@/components/CustomRemoteSelect.vue";
import CoachSaveForm from "@/types/coach/CoachSaveForm";
import { Options, Vue } from "vue-class-component";
import { coachStore } from "@/store/modules/coach.store";
import { activityStore } from "@/store/modules/activity.store";
import Coach from "@/types/coach/Coach";
import { Watch } from "vue-property-decorator";
import Pagination from "@/components/Pagination.vue";
import CoachFilter from "@/components/filters/CoachFilter.vue";

@Options({
  components: { CustomRemoteSelect, Pagination, CoachFilter },
})
export default class CoachIndex extends Vue {
  newCoach: CoachSaveForm = {};
  selectedUser: Coach[] = [];
  page = 1;
  filter = {};

  get currentPage() {
    return coachStore.context(this.$store).state.currentPage;
  }
  get pageCount() {
    return coachStore.context(this.$store).state.pageCount;
  }
  get coaches() {
    return coachStore.context(this.$store).state.coaches;
  }
  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  get [LOAD_ALL_COACHES]() {
    return () => coachStore.context(this.$store).dispatch(LOAD_ALL_COACHES, { page: this.page, filter: this.filter });
  }

  @Watch("selectedUser") onSelectedUserChanged() {
    this.newCoach.organizationUserId = this.selectedUser.length ? this.selectedUser[0].id : undefined;
  }

  mounted() {
    this.load();
  }
  load() {
    this[LOAD_ALL_COACHES]();
  }
  create() {
    this.save(this.newCoach).then((response) => {
      if (response.success) {
        this.newCoach = {};
      }
    });
  }

  save(coach: CoachSaveForm) {
    return Api.coach.save(coach).then((response) => {
      if (response.success) {
        this.load();
      } else {
        this.$notifications.error("Не удалось создать тренера<br>" + response.error);
      }
      return response;
    });
  }

  toggle(id: number) {
    Api.coach.toggle(id).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }

  changePage(page: number) {
    this.page = page;
    this.load();
  }

  applyFilter(filter: Object) {
    this.filter = filter;
    this.load();
  }
  resetFilter(filter: Object) {
    this.filter = filter;
    this.page = 1;
    this.load();
  }

  remove(id: number) {
    Api.coach.delete(id).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }
}
</script>
