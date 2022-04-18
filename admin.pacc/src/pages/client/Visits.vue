<template>
  <template v-if="client.id">
    <div class="bg-white rounded-10 p-3 mt-5">
      <div class="d-flex flex-column">
        <h3 class="m-0">Детализация посещений клиента {{ client.name }}</h3>
        <go-back link="/clients" />
      </div>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <table class="table m-0">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Абонемент</th>
            <th scope="col">Дата</th>
            <th scope="col">Использовано часов</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="visit in visits" :key="visit.id">
            <td class="mx-3 mb-3">
              {{ visit.id }}
            </td>
            <td class="mx-3 mb-3">{{ visit.passInstanceName }}</td>
            <td class="mx-3 mb-3">
              {{ visit.datetime }}
            </td>
            <td class="mx-3 mb-3">
              {{ visit.hoursSpent }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
</template>

<script lang="ts">
import Api from "@/common/api";
import GoBack from "@/components/GoBack.vue";
import Client from "@/types/client/Client";
import Visit from "@/types/client/Visit";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { GoBack },
})
export default class ClientVisits extends Vue {
  client: Client = {
    id: undefined,
    name: "",
    email: "",
  };
  visits: Visit[] = [];
  get organizationClientId() {
    return Number(this.$route.params.organizationClientId);
  }

  mounted() {
    this.load();
  }
  load() {
    this.loadClient();
    this.loadVisits();
  }
  loadClient() {
    Api.client.getOne(this.organizationClientId).then((response) => {
      if (response.success) {
        this.client = response.data;
      } else {
        this.$notifications.error(response.error);
      }
    });
  }
  loadVisits() {
    Api.client.getVisits(this.organizationClientId).then((response) => {
      if (response.success) {
        this.visits = response.data;
      } else {
        this.$notifications.error(response.error);
      }
    });
  }
}
</script>
