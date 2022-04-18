<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <div class="d-flex align-items-center justify-content-between">
      <input
        class="form-control form-control-lg p-0 border-0"
        v-model="client.name"
        :minlength="1"
        :maxlength="50"
        @change="save('name')"
        role="button"
        placeholder="Введите имя клиента"
      />
    </div>
    <div class="d-flex align-items-center justify-content-between">
      <go-back link="/clients" />
      <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
    </div>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3 mb-5">
    <!-- EDIT FORM -->
    <div class="edit">
      <div class="mb-3">
        <span>Телефон:</span>
        <input
          class="form-control form-control-sm"
          type="tel"
          placeholder="8xxxxxxxxxx"
          pattern="^8[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$"
          v-model="client.email"
          :minlength="11"
          :maxlength="11"
          @input="onEmailInput"
          @change="save('email')"
        />
        <div class="error" v-if="errors.email">
          {{ errors.email }}
        </div>
      </div>
      <div>
        <span>Заметка:</span>
        <input class="form-control form-control-sm" v-model="client.note" @change="save('note')" />
        <div class="error" v-if="errors.note">
          {{ errors.note }}
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Client from "@/types/client/Client";
import Api from "@/common/api";
import { Options, Vue } from "vue-class-component";
import GoBack from "@/components/GoBack.vue";
import SaveIndicator from "@/components/SaveIndicator.vue";

@Options({
  components: { GoBack, SaveIndicator },
})
export default class ClientEdit extends Vue {
  client: Client = {
    name: "",
    email: "",
  };
  errors = [];
  currentlySaving = 0;
  currentlySavingSuccess = false;
  get organizationClientId() {
    return Number(this.$route.params.organizationClientId);
  }

  mounted() {
    this.load();
  }
  load() {
    Api.client.getOne(this.organizationClientId).then((response) => {
      if (response.success) {
        this.client = response.data;
      } else {
        this.$notifications.error(response.error);
      }
    });
  }
  save(field: string) {
    this.currentlySaving++;
    this.errors = [];
    Api.client
      .save({
        id: this.client.id,
        [field]: this.client[field],
      })
      .then((response) => {
        this.currentlySavingSuccess = response.success;

        if (response.data.errors) {
          this.errors = response.data.errors;
        }
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }
}
</script>
