<script setup lang="ts">
import api from '@/api/axios.ts'
import { onMounted, ref } from 'vue'
import { useToast } from 'primevue/usetoast'

import type { Task } from '@/models/Task'
import TaskCreateForm from '@/components/TaskCreateForm.vue'
import TaskEditForm from '@/components/TaskEditForm.vue'
import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Drawer from 'primevue/drawer'

const tasks = ref<Task[]>([])

const showCreateForm = ref<boolean>(false)

const showEditForm = ref<boolean>(false)

const editItemId = ref<number | null>()

const fetchTasks = async () => {
  const { data } = await api.get<Task[]>('/api/tasks')

  tasks.value = data
}

const toast = useToast()

const deleteItem = async (id: number) => {
  try {
    const { data } = await api.delete(`/api/tasks/${id}`)
  
    toast.add({ severity: 'success', summary: 'Готово!', detail: 'Задача удалена', life: 3000 })

    fetchTasks()
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка!', detail: 'Не удалось удалить задачу', life: 3000 })
  }
}

onMounted(async () => {
  await fetchTasks()
})
</script>

<template>
<div>
  <h1 class="text-[32px]">Задачи</h1>

  <div class="mt-3">
    <Button label="Создать новую" @click="showCreateForm = true"/>

    <DataTable v-if="tasks.length" :value="tasks" tableStyle="min-width: 50rem" class="mt-3">
      <Column field="id" header="ID"></Column>
      <Column field="title" header="Название"></Column>
      <Column field="status" header="Статус"></Column>
      <Column field="created_at" header="Дата создания"></Column>
      <Column header="Действие">
        <template #body="slotProps">
          <div class="flex gap-2">
            <Button
              label="Редактировать"
              @click="editItemId = slotProps.data.id; showEditForm = true"
              size="small"
            />
            <Button
              label="Удалить"
              @click="deleteItem(slotProps.data.id)"
              severity="danger"
              size="small"
            />
          </div>
        </template>
      </Column>
    </DataTable>

    <div v-else class="mt-3">У вас пока нет задачь</div>
  </div>

  <Drawer v-model:visible="showCreateForm" header="Создать задачу" position="right" class="!w-full md:!w-80 lg:!w-[30rem]">
    <TaskCreateForm
      v-if="showCreateForm == true"
      @submit="showCreateForm = false; fetchTasks()"
    />
  </Drawer>

  <Drawer v-model:visible="showEditForm" header="Редактировать задачу" position="right" class="!w-full md:!w-80 lg:!w-[30rem]" @close="editItemId = null">
    <TaskEditForm
      v-if="showEditForm == true"
      :item-id="editItemId"
      @submit="editItemId = null; showEditForm = false; fetchTasks()"
    />
  </Drawer>
</div>
</template>