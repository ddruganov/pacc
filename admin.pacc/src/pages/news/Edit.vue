<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <input
      class="form-control border-0 p-0 m-0"
      v-model="article.title"
      @change="save()"
      role="button"
      style="font-size: calc(1.3rem + .6vw);"
      placeholder="Введите название статьи"
    />

    <div class="d-flex align-items-center justify-content-between">
      <go-back link="/news" />
      <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
    </div>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3 mb-5">
    <!-- EDIT FORM -->
    <div class="edit">
      <div class="mb-3">
        <span>Содержание:</span>
        <textarea
          class="form-control form-control-sm"
          v-model="article.contents"
          placeholder="Введите содержание статьи"
          @change="save()"
        />
        <div class="error">
          {{ errors.contents }}
        </div>
      </div>

      <div class="d-flex align-items-center mb-3">
        <span>Период отображения</span>
        <div class="ms-3">
          <input
            class="form-control form-control-sm w-auto"
            type="date"
            v-model="article.creationDate"
            @change="save()"
          />
          <div class="error">
            {{ errors.creation_date }}
          </div>
        </div>
        <span class="mx-3">–</span>
        <div>
          <input
            class="form-control form-control-sm w-auto"
            type="date"
            v-model="article.expirationDate"
            @change="save()"
          />
          <div class="error">
            {{ errors.expiration_date }}
          </div>
        </div>
      </div>

      <custom-switch v-model="article.active" :size="16" label="Активна" @change="save()" />
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import CustomSwitch from "@/components/CustomSwitch.vue";
import GoBack from "@/components/GoBack.vue";
import SaveIndicator from "@/components/SaveIndicator.vue";
import Article from "@/types/news/Article";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { GoBack, SaveIndicator, CustomSwitch },
})
export default class NewsEdit extends Vue {
  article: Article = {
    creationDate: "",
    expirationDate: "",
    active: false,
    title: "",
    contents: "",
  };
  errors = [];
  currentlySaving = 0;
  currentlySavingSuccess = false;

  get articleId() {
    return Number(this.$route.params.articleId);
  }

  mounted() {
    Api.news.getOne(this.articleId).then((response) => {
      if (response.success) {
        this.article = response.data;
      } else {
        this.$router.push({ path: "/news" });
      }
    });
  }
  save() {
    this.currentlySaving++;
    Api.news
      .save(this.article)
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        if (response.success) {
          this.$router.push({ path: "/news" });
        } else {
          this.errors = response.data.errors;
        }
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }
}
</script>
