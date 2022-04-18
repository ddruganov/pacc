import Api from "@/common/api";
import ApiResponse from "@/types/api/ApiResponse";
import Client from "@/types/client/Client";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class ClientState {
  clients: Client[] = [];
  pageCount: number = 0;
  currentPage: number = 1;
}

// Getters
class ClientGetters extends Getters<ClientState> {}

// Actions
export const LOAD_ALL_CLIENTS = "loadAllClients";
class ClientActions extends Actions<ClientState, ClientGetters, ClientMutations, ClientActions> {
  [LOAD_ALL_CLIENTS](payload: { page: number; filter: Object }) {
    return Api.client.getAll(payload.page, payload.filter).then((response) => {
      this.commit(SET_ALL_CLIENTS, response);
      return response;
    });
  }
}

// Mutations
export const SET_ALL_CLIENTS = "setAllClients";
class ClientMutations extends Mutations<ClientState> {
  [SET_ALL_CLIENTS](payload: ApiResponse) {
    this.state.currentPage = payload.data.currentPage;
    this.state.pageCount = payload.data.pageCount;
    this.state.clients = payload.data.models;
  }
}

// Create a module with module asset classes
export const clientStore = new Module({
  namespaced: true,
  state: ClientState,
  getters: ClientGetters,
  actions: ClientActions,
  mutations: ClientMutations,
});
