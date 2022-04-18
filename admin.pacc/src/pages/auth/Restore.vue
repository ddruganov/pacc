<template>
  <h3 class="text-center">Восстановление пароля</h3>

  <template v-if="emailSent">
    <div class="text-center mb-1">На Вашу почту было выслано письмо со ссылкой для смены пароля.</div>
    <div class="text-center mb-1">Ссылка будет действительна в течение одного дня.</div>
    <div class="text-center">Эту страницу можно закрыть.</div>
  </template>
  <template v-else>
    <input type="email" class="form-control mb-1" placeholder="Введите ваш email" v-model="email" />
    <button class="btn btn-block btn-primary lh-normal w-100" @click="restore()">Восстановить</button>
  </template>

  <div class="d-flex align-items-center justify-content-between mt-3">
    <a href="/auth/register" class="link-primary">Регистрация</a>
    <a href="/auth/login" class="link-primary">Вход</a>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import { authStore } from "@/store/modules/auth.store";
import { Vue } from "vue-class-component";

export default class AuthRestore extends Vue {
  email = "";
  emailSent = false;

  get isAuthenticated() {
    return authStore.context(this.$store).getters.isAuthenticated;
  }

  restore() {
    Api.auth.restore(this.email).then((response) => {
      this.emailSent = response.success;
      !response.success && this.$notifications.error(response.error);
    });
  }
}
</script>
