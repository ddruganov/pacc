<template>
  <template v-if="verificationSuccess === null">
    <h3 class="text-center mb-3">Производится верификация</h3>
    <div class="spinner-border text-primary mx-auto" />
  </template>
  <template v-else>
    <template v-if="verificationSuccess">
      <h3 class="text-center text-success">Верификация успешно пройдена</h3>
      <div class="text-muted text-center">
        Подождите, пока идёт перенаправление на главную страницу
      </div>
    </template>
    <template v-else>
      <h3 class="text-center text-danger">Ошибка верификации</h3>
    </template>
  </template>
</template>

<script lang="ts">
import Api from "@/common/api";
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class VerifyEmail extends Vue {
  @Prop(String) readonly hash!: string;

  verificationSuccess: boolean | null = null;

  mounted() {
    Api.auth.verifyEmail(this.hash).then((response) => {
      if (response.success) {
        this.verificationSuccess = true;
        setTimeout(() => {
          this.$router.push({ path: "/" });
        }, 2000);
        return;
      }

      this.$notifications.error(response.error, "", { timeout: -1 });
    });
  }
}
</script>
