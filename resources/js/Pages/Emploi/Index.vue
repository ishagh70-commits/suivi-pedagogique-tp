<template>
  <div style="padding:20px">
    <h1>Emploi du temps 🗓️</h1>

    <p v-if="page.props.flash?.success" class="flash">
      {{ page.props.flash.success }}
    </p>

    <div v-if="page.props.errors && Object.keys(page.props.errors).length" class="errors">
      <div v-for="(msg, key) in page.props.errors" :key="key">• {{ msg }}</div>
    </div>

    <!-- Filtre groupe + actions -->
    <div class="row">
      <label>Groupe :</label>
      <select v-model="filterGroupId">
        <option value="">-- Choisir un groupe --</option>
        <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
      </select>

      <button type="button" @click="applyFilter">Afficher</button>

      <!-- ✅ Export PDF -->
      <button
        type="button"
        class="btn-secondary"
        :disabled="!canExport"
        @click="exportPdf"
        title="Exporter l'emploi du temps en PDF"
      >
        Export PDF
      </button>
    </div>

    <p v-if="!filterGroupId" class="hint">Choisis un groupe pour voir et gérer l’emploi du temps.</p>

    <!-- Form ajout -->
    <div v-if="filterGroupId" class="card">
      <h3 style="margin:0 0 10px">Ajouter un créneau</h3>

      <div class="grid">
        <div>
          <label>Matière</label>
          <input v-model="form.matiere" type="text" placeholder="Ex: Méthode Agile" />
        </div>

        <div>
          <label>Jour</label>
          <select v-model="form.jour">
            <option v-for="j in jours" :key="j" :value="j">{{ j }}</option>
          </select>
        </div>

        <div>
          <label>Heure début</label>
          <input v-model="form.heure_debut" type="time" />
        </div>

        <div>
          <label>Heure fin</label>
          <input v-model="form.heure_fin" type="time" />
        </div>

        <div>
          <label>Enseignant</label>
          <select v-model="form.enseignant_id">
            <option value="">-- Choisir --</option>
            <option v-for="e in enseignants" :key="e.id" :value="e.id">
              {{ e.nom }} {{ e.prenom }} ({{ e.specialite }})
            </option>
          </select>
        </div>

        <div>
          <label>Salle (optionnel)</label>
          <input v-model="form.salle" type="text" placeholder="Ex: Labo" />
        </div>
      </div>

      <div style="margin-top:12px">
        <button :disabled="!canCreate" @click="createSlot">Ajouter</button>
      </div>
    </div>

    <!-- Tableau -->
    <div v-if="filterGroupId" class="card" style="margin-top:14px">
      <h3 style="margin:0 0 10px">Créneaux</h3>

      <table>
        <thead>
          <tr>
            <th style="width:130px">Jour</th>
            <th style="width:170px">Heure</th>
            <th>Matière</th>
            <th style="width:280px">Enseignant</th>
            <th style="width:120px">Salle</th>
            <th style="width:190px">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="slot in emplois" :key="slot.id">
            <td>
              <span v-if="editId !== slot.id">{{ slot.jour }}</span>
              <select v-else v-model="edit.jour">
                <option v-for="j in jours" :key="j" :value="j">{{ j }}</option>
              </select>
            </td>

            <td>
              <span v-if="editId !== slot.id">
                {{ fmtTime(slot.heure_debut) }} - {{ fmtTime(slot.heure_fin) }}
              </span>
              <div v-else class="row" style="gap:8px">
                <input type="time" v-model="edit.heure_debut" />
                <input type="time" v-model="edit.heure_fin" />
              </div>
            </td>

            <td>
              <span v-if="editId !== slot.id">{{ slot.matiere }}</span>
              <input v-else v-model="edit.matiere" type="text" />
            </td>

            <td>
              <span v-if="editId !== slot.id">
                {{ slot.enseignant?.nom }} {{ slot.enseignant?.prenom }}
                <span class="muted">({{ slot.enseignant?.specialite }})</span>
              </span>

              <select v-else v-model="edit.enseignant_id">
                <option v-for="e in enseignants" :key="e.id" :value="e.id">
                  {{ e.nom }} {{ e.prenom }} ({{ e.specialite }})
                </option>
              </select>
            </td>

            <td>
              <span v-if="editId !== slot.id">{{ slot.salle || '-' }}</span>
              <input v-else v-model="edit.salle" type="text" />
            </td>

            <td>
              <div class="row" style="gap:8px">
                <button v-if="editId !== slot.id" class="btn-secondary" @click="startEdit(slot)">Modifier</button>
                <button v-if="editId === slot.id" @click="saveEdit(slot.id)">Enregistrer</button>
                <button v-if="editId === slot.id" class="btn-secondary" @click="cancelEdit">Annuler</button>

                <button class="btn-danger" @click="remove(slot.id)">Supprimer</button>
              </div>
            </td>
          </tr>

          <tr v-if="emplois.length === 0">
            <td colspan="6" class="center" style="padding:16px">Aucun créneau pour ce groupe.</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
  groups: Array,
  enseignants: Array,
  group_id: [Number, String, null],
  emplois: { type: Array, default: () => [] },
})

