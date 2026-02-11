<script setup lang="ts">
import { ref } from 'vue'
import api from '@/api/axios.ts'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { useToast } from 'primevue/usetoast'
import type { Task } from '@/models/Task'


interface FormModel {
  title: string
}

const model = ref<FormModel>()

const emit = defineEmits(['submit'])

const toast = useToast()

const submit = async () => {
  try {
    const { data } = await api.post<Task>(`/api/tasks`, model.value)
  
    toast.add({ severity: 'success', summary: 'Готово!', detail: 'Задача создана', life: 3000 })
  
    emit('submit')
  } catch (error) {
    console.log(error)
    if (error.status == 422) {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Проверьте правильность заполненных полей', life: 3000 })
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Попробуйте ещё раз', life: 3000 })
    }
  }
}
</script>

<template>
<div class="flex flex-col gap-4">
  <div class="flex flex-col gap-2">
    <label>Название</label>
    <InputText type="text" v-model="model.title" />
  </div>
  <Button label="Сохранить" @click="submit"/>
</div>
</template>