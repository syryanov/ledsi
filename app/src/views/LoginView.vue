<script setup lang="ts">
import { ref } from 'vue' 
import useAuthStore from '@/stores/auth'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import { useToast } from 'primevue/usetoast'
import { useRouter } from 'vue-router'

interface Credentials {
  email: string
  password: string
}

const model = ref<Credentials>({
  email: '',
  password: '',
})

const authStore = useAuthStore()

const toast = useToast()

const router = useRouter()

const submit = async () => {
  await authStore.login(model.value,
  () => {
    toast.add({ severity: 'success', summary: 'Добро пожаловать!', detail: 'Вы успешно авторизовались', life: 3000 })

    router.push('/')
  }, 
  (error: any) => {
    if (error.status == 422) {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Неверный логин или пароль', life: 3000 })
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Попробуйте ещё раз', life: 3000 })
    }
  })
}

</script>

<template>
<div class="w-full h-full flex items-center justify-center">
  <div class="flex flex-col min-w-[300px]">
    <h1 class="text-[32px] mb-4 text-center">Авторизация</h1>
    <div class="mt-3 flex flex-col gap-2">
      <div class="flex flex-col gap-2">
        <label>Эл.почта</label>
        <InputText type="text" v-model="model.email" fluid />
      </div>
      <div class="flex flex-col gap-2">
        <label>Пароль</label>
        <Password v-model="model.password" :feedback="false" fluid class="w-full" />
      </div>
      <Button label="Войти" class="mt-4" fluid @click="submit()"/>
    </div>
  </div>
</div>
</template>