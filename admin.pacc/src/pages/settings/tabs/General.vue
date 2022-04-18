<template>
  <div class="bg-white rounded-10 d-flex flex-column p-3">
    <div class="d-flex align-items-center justify-content-between mb-1">
      <h3 class="m-0">Организация</h3>
      <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
    </div>

    <input
      type="text"
      class="form-control form-control-sm mb-3"
      v-model="organization.name"
      placeholder="Название организации"
      @change="saveOrganizationProperty('name')"
    />

    <input
      type="text"
      class="form-control form-control-sm mb-3"
      v-model="organization.description"
      placeholder="Описание организации"
      @change="saveOrganizationProperty('description')"
    />

    <image-uploader v-model="organization.logo" @change="saveOrganizationProperty('logo')" />
  </div>
</template>

<script lang="ts">
import Api from "@/common/api/index";
import { GET_CURRENT_USER } from "@/store/modules/auth.store";
import ImageUploader from "@/components/ImageUploader.vue";
import UserOrganization from "@/types/auth/userOrganization/UserOrganization";
import { Options, Vue } from "vue-class-component";
import { authStore } from "@/store/modules/auth.store";
import SaveIndicator from "@/components/SaveIndicator.vue";

@Options({
  components: { ImageUploader, SaveIndicator },
})
export default class GeneralSettings extends Vue {
  organization: UserOrganization = {
    id: 0,
    name: "",
    logo: {},
    description: "",
    inviteLink: "",
  };
  organizationErrors = [];
  currentlySaving = 0;
  currentlySavingSuccess = false;
  get currentOrganization() {
    return authStore.context(this.$store).getters.currentOrganization;
  }

  mounted() {
    this.load();
  }
  load() {
    Api.settings.organization.getInfo().then((response) => {
      this.organization = response.data;
    });
  }
  saveOrganizationProperty(propertyName: string) {
    this.currentlySaving++;
    Api.settings.organization
      .save({
        id: this.organization.id,
        [propertyName]: this.organization[propertyName],
      })
      .then((response) => {
        this.currentlySavingSuccess = response.success;

        response.success ? authStore.context(this.$store).dispatch(GET_CURRENT_USER) : this.load();

        if (response.data.errors) {
          this.organizationErrors = response.data.errors;
        }
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }
}
</script>
