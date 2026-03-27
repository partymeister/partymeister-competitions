<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import {
  useApi,
  getStoredToken,
  storeToken,
  storeBaseUrl,
  getStoredBaseUrl,
  clearAuth,
  type Competition,
  type CompetitionDetail,
} from './composables/useApi'

const api = useApi()

// Auth state
const authenticated = ref(false)
const tokenInput = ref('')
const baseUrlInput = ref(getStoredBaseUrl())
const showAdvanced = ref(false)
const authError = ref('')
const authLoading = ref(false)

// App state
const competitions = ref<CompetitionDetail[]>([])
const loading = ref(true)
const error = ref('')
const busyCompetitions = ref<Set<number>>(new Set())
const lastRefresh = ref<Date | null>(null)
const toasts = ref<Array<{ id: number; message: string; type: 'success' | 'error' }>>([])

let refreshInterval: ReturnType<typeof setInterval> | null = null
let toastId = 0

// Computed
const qualifiers = computed(() =>
  competitions.value
    .filter(c => c.competition_type === 'Shader Showdown')
    .sort((a, b) => a.sort_position - b.sort_position)
)

const finals = computed(() =>
  competitions.value
    .filter(c => c.competition_type === 'Shader Showdown Final')
    .sort((a, b) => a.sort_position - b.sort_position)
)

const allCompetitions = computed(() => [...qualifiers.value, ...finals.value])

const activeLiveCount = computed(() =>
  allCompetitions.value.filter(c => c.live_voting_enabled).length
)

// Rank helpers — entries come pre-sorted by votes from API, but we still
// need to assign rank numbers (ties share the same rank)
interface RankedEntry {
  id: number
  title: string
  votes: number
  rank: number
}

function rankedEntries(comp: CompetitionDetail): RankedEntry[] {
  const entries = (comp.entries ?? []).map(e => ({
    id: e.id,
    title: e.title,
    votes: e.votes,
    rank: 0,
  }))
  // Already sorted by votes DESC from API, assign ranks with tie handling
  let currentRank = 1
  for (let i = 0; i < entries.length; i++) {
    if (i > 0 && entries[i].votes < entries[i - 1].votes) {
      currentRank = i + 1
    }
    entries[i].rank = currentRank
  }
  return entries
}

function rankClass(rank: number): string {
  if (rank === 1) return 'rank-1'
  if (rank === 2) return 'rank-2'
  if (rank === 3) return 'rank-3'
  return ''
}

function isBusy(id: number): boolean {
  return busyCompetitions.value.has(id)
}

function addToast(message: string, type: 'success' | 'error') {
  const id = ++toastId
  toasts.value.push({ id, message, type })
  setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, 4000)
}

function formatTime(date: Date): string {
  return date.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
}

// Auth
async function connect() {
  authError.value = ''
  authLoading.value = true

  storeToken(tokenInput.value.trim())
  storeBaseUrl(baseUrlInput.value.trim() || window.location.origin)

  try {
    // Test the token by fetching competitions
    await api.fetchCompetitions()
    authenticated.value = true
    await loadData()
    loading.value = false
    refreshInterval = setInterval(refreshData, 30_000)
  } catch (e: any) {
    clearAuth()
    if (e.message.includes('401')) {
      authError.value = 'Invalid token'
    } else if (e.message.includes('503')) {
      authError.value = 'Token not configured on server'
    } else {
      authError.value = e.message || 'Connection failed'
    }
  } finally {
    authLoading.value = false
  }
}

function disconnect() {
  if (refreshInterval) clearInterval(refreshInterval)
  refreshInterval = null
  clearAuth()
  authenticated.value = false
  tokenInput.value = ''
  competitions.value = []
}

// Data loading
async function loadData() {
  try {
    const compList = await api.fetchCompetitions()
    const details = await Promise.all(
      compList.map(c => api.fetchCompetition(c.id))
    )
    competitions.value = details
    lastRefresh.value = new Date()
    error.value = ''
  } catch (e: any) {
    error.value = e.message || 'Failed to load data'
  }
}

