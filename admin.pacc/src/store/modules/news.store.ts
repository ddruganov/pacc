import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";
import Article from "@/types/news/Article";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class NewsState {
  articles: Article[] = [];
  currentPage: number = 1;
  pageCount: number = 0;
}

// Getters
class NewsGetters extends Getters<NewsState> {}

// Actions
export const LOAD_ALL_ARTICLES = "loadAllArticles";
class NewsActions extends Actions<NewsState, NewsGetters, NewsMutations, NewsActions> {
  [LOAD_ALL_ARTICLES](payload: { page: number; filter: Object }) {
    return Api.news.getAll(payload.page, payload.filter).then((response) => this.commit(SET_ALL_ARTICLES, response));
  }
}

// Mutations
export const SET_ALL_ARTICLES = "setAllArticles";
class NewsMutations extends Mutations<NewsState> {
  [SET_ALL_ARTICLES](payload: ApiResponse) {
    this.state.currentPage = payload.data.currentPage;
    this.state.pageCount = payload.data.pageCount;
    this.state.articles = payload.data.models;
  }
}

// Create a module with module asset classes
export const newsStore = new Module({
  namespaced: true,
  state: NewsState,
  getters: NewsGetters,
  actions: NewsActions,
  mutations: NewsMutations,
});
