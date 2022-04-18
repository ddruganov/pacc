<template>
  <div class="d-flex flex-column bg-white rounded-10 p-3 mt-5">
    <div class="d-flex align-items-center justify-content-between">
      <h3 class="m-0">
        {{ userId ? "Редактирование" : "Создание" }} пользователя <span v-if="userId">#{{ userId }}</span>
      </h3>
      <button class="btn btn-primary btn-sm lh-normal" @click="save"><i class="far fa-save" /></button>
    </div>
    <go-back link="/staff" />
  </div>

  <div class="bg-white rounded-10 p-3 mt-3 mb-5">
    <div v-if="createUserFromEmail" class="text-muted">
      Пользователь с таким номером телефона уже существует (у вас или у другой организации), поэтому данные будут
      заполнены автоматически.
    </div>
    <!-- EDIT FORM -->
    <div class="edit">
      <div>
        <span>Имя:</span>
        <input
          class="form-control form-control-sm"
          v-model="user.name"
          :minlength="1"
          :maxlength="50"
          :disabled="createUserFromEmail"
        />
        <div class="error" v-if="errors.name">
          {{ errors.name }}
        </div>
      </div>
      <div>
        <span>Телефон:</span>
        <input
          class="form-control form-control-sm"
          type="tel"
          placeholder="8xxxxxxxxxx"
          pattern="^8[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$"
          v-model="user.email"
          @input="onEmailInput"
        />
        <div class="error" v-if="errors.email">
          {{ errors.email }}
        </div>
      </div>
      <div v-if="!userId && !createUserFromEmail">
        <span>Пароль:</span>
        <input class="form-control form-control-sm" v-model="user.password" />
        <div class="error" v-if="errors.password">
          {{ errors.password }}
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import GoBack from "@/components/GoBack.vue";
import User from "@/types/auth/User";
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

@Options({
  components: { GoBack },
})
export default class StaffEdit extends Vue {
  @Prop(Number) readonly userId!: number;

  user: User = {};
  errors = [];
  createUserFromEmail = false;

  mounted() {
    this.userId &&
      Api.staff.getOne(this.userId).then((response) => {
        if (response.success) {
          this.user = response.data;
        } else {
          this.$router.push({ path: "/staff" });
        }
      });
  }
  save() {
    Api.staff.save(this.user).then((response) => {
      if (response.success) {
        this.$router.push({ path: "/staff" });
      } else {
        this.errors = response.data.errors;
      }
    });
  }
}
</script>
