<script setup lang="ts">
import useAuthStore from '@/stores/auth'
import { useToast } from 'primevue/usetoast'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()

const toast = useToast()

const router = useRouter()

const submit = () => {
  authStore.logout(
    () => {
      router.push('/auth')
    },
    () => {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Попробуйте ещё раз', life: 3000 })
    }
  )
}
</script>


<template>
<div>
  <div class="cursor-pointer w-fit" @click="submit">Выйти</div>
</div>
</template>