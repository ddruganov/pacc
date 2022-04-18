import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";
import User from "@/types/auth/User";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class StaffState {
  staff: User[] = [];
  pageCount: number = 0;
  currentPage: number = 1;
}

// Getters
class StaffGetters extends Getters<StaffState> {}

// Actions
export const LOAD_ALL_STAFF = "loadAllStaff";
class StaffActions extends Actions<StaffState, StaffGetters, StaffMutations, StaffActions> {
  [LOAD_ALL_STAFF](payload: { page: number; filter: Object }) {
    return Api.staff.getAll(payload.page, payload.filter).then((response) => {
      this.commit(SET_ALL_STAFF, response);
      return response;
    });
  }
}

// Mutations
export const SET_ALL_STAFF = "setAllStaff";
class StaffMutations extends Mutations<StaffState> {
  [SET_ALL_STAFF](payload: ApiResponse) {
    this.state.currentPage = payload.data.currentPage;
    this.state.pageCount = payload.data.pageCount;
    this.state.staff = payload.data.models;
  }
}

// Create a module with module asset classes
export const staffStore = new Module({
  namespaced: true,
  state: StaffState,
  getters: StaffGetters,
  actions: StaffActions,
  mutations: StaffMutations,
});
