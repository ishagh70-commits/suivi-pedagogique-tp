<template>
  <div style="padding:20px">
    <h1>Présences / Absences</h1>

    <p v-if="page.props.flash?.success" class="flash">
      {{ page.props.flash.success }}
    </p>

    <div style="margin: 10px 0; font-weight: bold;">
      ✅ Présents : {{ presentsCount }} | ❌ Absents : {{ absentsCount }}
    </div>

    <!-- Date + Groupe + Actions -->
    <div class="row">
      <label>Date :</label>
      <input type="date" v-model="form.date" />

      <label style="margin-left:12px">Groupe :</label>
      <select v-model="form.group_id">
        <option value="">-- Choisir un groupe --</option>
        <option v-for="g in groups" :key="g.id" :value="g.id">
          {{ g.name }}
        </option>
      </select>

      <button type="button" @click="applyFilters">Afficher</button>

      <!-- ✅ Exports -->
      <button
        type="button"
        class="btn-secondary"
        :disabled="!canExport"
        @click="exportExcel"
        title="Exporter la liste en Excel"
      >
        Export Excel
      </button>

      <button
        type="button"
        class="btn-secondary"
        :disabled="!canExport"
        @click="exportPdf"
        title="Exporter la liste en PDF"
      >
        Export PDF
      </button>
    </div>

    <p v-if="!form.group_id" class="hint">
      Choisis un groupe pour afficher les étudiants.
    </p>

    <!-- Filtre -->
    <div class="row" v-if="form.group_id">
      <label>Filtre :</label>
      <input
        type="text"
        v-model="filter"
        placeholder="Rechercher par ID, nom ou prénom..."
        class="filter"
      />
      <button type="button" @click="clearFilter">Effacer</button>
    </div>

    <!-- Tableau -->
    <form v-if="form.group_id" @submit.prevent="submit">
      <table>
        <thead>
          <tr>
            <th style="width:80px">ID</th>
            <th>Étudiant</th>
            <th style="width:180px">Statut</th>
            <th style="width:160px">Situation</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="e in filteredEtudiants" :key="e.id">
            <td class="center">{{ e.id }}</td>
            <td>{{ e.nom }} {{ e.prenom }}</td>

            <td>
              <select v-model="form.itemsMap[e.id]">
                <option value="present">Présent</option>
                <option value="absent">Absent</option>
              </select>
            </td>

            <td>
              <span :class="form.itemsMap[e.id] === 'present' ? 'badge present' : 'badge absent'">
                {{ form.itemsMap[e.id] === 'present' ? 'Présent' : 'Absent' }}
              </span>
            </td>
          </tr>

          <tr v-if="filteredEtudiants.length === 0">
            <td colspan="4" class="center" style="padding:18px">
              Aucun étudiant ne correspond au filtre.
            </td>
          </tr>
        </tbody>
      </table>

      <button type="submit" style="margin-top:12px" :disabled="!canSave">
        Enregistrer
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
  date: String,
  group_id: [String, Number, null],
  groups: { type: Array, default: () => [] },
  etudiants: { type: Array, default: () => [] },
})

const groups = props.groups

const form = reactive({
  date: props.date,
  group_id: props.group_id ?? '',
  itemsMap: {},
})

function initItemsMap() {
  const map = {}
  props.etudiants.forEach(e => {
    map[e.id] = (e.presences && e.presences[0]) ? e.presences[0].statut : 'present'
  })
  form.itemsMap = map
}

initItemsMap()

watch(
  () => props.etudiants,
  () => initItemsMap()
)

// Filtre
const filter = ref('')

const filteredEtudiants = computed(() => {
  const q = filter.value.trim().toLowerCase()
  if (!q) return props.etudiants

  return props.etudiants.filter(e => {
    const id = String(e.id)
    const fullName = `${e.nom ?? ''} ${e.prenom ?? ''}`.toLowerCase()
    const reverseName = `${e.prenom ?? ''} ${e.nom ?? ''}`.toLowerCase()
    return id.includes(q) || fullName.includes(q) || reverseName.includes(q)
  })
})

function clearFilter() {
  filter.value = ''
}

// Compteurs
const presentsCount = computed(() =>
  filteredEtudiants.value.filter(e => form.itemsMap[e.id] === 'present').length
)

const absentsCount = computed(() =>
  filteredEtudiants.value.filter(e => form.itemsMap[e.id] === 'absent').length
)

const canSave = computed(() => form.group_id && props.etudiants.length > 0)
const canExport = computed(() => form.group_id) // tu peux aussi exiger props.etudiants.length > 0

function applyFilters() {
  router.get(
    '/presences',
    { date: form.date, group_id: form.group_id || null },
    { preserveState: true, preserveScroll: true }
  )
}

// ✅ Exports (ouvre le téléchargement)
function exportExcel() {
  const url = `/presences/export/excel?date=${encodeURIComponent(form.date)}&group_id=${encodeURIComponent(form.group_id)}`
  window.location.href = url
}

function exportPdf() {
  const url = `/presences/export/pdf?date=${encodeURIComponent(form.date)}&group_id=${encodeURIComponent(form.group_id)}`
  window.location.href = url
}

function submit() {
  if (!canSave.value) return
  if (!confirm(`Enregistrer les présences du ${form.date} pour ce groupe ?`)) return

  const items = Object.entries(form.itemsMap).map(([id, statut]) => ({
    etudiant_id: Number(id),
    statut,
  }))

  router.post('/presences', { date: form.date, group_id: form.group_id, items })
}
</script>

<style>
.row{
  margin: 10px 0;
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.filter{
  padding: 6px 10px;
  min-width: 280px;
}

.hint{
  margin: 8px 0 14px;
  color: #666;
}

table {
  border-collapse: collapse;
  background: #fff;
  width: 100%;
  margin-top: 12px;
}

th {
  background: #f3f4f6;
  font-weight: bold;
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  vertical-align: middle;
}

.center { text-align: center; }

select {
  padding: 4px 8px;
  min-width: 160px;
}

button {
  padding: 6px 14px;
  cursor: pointer;
}

.btn-secondary{
  background: #f3f4f6;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

button:disabled{
  opacity: 0.6;
  cursor: not-allowed;
}

.badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-weight: bold;
  font-size: 14px;
  display: inline-block;
}

.badge.present {
  background-color: #d1fae5;
  color: #065f46;
}

.badge.absent {
  background-color: #fee2e2;
  color: #991b1b;
}

.flash{
  margin: 10px 0;
  padding: 10px 12px;
  border: 1px solid #bbf7d0;
  background: #ecfdf5;
  color: #065f46;
  border-radius: 8px;
  width: fit-content;
}
</style>
