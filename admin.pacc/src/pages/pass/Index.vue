<template>
  <div class="d-flex align-items-center bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Абонементы</h3>
    <a href="#" @click.prevent="createPass" class="btn btn-primary btn-sm ms-3 h-fit-content">
      <i class="fas fa-plus" />
    </a>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <pass-filter @apply="applyFilter" @reset="resetFilter" />
  </div>

  <div class="bg-white rounded-10 p-3 my-3">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Название</th>
          <th scope="col">Цена</th>
          <th scope="col">Количество часов</th>
          <th scope="col">Срок действия</th>
          <th scope="col">Вид активности</th>
          <th scope="col">Активен</th>
          <th scope="col" class="text-end">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pass in passes" :key="pass.id">
          <td>{{ pass.id }}</td>
          <td>
            <a :href="`/pass/${pass.id}/edit`" class="link-primary">
              {{ pass.name }}
            </a>
          </td>
          <td>{{ pass.price }}</td>
          <td>{{ pass.hours }}</td>
          <td>{{ pass.expiresIn }}</td>
          <td>{{ pass.activityName }}</td>
          <td>
            <div @click="toggle(pass.id)" class="pass-toggle rounded" role="button">
              <i v-if="pass.active" class="fas fa-check text-success" />
              <i v-else class="fas fa-times text-danger" />
            </div>
          </td>
          <td>
            <div class="controls d-flex justify-content-end">
              <button class="btn btn-sm btn-danger" @click="remove(pass.id)">
                <i class="far fa-trash-alt" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <pagination v-if="pageCount > 1" :pageCount="pageCount" :currentPage="currentPage" @change="changePage" />
</template>

<style lang="scss">
.pass-toggle {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  &:hover {
    background-color: #eee;
  }
}
</style>

<script lang="ts">
import Api from "@/common/api";
import { LOAD_ALL_PASSES } from "@/store/modules/pass.store";
import Pagination from "@/components/Pagination.vue";
import PassFilter from "../../components/filters/PassFilter.vue";
import { Options, Vue } from "vue-class-component";
import { passStore } from "@/store/modules/pass.store";
import CustomSwitch from "@/components/CustomSwitch.vue";

@Options({
  components: { Pagination, PassFilter, CustomSwitch },
})
export default class PassIndex extends Vue {
  page = 1;
  filter = {};
  get currentPage() {
    return passStore.context(this.$store).state.currentPage;
  }
  get pageCount() {
    return passStore.context(this.$store).state.pageCount;
  }
  get passes() {
    return passStore.context(this.$store).state.passes;
  }

  load() {
    passStore.context(this.$store).dispatch(LOAD_ALL_PASSES, { page: this.page, filter: this.filter });
  }
  toggle(id: number) {
    Api.pass.toggle(id).then((response) => {
      response.success ? this.load() : this.$notifications.error("Ошибка сохранения!");
    });
  }
  remove(id: number) {
    Api.pass.delete(id).then((response) => {
      response.success ? this.load() : this.$notifications.error("Ошибка удаления абонемента!");
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

  createPass() {
    Api.pass.save().then((response) => {
      response.success
        ? this.$router.push({ path: `/pass/${response.data.id}/edit` })
        : this.$notifications.error("Ошибка создания абонемента");
    });
  }
}
</script>
