<script setup lang="ts">
import { ref } from 'vue'
import Logout from '@/components/Logout.vue'

interface MenuItem {
  link: string,
  title: string,
}

const menu = ref<MenuItem[]>([
  {
    link: '/',
    title: 'Главная',
  },
  {
    link: '/tasks',
    title: 'Задачи',
  },
  {
    link: '/stats',
    title: 'Статистика',
  },
])
</script>

<template>
<div class="h-screen bg-gray-100">
  <div class="flex w-full h-full">
    <div class="w-[300px] bg-gray-800 p-8">
      <div class="flex flex-col gap-4 text-white">
        <router-link
          v-for="item in menu"
          :key="item.link"
          :to="item.link"
        >
        {{ item.title }}
        </router-link>
      </div>
    </div>
    <div class="w-full">
      <div class="w-full bg-white p-4">
        <Logout />
      </div>
      <div class="p-8">
        <slot />
      </div>
    </div>
  </div>
</div>
</template>