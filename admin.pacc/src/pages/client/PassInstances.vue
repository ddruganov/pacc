<template>
  <template v-if="client">
    <div class="bg-white rounded-10 p-3 mt-5">
      <div class="d-flex flex-column">
        <h3 class="m-0">Абонементы клиента {{ client.name }}</h3>
        <go-back link="/clients" />
      </div>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <custom-remote-select
        modelName="pass"
        promptText="Выберите абонемент"
        :maxModelCount="1"
        class="w-100 mb-1"
        v-model="selectedPasses"
      />
      <custom-remote-select
        modelName="coach"
        promptText="Выберите тренера"
        :maxModelCount="1"
        class="w-100 mb-1"
        v-model="selectedCoaches"
      />
      <button @click="createPassInstance()" :disabled="!selectedPasses.length" class="btn btn-primary btn-sm lh-normal">
        Привязать
      </button>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <div class="text-muted" style="font-size: 12px">
        <span>Сейчас показываются</span>
        <a class="mx-1 link-primary" href="javascript:;" @click="unexpiredOnly = !unexpiredOnly">
          {{ unexpiredOnly ? "только действующие" : "все" }}
        </a>
        <span>привязанные абонементы</span>
      </div>
    </div>

    <div class="bg-white rounded-10 p-3 my-3">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Название</th>
            <!-- <th scope="col">Количество часов</th> -->
            <!-- <th scope="col">Цена</th> -->
            <!-- <th scope="col">Дата создания</th> -->
            <th scope="col">Дата окончания</th>
            <!-- <th scope="col">Вид активности</th> -->
            <th scope="col">Осталось часов</th>
            <th scope="col">Дата последнего посещения</th>
            <th scope="col">Тренер</th>
            <th scope="col" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="passInstance in passInstances" :key="passInstance.id">
            <td>{{ passInstance.id }}</td>
            <td>{{ passInstance.name }}</td>
            <!-- <td>{{ passInstance.hours }}</td> -->
            <!-- <td>{{ passInstance.price }}</td> -->
            <!-- <td>{{ passInstance.creationDate }}</td> -->
            <td>{{ passInstance.expirationDate }}</td>
            <!-- <td>{{ passInstance.activityName }}</td> -->
            <td>{{ passInstance.hours - passInstance.hoursSpent }}</td>
            <td>
              <a
                v-if="passInstance.lastVisitDate"
                :href="`/client/${organizationClientId}/visits`"
                class="link-primary"
              >
                {{ passInstance.lastVisitDate }}
              </a>
              <span v-else>—</span>
            </td>
            <td>{{ passInstance.coachName || "—" }}</td>
            <td>
              <div class="controls d-flex justify-content-end">
                <button class="btn btn-primary btn-sm lh-normal" @click="visit(passInstance.id)">
                  Проставить посещение
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination v-if="pageCount > 1" :pageCount="pageCount" :currentPage="currentPage" @change="changePage" />
  </template>
</template>

<script lang="ts">
import Api from "@/common/api/index";
import CustomRemoteSelect from "@/components/CustomRemoteSelect.vue";
import Client from "@/types/client/Client";
import { Options, Vue } from "vue-class-component";
import { activityStore } from "@/store/modules/activity.store";
import { Watch } from "vue-property-decorator";
import GoBack from "@/components/GoBack.vue";
import Pagination from "@/components/Pagination.vue";

@Options({
  components: { CustomRemoteSelect, GoBack, Pagination },
})
export default class ClientPassInstances extends Vue {
  client: Client = {
    name: "",
    email: "",
  };
  pageCount = 0;
  currentPage = 0;
  passInstances = [];
  selectedPasses: { id: number; name: string }[] = [];
  coaches = [];
  selectedCoaches: { id: number; name: string }[] = [];

  unexpiredOnly = true;

  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  get organizationClientId() {
    return Number(this.$route.params.organizationClientId);
  }

  @Watch("unexpiredOnly") onUnexpiredOnlyChanged() {
    this.loadPassInstances();
  }

  mounted() {
    this.load();
  }
  load() {
    this.loadClient();
    this.loadPassInstances();
    this.loadCoaches();
  }
  loadClient() {
    Api.client.getOne(this.organizationClientId).then((response) => {
      if (response.success) {
        this.client = response.data;
      } else {
        this.$router.push({ path: "/clients" });
      }
    });
  }
  loadPassInstances() {
    const filter = {
      expirationDate: this.unexpiredOnly ? { from: new Date().toUTCString() } : null,
    };
    Api.client.getPassInstances(this.organizationClientId, filter).then((response) => {
      if (response.success) {
        this.pageCount = response.data.pageCount;
        this.currentPage = response.data.currentPage;
        this.passInstances = response.data.models;
      } else {
        this.$router.push({ path: "/clients" });
      }
    });
  }
  loadCoaches() {
    Api.coach.getAll().then((response) => {
      if (response.success) {
        this.coaches = response.data.models;
      } else {
        this.$router.push({ path: "/clients" });
      }
    });
  }
  createPassInstance() {
    Api.passInstance
      .create({
        organizationClientId: this.organizationClientId,
        passId: this.selectedPasses[0].id,
        coachId: this.selectedCoaches.length ? this.selectedCoaches[0].id : undefined,
      })
      .then((response) => {
        response.success ? this.loadPassInstances() : this.$notifications.error(response.error);
      });
  }
  visit(passInstanceId: number) {
    Api.passInstance
      .visit({
        organizationClientId: this.organizationClientId,
        passInstanceId: passInstanceId,
        hoursSpent: 1,
      })
      .then((response) => {
        response.success ? this.loadPassInstances() : this.$notifications.error(response.error);
      });
  }

  changePage(page: number) {
    this.currentPage = page;
    this.load();
  }
}
</script>
