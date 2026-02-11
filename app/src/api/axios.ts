import axios from 'axios'

const api = axios.create({
  baseURL: 'https://app.local',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
  },
})

export default api