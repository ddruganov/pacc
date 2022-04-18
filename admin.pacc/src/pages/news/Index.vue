<template>
  <div class="d-flex align-items-center bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Новости</h3>
    <a href="#" @click.prevent="createArticle" class="btn btn-primary btn-sm ms-3">
      <i class="fas fa-plus" />
    </a>
  </div>

  <div class="bg-white rounded-10 p-3 mt-3">
    <news-filter @apply="applyFilter" @reset="resetFilter" />
  </div>

  <template v-if="articles.length">
    <div v-for="article in articles" :key="article.id" class="bg-white rounded-10 my-3 p-3">
      <div class="d-flex align-items-center">
        <a :href="`/news/${article.id}/edit`" class="m-0 h3 link-primary">
          {{ article.title }}
        </a>
        <button class="btn btn-sm btn-danger ms-auto" @click="remove(article.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
      <span class="text-muted">от {{ article.creationDate }}</span>
      <div class="my-3" v-html="article.contents" />
      <div v-if="article.showAfterDate || article.showBeforeDate" class="text-muted">
        <span>Актуально</span>
        <span v-if="article.showAfterDate"> с {{ article.showAfterDate }} </span>
        <span v-if="article.showBeforeDate"> до {{ article.showBeforeDate }} </span>
      </div>
    </div>
  </template>

  <pagination v-if="pageCount > 1" :pageCount="pageCount" :currentPage="currentPage" @change="changePage" />
</template>

<script lang="ts">
import Api from "@/common/api/index";
import { LOAD_ALL_ARTICLES, newsStore } from "@/store/modules/news.store";
import Pagination from "@/components/Pagination.vue";
import NewsFilter from "@/components/filters/NewsFilter.vue";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { Pagination, NewsFilter },
})
export default class NewsIndex extends Vue {
  page = 1;
  filter = {};
  get currentPage() {
    return newsStore.context(this.$store).state.currentPage;
  }
  get pageCount() {
    return newsStore.context(this.$store).state.pageCount;
  }
  get articles() {
    return newsStore.context(this.$store).state.articles;
  }
  get [LOAD_ALL_ARTICLES]() {
    return () => newsStore.context(this.$store).dispatch(LOAD_ALL_ARTICLES, { page: this.page, filter: this.filter });
  }

  mounted() {
    this.load();
  }

  load() {
    this[LOAD_ALL_ARTICLES]();
  }
  changePage(page: number) {
    this.page = page;
    this.load();
  }
  applyFilter(filter: Object) {
    this.filter = filter;
    this.load();
  }
  resetFilter(filter: Object) {
    this.filter = filter;
    this.page = 1;
    this.load();
  }
  remove(id: number) {
    Api.news.remove(id).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }

  createArticle() {
    Api.news.save().then((response) => {
      response.success
        ? this.$router.push({ path: `/news/${response.data.id}/edit` })
        : this.$notifications.error("Ошибка создания статьи");
    });
  }
}
</script>
