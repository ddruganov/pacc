<template>
  <template v-if="resetPasswordSuccess">
    <h3 class="text-center">Пароль успешно сброшен</h3>
    <div class="text-muted text-center">Сейчас Вы будете перенаправлены на страницу авторизации</div>
  </template>
  <template v-else>
    <h3 class="text-center">Сброс пароля</h3>
    <input
      type="password"
      class="form-control form-control-sm mb-1"
      v-model="form.password"
      placeholder="Введите пароль"
    />
    <span class="error text-danger mb-1">{{ errors.password }}</span>
    <input
      type="password"
      class="form-control form-control-sm mb-1"
      v-model="form.repeatPassword"
      placeholder="Повторите пароль"
    />
    <span class="error text-danger mb-1">{{ errors.repeatPassword }}</span>
    <button :disabled="requestCommencing" class="btn btn-primary btn-sm lh-normal" @click="apply()">Применить</button>
  </template>
</template>

<script lang="ts">
import Api from "@/common/api";
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class AuthRestore extends Vue {
  @Prop(String) readonly hash!: string;

  form = {
    hash: "",
    password: "",
    repeatPassword: "",
  };
  resetPasswordSuccess = false;
  errors = [];
  requestCommencing = false;

  mounted() {
    if (!this.hash) {
      return this.$router.push({ path: "/" });
    }
    this.form.hash = this.hash;
  }

  apply() {
    this.errors = [];

    this.requestCommencing = true;
    Api.auth
      .resetPassword(this.form)
      .then((response) => {
        this.resetPasswordSuccess = response.success;
        if (response.data.errors) {
          this.errors = response.data.errors;
          return;
        }

        !this.resetPasswordSuccess && this.$notifications.error(response.error);

        setTimeout(() => {
          this.$router.push({ path: "/" });
        }, 3000);
      })
      .finally(() => {
        this.requestCommencing = false;
      });
  }
}
</script>
