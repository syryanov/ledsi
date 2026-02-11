import api from '@/api/axios.ts'
import { defineStore } from 'pinia'

interface User {
  id: number
  email: string
  name: string
  role: 'customer' | 'admin'
}

interface Credentials {
  email: string,
  password: string
}

export default defineStore('auth', {
  state: () => ({
    user: null as User | null,
    isAuth: false as boolean,
    checked: false as boolean,
  }),
  actions: {
    async login(credentials: Credentials, successCallback: any, errorCallback: any) {
      try {
        await api.get('/sanctum/csrf-cookie')

        const { data } = await api.post<User>('/login', credentials)

        this.setUser(data)
        this.setIsAuth(true)
        this.setChecked(true)

        successCallback()
      } catch (error) {
        errorCallback(error)
      }
    },

    async logout(successCallback: any, errorCallback: any) {
      try {
        await api.post('/logout')

        this.setUser(null)
        this.setIsAuth(false)

        successCallback()
      } catch (error) {
        errorCallback(error)
      }
    },

    async check(errorCallback: any) {
      try {
        const { data } = await api.get<User>('/api/user')

        this.setUser(data)
        this.setIsAuth(true)
        this.setChecked(true)
      } catch (error) {
        errorCallback(error)
      }
    },

    setUser(user: User | null) {
      this.user = user
    },

    setIsAuth(value: boolean) {
      this.isAuth = value
    },

    setChecked(value: boolean) {
      this.checked = value
    }
  }
})
