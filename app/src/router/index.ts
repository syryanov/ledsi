import { createRouter, createWebHistory } from 'vue-router'
import IndexView from '@/views/IndexView.vue'
import useAuthStore from '@/stores/auth'


const routes = [
  { 
    path: '/',
    name: 'Index',
    component: IndexView,
    meta: { layout: 'DefaultLayout', requiresAuth: true },
  },
  { 
    path: '/auth',
    name: 'Login',
    component: () => import('@/views/LoginView.vue'),
    meta: { layout: 'EmptyLayout' },
  },
  { 
    path: '/tasks',
    name: 'Tasks',
    component: () => import('@/views/TasksView.vue'),
    meta: { layout: 'DefaultLayout', requiresAuth: true },
  },
  { path: '/stats',
    name: 'Stats',
    component: () => import('@/views/StatsView.vue'),
    meta: { layout: 'DefaultLayout', requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (!authStore.checked) {
    await authStore.check(
      (error: any) => {
        console.log(error)
      }
    )
  }
  
  if (to.meta.requiresAuth && !authStore.isAuth) {
    next('/auth')
  } else if (authStore.isAuth && to.path === '/auth') {
    next('/')
  } else {
    next()
  }
})

export default router
