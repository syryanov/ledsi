<script setup lang="ts">
import { onMounted, ref } from 'vue'
import api from '@/api/axios.ts'
import type { Stats } from '@/models/Stats'
import type { User } from '@/models/User'
import Select from 'primevue/select'
import useAuthStore from '@/stores/auth'

const stats = ref<Stats>({})

const filterUserId = ref<number | null>()

const fetchStats = async () => {
  const { data } = await api.get<Stats>(`/api/stats${filterUserId.value ? '?user_id=' + filterUserId.value : ''}`)

  stats.value = data
}

onMounted(async () => {
  await fetchStats()
})

const usersOptions = ref<User[]>([])

const authStore = useAuthStore()

const getUsers = async () => {
  if (usersOptions.value.length) {
    return
  }

  const { data } = await api.get<User[]>('/api/users')

  usersOptions.value = data
}
</script>

<template>
<div>
  <h1 class="text-[32px]">Статистика</h1>
  
  <div class="mt-3">
    <div v-if="authStore.user.role == 'admin'" class="mb-3">
      <div class="flex flex-col gap-2">
        <label>Пользователь</label>
        <Select
          v-model="filterUserId"
          :options="usersOptions"
          optionLabel="email"
          optionValue="id"
          placeholder="Все"
          showClear
          class="w-full md:w-56"
          @click="getUsers()"
          @update:modelValue="fetchStats"
        />
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

      <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <p class="text-sm font-medium text-gray-500">Всего</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ stats.total }}</p>
      </div>

      <div class="bg-blue-50 rounded-2xl shadow p-6 border border-blue-100">
        <p class="text-sm font-medium text-blue-600">Новых</p>
        <p class="mt-2 text-3xl font-bold text-blue-900">{{ stats.new }}</p>
      </div>

      <div class="bg-yellow-50 rounded-2xl shadow p-6 border border-yellow-100">
        <p class="text-sm font-medium text-yellow-600">В процессе</p>
        <p class="mt-2 text-3xl font-bold text-yellow-900">{{ stats.in_progress }}</p>
      </div>

      <div class="bg-green-50 rounded-2xl shadow p-6 border border-green-100">
        <p class="text-sm font-medium text-green-600">Завершенных</p>
        <p class="mt-2 text-3xl font-bold text-green-900">{{ stats.done }}</p>
      </div>
    </div>
  </div>
</div>
</template>

<style scoped></style>