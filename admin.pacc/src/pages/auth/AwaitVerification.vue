<template>
  <h3 class="text-center">Ожидается верификация</h3>
  <div class="text-center text-muted mb-1">
    На Вашу почту было выслано письмо.
  </div>
  <div class="text-center text-muted">
    Пожалуйста, пройдите по ссылке в этом письме, чтобы завершить регистрацию.
  </div>
  <a href="/" class="link-primary mt-3 text-center">я уже прошёл по ссылке</a>
  <a href="/auth/logout" class="link-primary mt-3 text-center">войти под другим аккаунтом</a>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class AwaitVerification extends Vue {}
</script>
