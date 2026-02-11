<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/axios.ts'
import type { Task } from '@/models/Task'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Select from 'primevue/select'
import { useToast } from 'primevue/usetoast'
import { TaskStatus } from '@/models/Task'

const props = defineProps({
  itemId: Number,
})

const emit = defineEmits(['submit'])

interface FormModel {
  title: string,
  status: TaskStatus,
}

const model = ref<FormModel>()

const statusOptions = [
  {
    name: 'Новая',
    value: 'new',
  },
  {
    name: 'В процессе',
    value: 'in_progress',
  },
  {
    name: 'Завершена',
    value: 'done',
  },
]

const toast = useToast()

const getItem = async () => {
  const { data } = await api.get<Task>(`/api/tasks/${props.itemId}`)

  model.value = data
}

const submit = async () => {
  try {
    const { data } = await api.post<Task>(`/api/tasks/${props.itemId}`, model.value)
  
    toast.add({ severity: 'success', summary: 'Готово!', detail: 'Задача обновлена', life: 3000 })
  
    emit('submit')
  } catch (error) {
    if (error.status == 422) {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Проверьте правильность заполненных полей', life: 3000 })
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Попробуйте ещё раз', life: 3000 })
    }
  }
}

onMounted(async () => {
  await getItem()
})
</script>


<template>
<div class="flex flex-col gap-4">
  <div class="flex flex-col gap-2">
    <label>Название</label>
    <InputText type="text" v-model="model.title" />
  </div>
  <div class="flex flex-col gap-2">
    <label>Статус</label>
    <Select
      v-model="model.status"
      :options="statusOptions"
      optionLabel="name"
      optionValue="value"
      placeholder="Выбрать статус"
      class="w-full md:w-56"
      fluid
    />
  </div>
  <Button label="Сохранить" @click="submit"/>
</div>
</template>