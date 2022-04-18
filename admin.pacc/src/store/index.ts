import { createStore, Module } from "vuex-smart-module";
import { activityStore } from "@/store/modules/activity.store";
import { authStore } from "@/store/modules/auth.store";
import { calendarStore } from "@/store/modules/calendar.store";
import { clientStore } from "@/store/modules/client.store";
import { coachStore } from "@/store/modules/coach.store";
import { newsStore } from "@/store/modules/news.store";
import { passStore } from "@/store/modules/pass.store";
import { requestStore } from "@/store/modules/request.store";
import { staffStore } from "@/store/modules/staff.store";

export const store = createStore(
  new Module({
    modules: {
      activity: activityStore,
      auth: authStore,
      calendar: calendarStore,
      client: clientStore,
      coach: coachStore,
      news: newsStore,
      pass: passStore,
      request: requestStore,
      staff: staffStore,
    },
  })
);
