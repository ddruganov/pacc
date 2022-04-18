import config from "@/config";
import { stringify as dataToQueryString } from "querystring";

import router from "@/router/index";
import { store } from "@/store";
import { BEGIN_HTTP_REQUEST, END_HTTP_REQUEST, requestStore } from "@/store/modules/request.store";
import { authStore, SET_AUTHENTICATED, SET_VERIFIED } from "@/store/modules/auth.store";
import ApiResponse from "@/types/api/ApiResponse";
import RequestParams from "@/types/api/RequestParams";
import RequestOptions from "@/types/api/RequestOptions";

export default class Requestor {
  static REQUEST_METHODS = {
    GET: "GET",
    POST: "POST",
  };

  static get(url: string, query?: any, options?: RequestOptions) {
    return Requestor.makeRequest(Requestor.REQUEST_METHODS.GET, url, query, options);
  }

  static post(url: string, form?: any, options?: RequestOptions) {
    return Requestor.makeRequest(Requestor.REQUEST_METHODS.POST, url, form, options);
  }

  static makeRequest(
    method: string,
    url: string,
    data: any,
    options: RequestOptions = new RequestOptions()
  ): Promise<ApiResponse> {
    const params: RequestParams = {
      method: method,
      headers: {
        OrganizationId: String(authStore.context(store).getters.userOrganizations.find((uo) => uo.isDefault)?.id),
      },
      credentials: "include",
    };

    if (options.stringify) {
      params.headers = {
        ...params.headers,
        "Content-Type": "application/json",
      };
    }

    if (method === Requestor.REQUEST_METHODS.GET) {
      url += "?" + dataToQueryString(data);
    } else if (method === Requestor.REQUEST_METHODS.POST) {
      params.body = options?.stringify ? JSON.stringify(data) : data;
    } else {
      throw new Error("Unknown RequestMethod: " + method);
    }

    !options.ignoredByState &&
      requestStore.context(store).dispatch(BEGIN_HTTP_REQUEST, { id: url, cancelToken: undefined });

    return fetch(config.host + url, params)
      .then((response) => response.json())
      .then((response: ApiResponse) => {
        if (Object.prototype.hasOwnProperty.call(response, "code")) {
          switch (response.code) {
            case 400:
              router.push({ path: "/404" });
              break;
            case 401:
              authStore.context(store).dispatch(SET_AUTHENTICATED, false);
              router.push({ path: "/auth/login" });
              break;
            case 402:
              authStore.context(store).dispatch(SET_VERIFIED, false);
              router.push({ path: "/auth/awaitVerification" });
              break;
          }
        }

        return response;
      })
      .finally(() => {
        !options.ignoredByState && requestStore.context(store).dispatch(END_HTTP_REQUEST, url);
      });
  }
}
