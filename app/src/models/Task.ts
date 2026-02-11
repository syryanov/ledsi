export type TaskStatus = 'new' | 'in_progress' | 'done'

export interface Task {
  id: number
  title: string
  status: TaskStatus
  created_at: string
  updated_at: string
}