const groups = props.groups ?? []
const enseignants = props.enseignants ?? []
const emplois = props.emplois ?? []

const jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']

const filterGroupId = ref(props.group_id ?? '')

function applyFilter() {
  router.get('/emploi', { group_id: filterGroupId.value || null }, { preserveState: true, preserveScroll: true })
}

const form = reactive({
  group_id: filterGroupId.value || '',
  enseignant_id: '',
  matiere: '',
  jour: 'Lundi',
  heure_debut: '',
  heure_fin: '',
  salle: '',
})

// ✅ important : quand tu changes de groupe, on met à jour form.group_id
watch(filterGroupId, (val) => {
  form.group_id = val || ''
})

const canCreate = computed(() =>
  !!filterGroupId.value &&
  !!form.enseignant_id &&
  !!form.matiere &&
  !!form.jour &&
  !!form.heure_debut &&
  !!form.heure_fin
)

const canExport = computed(() => !!filterGroupId.value)

function createSlot() {
  router.post('/emploi', {
    group_id: Number(filterGroupId.value),
    enseignant_id: Number(form.enseignant_id),
    matiere: form.matiere,
    jour: form.jour,
    heure_debut: form.heure_debut,
    heure_fin: form.heure_fin,
    salle: form.salle || null,
  })
}

// ✅ Export PDF
function exportPdf() {
  const url = `/emploi/export/pdf?group_id=${encodeURIComponent(filterGroupId.value)}`
  window.location.href = url
}

/** Edition inline */
const editId = ref(null)
const edit = reactive({
  group_id: null,
  enseignant_id: null,
  matiere: '',
  jour: '',
  heure_debut: '',
  heure_fin: '',
  salle: '',
})

function startEdit(slot) {
  editId.value = slot.id
  edit.group_id = slot.group_id
  edit.enseignant_id = slot.enseignant_id
  edit.matiere = slot.matiere
  edit.jour = slot.jour
  edit.heure_debut = fmtTime(slot.heure_debut)
  edit.heure_fin = fmtTime(slot.heure_fin)
  edit.salle = slot.salle ?? ''
}

function cancelEdit() {
  editId.value = null
}

function saveEdit(id) {
  router.put(`/emploi/${id}`, {
    group_id: Number(filterGroupId.value),
    enseignant_id: Number(edit.enseignant_id),
    matiere: edit.matiere,
    jour: edit.jour,
    heure_debut: edit.heure_debut,
    heure_fin: edit.heure_fin,
    salle: edit.salle || null,
  })
  editId.value = null
}

function remove(id) {
  if (!confirm('Supprimer ce créneau ?')) return
  router.delete(`/emploi/${id}`)
}

/** Helpers */
function fmtTime(t) {
  if (!t) return ''
  return String(t).slice(0,5)
}
</script>

<style>
.row{
  margin: 10px 0;
  display:flex;
  align-items:center;
  gap:10px;
  flex-wrap:wrap;
}

.grid{
  display:grid;
  grid-template-columns: repeat(3, minmax(220px, 1fr));
  gap:10px;
}

@media (max-width: 900px){
  .grid{ grid-template-columns: 1fr; }
}

label{ display:block; font-weight:600; margin-bottom:6px; }

input, select{
  padding:6px 10px;
  border:1px solid #d1d5db;
  border-radius:8px;
  width:100%;
}

button{
  padding:6px 14px;
  cursor:pointer;
}

.btn-secondary{
  background:#f3f4f6;
  border:1px solid #d1d5db;
  border-radius:8px;
}

.btn-danger{
  background:#fee2e2;
  border:1px solid #fecaca;
  border-radius:8px;
}

table{
  border-collapse:collapse;
  background:#fff;
  width:100%;
}

th{
  background:#f3f4f6;
  font-weight:700;
}

th, td{
  border:1px solid #ccc;
  padding:10px;
  vertical-align:middle;
}

.center{ text-align:center; }
.muted{ color:#6b7280; }

.card{
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:12px;
  padding:14px;
}

.hint{ margin:8px 0 14px; color:#666; }

.flash{
  margin:10px 0;
  padding:10px 12px;
  border:1px solid #bbf7d0;
  background:#ecfdf5;
  color:#065f46;
  border-radius:8px;
  width:fit-content;
}

.errors{
  margin:10px 0;
  padding:10px 12px;
  border:1px solid #fecaca;
  background:#fef2f2;
  color:#991b1b;
  border-radius:8px;
  width:fit-content;
}
</style>
