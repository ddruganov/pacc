import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";
import Pass from "@/types/pass/Pass";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class PassState {
  passes: Pass[] = [];
  currentPage: number = 0;
  pageCount: number = 0;
}

// Getters
class PassGetters extends Getters<PassState> {}

// Actions
export const LOAD_ALL_PASSES = "loadAllPasses";
class PassActions extends Actions<PassState, PassGetters, PassMutations, PassActions> {
  [LOAD_ALL_PASSES](payload: { page: number; filter: Object }) {
    return Api.pass.getAll(payload.page, payload.filter).then((response) => {
      this.commit(SET_ALL_PASSES, response);
      return response;
    });
  }
}

// Mutations
export const SET_ALL_PASSES = "setAllPasses";
class PassMutations extends Mutations<PassState> {
  [SET_ALL_PASSES](payload: ApiResponse) {
    this.state.currentPage = payload.data.currentPage;
    this.state.pageCount = payload.data.pageCount;
    this.state.passes = payload.data.models;
  }
}

// Create a module with module asset classes
export const passStore = new Module({
  namespaced: true,
  state: PassState,
  getters: PassGetters,
  actions: PassActions,
  mutations: PassMutations,
});
