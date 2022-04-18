import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";
import Coach from "@/types/coach/Coach";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class CoachState {
  coaches: Coach[] = [];
  pageCount: number = 0;
  currentPage: number = 1;
}

// Getters
class CoachGetters extends Getters<CoachState> {}

// Actions
export const LOAD_ALL_COACHES = "loadAllCoaches";
class CoachActions extends Actions<CoachState, CoachGetters, CoachMutations, CoachActions> {
  [LOAD_ALL_COACHES](payload: { page: number; filter: Object }) {
    return Api.coach.getAll(payload.page, payload.filter).then((response) => {
      this.commit(SET_ALL_COACHES, response);
      return response;
    });
  }
}

// Mutations
export const SET_ALL_COACHES = "setAllCoaches";
class CoachMutations extends Mutations<CoachState> {
  [SET_ALL_COACHES](payload: ApiResponse) {
    this.state.coaches = payload.data.models;
    this.state.pageCount = payload.data.pageCount;
    this.state.currentPage = payload.data.currentPage;
  }
}

// Create a module with module asset classes
export const coachStore = new Module({
  namespaced: true,
  state: CoachState,
  getters: CoachGetters,
  actions: CoachActions,
  mutations: CoachMutations,
});
