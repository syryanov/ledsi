<script setup lang="ts">
import { computed, type Component } from 'vue'
import { useRoute } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import EmptyLayout from '@/layouts/EmptyLayout.vue'
import Toast from 'primevue/toast'

const route = useRoute()

const layout = computed<Component>(() => {
  if (route.meta.layout == 'DefaultLayout') {
    return DefaultLayout
  }

  if (route.meta.layout == 'EmptyLayout') {
    return EmptyLayout
  }
})
</script>

<template>
<div>
  <component :is="layout">
    <router-view />
  </component>
  <Toast />
</div>
</template>

<style scoped></style>
