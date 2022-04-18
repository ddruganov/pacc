import { Getters, Mutations, Actions, Module } from "vuex-smart-module";
import CalendarEntry from "@/types/calendar/CalendarEntry";
import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";

// State
class CalendarState {
  entries: CalendarEntry[] = [];
}

// Getters
class CalendarGetters extends Getters<CalendarState> {}

// Actions
export const LOAD_ALL_CALENDAR_ENTRIES = "loadAllCalendarEntries";
class CalendarActions extends Actions<CalendarState, CalendarGetters, CalendarMutations, CalendarActions> {
  [LOAD_ALL_CALENDAR_ENTRIES](filter: Object) {
    return Api.calendar.getEntries(filter).then((response) => {
      this.commit(SET_ALL_CALENDAR_ENTRIES, response);
      return response;
    });
  }
}

// Mutations
export const SET_ALL_CALENDAR_ENTRIES = "setAllCalendarEntries";
class CalendarMutations extends Mutations<CalendarState> {
  [SET_ALL_CALENDAR_ENTRIES](payload: ApiResponse) {
    this.state.entries = payload.data;
  }
}

// Create a module with module asset classes
export const calendarStore = new Module({
  namespaced: true,
  state: CalendarState,
  getters: CalendarGetters,
  actions: CalendarActions,
  mutations: CalendarMutations,
});
