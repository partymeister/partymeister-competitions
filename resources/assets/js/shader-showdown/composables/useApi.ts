export interface Competition {
  id: number
  name: string
  competition_type: string
  sort_position: number
  voting_enabled: boolean
  live_voting_enabled: boolean
  entry_count: number
}

export interface EntryWithVotes {
  id: number
  title: string
  author: string
  sort_position: number
  status: number
  votes: number
}

export interface CompetitionDetail {
  id: number
  name: string
  competition_type: string
  sort_position: number
  voting_enabled: boolean
  live_voting_enabled: boolean
  entries: EntryWithVotes[]
  total_votes: number
}

const STORAGE_KEY = 'shader-showdown-token'
const BASE_URL_KEY = 'shader-showdown-base-url'

export function getStoredToken(): string | null {
  return localStorage.getItem(STORAGE_KEY)
}

export function storeToken(token: string): void {
  localStorage.setItem(STORAGE_KEY, token)
}

export function getStoredBaseUrl(): string {
  return localStorage.getItem(BASE_URL_KEY) || window.location.origin
}

export function storeBaseUrl(url: string): void {
  localStorage.setItem(BASE_URL_KEY, url)
}

export function clearAuth(): void {
  localStorage.removeItem(STORAGE_KEY)
  localStorage.removeItem(BASE_URL_KEY)
}

function baseUrl(): string {
  return getStoredBaseUrl().replace(/\/+$/, '')
}

function headers(): HeadersInit {
  return {
    'X-Shader-Token': getStoredToken() ?? '',
    Accept: 'application/json',
    'Content-Type': 'application/json',
  }
}

async function request<T>(method: string, path: string, body?: unknown): Promise<T> {
  const url = `${baseUrl()}${path}`
  const init: RequestInit = { method, headers: headers() }
  if (body !== undefined) {
    init.body = JSON.stringify(body)
  }

  const response = await fetch(url, init)
  if (!response.ok) {
    const text = await response.text().catch(() => 'Unknown error')
    throw new Error(`API ${method} ${path} failed (${response.status}): ${text}`)
  }

  if (response.status === 204) return undefined as T
  return response.json() as Promise<T>
}

export function useApi() {
  async function fetchCompetitions(): Promise<Competition[]> {
    const res = await request<{ data: Competition[] }>('GET', '/api/shader-showdown/competitions')
    return res.data
  }

  async function fetchCompetition(id: number): Promise<CompetitionDetail> {
    const res = await request<{ data: CompetitionDetail }>('GET', `/api/shader-showdown/competitions/${id}`)
    return res.data
  }

  async function startLiveVoting(id: number): Promise<string> {
    const res = await request<{ message: string }>('POST', `/api/shader-showdown/competitions/${id}/start`)
    return res.message
  }

  async function stopLiveVoting(id: number): Promise<string> {
    const res = await request<{ message: string }>('POST', `/api/shader-showdown/competitions/${id}/stop`)
    return res.message
  }

  return { fetchCompetitions, fetchCompetition, startLiveVoting, stopLiveVoting }
}