async function refreshData() {
  try {
    const compList = await api.fetchCompetitions()
    const details = await Promise.all(
      compList.map(c => api.fetchCompetition(c.id))
    )
    competitions.value = details
    lastRefresh.value = new Date()
  } catch (e: any) {
    console.error('Refresh failed:', e)
  }
}

async function startLiveVoting(comp: CompetitionDetail) {
  if (isBusy(comp.id)) return
  busyCompetitions.value.add(comp.id)

  try {
    const msg = await api.startLiveVoting(comp.id)
    addToast(msg, 'success')
    await loadData()
  } catch (e: any) {
    addToast(`Failed to start: ${e.message}`, 'error')
  } finally {
    busyCompetitions.value.delete(comp.id)
  }
}

async function stopLiveVoting(comp: CompetitionDetail) {
  if (isBusy(comp.id)) return
  busyCompetitions.value.add(comp.id)

  try {
    const msg = await api.stopLiveVoting(comp.id)
    addToast(msg, 'success')
    await loadData()
  } catch (e: any) {
    addToast(`Failed to stop: ${e.message}`, 'error')
  } finally {
    busyCompetitions.value.delete(comp.id)
  }
}

// Lifecycle — auto-connect if token exists
onMounted(async () => {
  const existing = getStoredToken()
  if (existing) {
    tokenInput.value = existing
    await connect()
  }
})

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval)
})
</script>

