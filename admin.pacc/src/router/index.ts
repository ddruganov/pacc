import { createRouter, createWebHistory, RouteParams, RouteRecordRaw } from "vue-router";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    component: () => import("@/layout/Main.vue"),
    children: [
      /* HOME */
      {
        path: "/",
        component: () => import("@/pages/home/Index.vue"),
      },

      /* ACTIVITY */
      {
        path: "/activities",
        component: () => import("@/pages/activity/Index.vue"),
      },

      /* CALENDAR */
      {
        path: "/calendar",
        component: () => import("@/pages/calendar/Index.vue"),
      },

      /* CLIENT */
      {
        path: "/clients",
        component: () => import("@/pages/client/Index.vue"),
      },
      {
        path: "/client/create",
        component: () => import("@/pages/client/Edit.vue"),
      },
      {
        path: "/client/:organizationClientId/edit",
        component: () => import("@/pages/client/Edit.vue"),
      },
      {
        path: "/client/:organizationClientId/passInstances",
        component: () => import("@/pages/client/PassInstances.vue"),
      },
      {
        path: "/client/:organizationClientId/visits",
        component: () => import("@/pages/client/Visits.vue"),
      },

      /* COACHES */
      {
        path: "/coaches",
        component: () => import("@/pages/coach/Index.vue"),
      },

      /* NEWS */
      {
        path: "/news",
        component: () => import("@/pages/news/Index.vue"),
      },
      {
        path: "/news/create",
        component: () => import("@/pages/news/Edit.vue"),
      },
      {
        path: "/news/:articleId/edit",
        component: () => import("@/pages/news/Edit.vue"),
      },

      /* PASS */
      {
        path: "/passes",
        component: () => import("@/pages/pass/Index.vue"),
      },
      {
        path: "/pass/create",
        component: () => import("@/pages/pass/Edit.vue"),
      },
      {
        path: "/pass/:passId/edit",
        component: () => import("@/pages/pass/Edit.vue"),
      },

      /* SETTINGS */
      {
        path: "/settings",
        component: () => import("@/pages/settings/Index.vue"),
        children: [
          {
            path: "/settings/general",
            component: () => import("@/pages/settings/tabs/General.vue"),
          },
          {
            path: "/settings/security",
            component: () => import("@/pages/settings/tabs/Security.vue"),
          },
        ],
      },

      /* STAFF */
      {
        path: "/staff",
        component: () => import("@/pages/staff/Index.vue"),
      },
      {
        path: "/staff/create",
        component: () => import("@/pages/staff/Edit.vue"),
      },
      {
        path: "/staff/:userId/edit",
        component: () => import("@/pages/staff/Edit.vue"),
      },

      /* STATISTICS */
      {
        path: "/stats",
        component: () => import("@/pages/stats/Index.vue"),
      },
      {
        path: "/stats/:statId/edit",
        component: () => import("@/pages/stats/Edit.vue"),
      },
      {
        path: "/stats/:statId/view",
        component: () => import("@/pages/stats/View.vue"),
      },
    ],
  },
  /* AUTHENTICATION */
  {
    path: "/auth",
    component: () => import("@/layout/Auth.vue"),
    children: [
      {
        path: "/auth/login",
        component: () => import("@/pages/auth/Login.vue"),
        props: (route: RouteParams) => ({ backurl: (route.query as any).backurl }),
      },
      {
        path: "/auth/register",
        component: () => import("@/pages/auth/Register.vue"),
        props: (route: RouteParams) => ({ hash: (route.query as any).hash }),
      },
      {
        path: "/auth/restore",
        component: () => import("@/pages/auth/Restore.vue"),
      },
      {
        path: "/auth/resetPassword",
        component: () => import("@/pages/auth/ResetPassword.vue"),
        props: (route: RouteParams) => ({ hash: (route.query as any).hash }),
      },
      {
        path: "/auth/logout",
        component: () => import("@/pages/auth/Logout.vue"),
      },
      {
        path: "/auth/awaitVerification",
        component: () => import("@/pages/auth/AwaitVerification.vue"),
      },
      {
        path: "/auth/verifyEmail",
        component: () => import("@/pages/auth/VerifyEmail.vue"),
        props: (route: RouteParams) => ({ hash: (route.query as any).hash }),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
