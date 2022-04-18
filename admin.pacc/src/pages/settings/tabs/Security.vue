<template>
  <div class="bg-white rounded-10 d-flex flex-column p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-1">
      <span>Пароль</span>
      <save-indicator :count="passwordSaveRequest" :success="passwordSaveRequestSuccess" />
    </div>
    <input
      type="password"
      class="form-control form-control-sm mb-1"
      v-model="password.password"
      placeholder="Введите пароль"
    />
    <span class="error text-danger mb-1">{{ passwordErrors.password }}</span>
    <input
      type="password"
      class="form-control form-control-sm mb-1"
      v-model="password.repeatPassword"
      placeholder="Повторите пароль"
    />
    <span class="error text-danger mb-1">{{ passwordErrors.repeatPassword }}</span>
    <button class="btn btn-primary btn-sm lh-normal" @click="savePassword">Применить</button>
  </div>

  <div v-if="tokens.length" class="bg-white rounded-10 d-flex flex-column p-3">
    <div class="mb-1">Ваши устройства</div>
    <div v-for="(token, i) in tokens" :key="token.id" class="d-flex align-items-center">
      <span class="me-3">{{ i + 1 }}.</span>
      <i :class="isMobile(token.userAgent) ? 'fas fa-mobile-alt' : 'fas fa-desktop'" />
      <span class="ms-3">Дата входа: {{ token.issueDate }}</span>
      <button class="btn btn-sm btn-danger ms-auto" @click.prevent="removeToken(token.id)">
        <i class="far fa-trash-alt" />
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import SaveIndicator from "@/components/SaveIndicator.vue";
import PasswordSaveForm from "@/types/settings/PasswordSaveForm";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { SaveIndicator },
})
export default class SecuritySettings extends Vue {
  password: PasswordSaveForm = {
    password: "",
    repeatPassword: "",
  };
  passwordErrors = [];
  passwordSaveRequest = 0;
  passwordSaveRequestSuccess = false;

  tokens = [];

  mounted() {
    this.load();
  }
  load() {
    Api.settings.token.getAll().then((response) => {
      this.tokens = response.data;
    });
  }
  isMobile(userAgent: string) {
    return ["iEmail", "iPad", "Android"].some((i) => userAgent.includes(i));
  }
  removeToken(tokenId: number) {
    Api.settings.token.remove(tokenId).then((response) => {
      response.success ? this.load() : this.$notifications.error("Ошибка закрытия сессии.<br>" + response.error);
    });
  }
  savePassword() {
    this.passwordSaveRequest++;
    Api.settings
      .savePassword(this.password)
      .then((response) => {
        this.passwordSaveRequestSuccess = response.success;
        if (response.success) {
          this.resetPasswordForm();
        } else {
          this.passwordErrors = response.data.errors;
        }
      })
      .finally(() => {
        this.passwordSaveRequest--;
      });
  }
  resetPasswordForm() {
    this.password = {
      password: "",
      repeatPassword: "",
    };
    this.passwordErrors = [];
  }
}
</script>