<template>
  <div class="shader-showdown-app">
    <!-- Header -->
    <header class="app-header">
      <h1>Shader Showdown</h1>
      <div class="subtitle">
        Live Voting Control Panel
        <button v-if="authenticated" class="change-token" @click="disconnect">
          Change token
        </button>
      </div>
    </header>

    <!-- Token screen -->
    <div v-if="!authenticated" class="token-screen">
      <h2>Connect</h2>
      <p>Enter your Shader Showdown API token to get started.</p>
      <form class="token-form" @submit.prevent="connect">
        <input
          v-model="tokenInput"
          type="password"
          class="token-input"
          placeholder="Paste token here"
          autocomplete="off"
        />
        <button
          type="button"
          class="advanced-toggle"
          @click="showAdvanced = !showAdvanced"
        >
          {{ showAdvanced ? 'Hide' : 'Show' }} server URL
        </button>
        <input
          v-if="showAdvanced"
          v-model="baseUrlInput"
          type="url"
          class="token-input"
          placeholder="https://pm.revision-party.net"
        />
        <div v-if="authError" class="token-error">{{ authError }}</div>
        <button
          type="submit"
          class="btn btn-start"
          :disabled="!tokenInput.trim() || authLoading"
        >
          {{ authLoading ? 'Connecting...' : 'Connect' }}
        </button>
      </form>
    </div>

    <!-- Authenticated content -->
    <template v-else>
      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <div>Loading competitions...</div>
      </div>

      <!-- Error -->
      <div v-else-if="error && !allCompetitions.length" class="error-state">
        <div>{{ error }}</div>
        <button class="btn btn-start" style="margin-top: 16px; flex: none;" @click="loadData">
          Retry
        </button>
      </div>

      <!-- Main content -->
      <template v-else>
        <!-- Status bar -->
        <div class="status-bar">
          <div v-if="activeLiveCount > 0" class="status-pill live">
            <span class="dot"></span>
            LIVE
          </div>
          <div v-else class="status-pill">
            <span class="dot"></span>
            No voting active
          </div>
        </div>

        <div v-if="!allCompetitions.length" class="loading-state">
          No Shader Showdown competitions found.
        </div>

        <!-- Qualifiers -->
        <template v-if="qualifiers.length">
          <div class="competitions-grid">
            <div
              v-for="comp in qualifiers"
              :key="comp.id"
              class="competition-card"
              :class="{ 'is-live': comp.live_voting_enabled }"
            >
              <div class="card-header">
                <div class="card-title">{{ comp.name }}</div>
                <span v-if="comp.live_voting_enabled" class="card-badge live">Live</span>
                <span v-else class="card-badge qualifier">Qualifier</span>
              </div>

              <ul class="entries-list">
                <li
                  v-for="entry in rankedEntries(comp)"
                  :key="entry.id"
                  class="entry-item"
                  :class="rankClass(entry.rank)"
                >
                  <div class="entry-rank">{{ entry.rank }}</div>
                  <div class="entry-info">
                    <span class="entry-title">{{ entry.title || 'Untitled' }}</span>
                  </div>
                  <div class="entry-votes">{{ entry.votes }}</div>
                </li>
                <li v-if="!comp.entries?.length" class="entry-item">
                  <div class="entry-info"><span class="entry-title">No entries yet</span></div>
                </li>
              </ul>

              <div class="vote-summary">
                <span class="vote-summary-label">Total votes</span>
                <span class="vote-summary-count">{{ comp.total_votes }}</span>
              </div>

              <div class="card-actions">
                <button
                  v-if="!comp.live_voting_enabled"
                  class="btn btn-start"
                  :disabled="isBusy(comp.id)"
                  @click="startLiveVoting(comp)"
                >
                  {{ isBusy(comp.id) ? 'Starting...' : 'Start Live Voting' }}
                </button>
                <button
                  v-else
                  class="btn btn-stop"
                  :disabled="isBusy(comp.id)"
                  @click="stopLiveVoting(comp)"
                >
                  {{ isBusy(comp.id) ? 'Stopping...' : 'Stop Live Voting' }}
                </button>
              </div>
            </div>
          </div>
        </template>

        <!-- Finals -->
        <template v-if="finals.length">
          <div class="competitions-grid">
            <div
              v-for="comp in finals"
              :key="comp.id"
              class="competition-card is-final"
              :class="{ 'is-live': comp.live_voting_enabled }"
            >
              <div class="card-header">
                <div class="card-title">{{ comp.name }}</div>
                <span v-if="comp.live_voting_enabled" class="card-badge live">Live</span>
                <span v-else class="card-badge final">Final</span>
              </div>

              <ul class="entries-list">
                <li
                  v-for="entry in rankedEntries(comp)"
                  :key="entry.id"
                  class="entry-item"
                  :class="rankClass(entry.rank)"
                >
                  <div class="entry-rank">{{ entry.rank }}</div>
                  <div class="entry-info">
                    <span class="entry-title">{{ entry.title || 'Untitled' }}</span>
                  </div>
                  <div class="entry-votes">{{ entry.votes }}</div>
                </li>
                <li v-if="!comp.entries?.length" class="entry-item">
                  <div class="entry-info"><span class="entry-title">No entries yet</span></div>
                </li>
              </ul>

              <div class="vote-summary">
                <span class="vote-summary-label">Total votes</span>
                <span class="vote-summary-count">{{ comp.total_votes }}</span>
              </div>

              <div class="card-actions">
                <button
                  v-if="!comp.live_voting_enabled"
                  class="btn btn-start"
                  :disabled="isBusy(comp.id)"
                  @click="startLiveVoting(comp)"
                >
                  {{ isBusy(comp.id) ? 'Starting...' : 'Start Live Voting' }}
                </button>
                <button
                  v-else
                  class="btn btn-stop"
                  :disabled="isBusy(comp.id)"
                  @click="stopLiveVoting(comp)"
                >
                  {{ isBusy(comp.id) ? 'Stopping...' : 'Stop Live Voting' }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </template>

      <!-- Last refresh -->
      <div v-if="lastRefresh" class="refresh-indicator">
        Vote counts refresh every 30s &middot; Last update: {{ formatTime(lastRefresh) }}
      </div>
    </template>

    <!-- Toasts -->
    <div class="toast-container">
      <div v-for="toast in toasts" :key="toast.id" class="toast" :class="toast.type">
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>
