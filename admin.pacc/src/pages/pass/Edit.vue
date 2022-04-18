<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <input
      class="form-control border-0 p-0 m-0"
      v-model="pass.name"
      @change="save()"
      role="button"
      style="font-size: calc(1.3rem + .6vw);"
      placeholder="Введите название абонемента"
    />
    <div class="d-flex align-items-center justify-content-between">
      <go-back link="/passes" />
      <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
    </div>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3 mb-5">
    <!-- EDIT FORM -->
    <div class="edit">
      <div class="mb-3">
        <span>Цена:</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="pass.price"
          :min="0"
          @change="save()"
          placeholder="Введите цену"
        />
        <div class="error" v-if="errors.price">
          {{ errors.price }}
        </div>
      </div>
      <div class="mb-3">
        <span>Кол-во часов:</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="pass.hours"
          :min="1"
          @change="save()"
          placeholder="Введите количество часов"
        />
        <div class="error" v-if="errors.hours">
          {{ errors.hours }}
        </div>
      </div>
      <div class="mb-3">
        <span>Срок действия:</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="pass.expiresIn"
          :min="1"
          @change="save()"
          placeholder="Введите количество дней"
        />
        <div class="error" v-if="errors.expires_in">
          {{ errors.expires_in }}
        </div>
      </div>
      <div class="mb-3">
        <span>Вид активности:</span>
        <select class="form-control form-control-sm" v-model="pass.activityId" @change="save()">
          <option :value="null" disabled="true">Выберите вид активности</option>
          <option v-for="activity in activities" :key="activity.id" :value="activity.id">
            {{ activity.name }}
          </option>
        </select>
      </div>
      <custom-switch v-model="pass.active" @change="save()" :size="16" label="Активен" />
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import CustomSwitch from "@/components/CustomSwitch.vue";
import GoBack from "@/components/GoBack.vue";
import SaveIndicator from "@/components/SaveIndicator.vue";
import { activityStore } from "@/store/modules/activity.store";
import PassSaveForm from "@/types/pass/PassSaveForm";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { GoBack, SaveIndicator, CustomSwitch },
})
export default class PassEdit extends Vue {
  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  get passId() {
    return Number(this.$route.params.passId);
  }
  pass: PassSaveForm = {
    name: "",
    hours: 0,
    price: 0,
    active: true,
    expiresIn: 0,
    activityId: 0,
  };
  errors = [];
  currentlySaving = 0;
  currentlySavingSuccess = false;

  mounted() {
    Api.pass
      .getOne(this.passId)
      .then((response) => {
        if (response.success) {
          this.pass = response.data;
        } else {
          this.$router.push({ path: "/passes" });
        }
      })
      .catch((e) => e);
  }
  save() {
    this.currentlySaving++;
    Api.pass
      .save(this.pass)
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        if (!response.success) {
          this.errors = response.data.errors;
        }
      })
      .catch((e) => e)
      .finally(() => {
        this.currentlySaving--;
      });
  }
}
</script>
