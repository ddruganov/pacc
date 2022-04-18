import Api from "@/common/api";
import Activity from "@/types/activity/Activity";
import ApiResponse from "@/types/api/ApiResponse";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class ActivityState {
  activities: Activity[] = [];
}

// Getters
class ActivityGetters extends Getters<ActivityState> {
  get activities() {
    return this.state.activities;
  }
}

// Actions
export const LOAD_ACTIVITIES = "loadActivities";
class ActivityActions extends Actions<ActivityState, ActivityGetters, ActivityMutations, ActivityActions> {
  [LOAD_ACTIVITIES]() {
    return Api.activity
      .getAll()
      .then((response) => this.commit(SET_ACTIVITIES, response))
      .catch((e: Error) => e);
  }
}

// Mutations
export const SET_ACTIVITIES = "setActivities";
class ActivityMutations extends Mutations<ActivityState> {
  [SET_ACTIVITIES](payload: ApiResponse) {
    this.state.activities = payload.data;
  }
}

// Create a module with module asset classes
export const activityStore = new Module({
  namespaced: true,
  state: ActivityState,
  getters: ActivityGetters,
  actions: ActivityActions,
  mutations: ActivityMutations,
});
