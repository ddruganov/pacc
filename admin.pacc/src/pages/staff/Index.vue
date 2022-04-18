<template>
  <div class="d-flex bg-white align-items-center rounded-10 p-3 mt-5">
    <h3 class="m-0">Персонал</h3>
    <!-- <a href="/staff/create" class="btn btn-primary btn-sm ms-3 h-fit-content">
      <i class="fas fa-plus" />
    </a> -->
  </div>

  <div class="d-flex flex-column bg-white rounded-10 p-3 mt-3">
    <span class="text-muted"
      >Для приглашения пользователей в свою организацию предложите им пройти по данной ссылке после регистрации:</span
    >
    <clipboard-input :value="currentOrganization.inviteLink" style="max-width: 300px" />
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <staff-filter @apply="applyFilter" @reset="resetFilter" />
  </div>

  <div class="bg-white rounded-10 p-3 my-3">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Имя</th>
          <th scope="col">Телефон</th>
          <th scope="col">Дата регистрации</th>
          <th scope="col" class="text-end">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in staff" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.creationDate }}</td>
          <td>
            <div class="controls d-flex justify-content-end">
              <a :href="'/staff/edit?id=' + user.id" class="btn btn-sm btn-primary">
                <i class="fas fa-pencil-alt" />
              </a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <pagination v-if="pageCount > 1" :pageCount="pageCount" :currentPage="currentPage" @change="changePage" />
</template>

<style lang="scss">
.pill {
  display: flex;
  background-color: red;
  color: white;
  cursor: pointer !important;
  border-radius: 32px;
  padding: 2px 10px;
  &.active {
    background-color: limegreen;
  }
}
</style>

<script lang="ts">
import { LOAD_ALL_STAFF, staffStore } from "@/store/modules/staff.store";
import StaffFilter from "@/components/filters/StaffFilter.vue";
import { Options, Vue } from "vue-class-component";
import Pagination from "@/components/Pagination.vue";
import { authStore } from "@/store/modules/auth.store";
import ClipboardInput from "@/components/ClipboardInput.vue";

@Options({
  components: { StaffFilter, Pagination, ClipboardInput },
})
export default class StaffIndex extends Vue {
  page = 1;
  filter = {};

  get currentPage() {
    return staffStore.context(this.$store).state.currentPage;
  }
  get pageCount() {
    return staffStore.context(this.$store).state.pageCount;
  }
  get staff() {
    return staffStore.context(this.$store).state.staff;
  }
  get currentOrganization() {
    return authStore.context(this.$store).getters.currentOrganization;
  }

  get [LOAD_ALL_STAFF]() {
    return () => staffStore.context(this.$store).dispatch(LOAD_ALL_STAFF, { page: this.page, filter: this.filter });
  }

  load() {
    this[LOAD_ALL_STAFF]();
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

  copyInviteLink() {}
}
</script>
