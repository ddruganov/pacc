<template>
  <div class="d-flex align-items-center bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Клиенты</h3>
    <a href="#" @click.prevent="createClient()" class="btn btn-primary btn-sm ms-3 h-fit-content">
      <i class="fas fa-plus" />
    </a>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <client-filter @apply="applyFilter" @reset="resetFilter" />
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
        <tr v-for="client in clients" :key="client.id">
          <td>{{ client.id }}</td>
          <td>{{ client.name || "Безымянный клиент" }}</td>
          <td>{{ client.email }}</td>
          <td>{{ client.creationDate }}</td>
          <td>
            <div class="controls d-flex justify-content-end">
              <a :href="`/client/${client.id}/edit`" class="btn btn-sm btn-primary">
                <i class="fas fa-pencil-alt" />
              </a>
              <a :href="`/client/${client.id}/passInstances`" class="btn btn-sm btn-primary ms-1">
                <i class="fas fa-ticket-alt" />
              </a>
              <a :href="`/client/${client.id}/visits`" class="btn btn-sm btn-primary ms-1">
                <i class="far fa-calendar" />
              </a>
              <button class="btn btn-sm btn-danger ms-1" @click="deleteClient(client.id)">
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
import { clientStore, LOAD_ALL_CLIENTS } from "@/store/modules/client.store";
import Pagination from "@/components/Pagination.vue";
import ClientFilter from "@/components/filters/ClientFilter.vue";
import { Options, Vue } from "vue-class-component";
import Api from "@/common/api";

@Options({
  components: { Pagination, ClientFilter },
})
export default class ClientIndex extends Vue {
  page = 1;
  filter = {};

  get currentPage() {
    return clientStore.context(this.$store).state.currentPage;
  }
  get pageCount() {
    return clientStore.context(this.$store).state.pageCount;
  }
  get clients() {
    return clientStore.context(this.$store).state.clients;
  }

  get [LOAD_ALL_CLIENTS]() {
    return () => clientStore.context(this.$store).dispatch(LOAD_ALL_CLIENTS, { page: this.page, filter: this.filter });
  }

  load() {
    this[LOAD_ALL_CLIENTS]();
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

  createClient() {
    Api.client.save().then((response) => {
      response.success
        ? this.$router.push({ path: `/client/${response.data.id}/edit` })
        : this.$notifications.error(response.error);
    });
  }

  deleteClient(id: number) {
    Api.client.delete(id).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }
}
</script>
