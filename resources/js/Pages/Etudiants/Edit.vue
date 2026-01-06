<template>
  <div style="padding: 20px; max-width: 700px;">
    <h1>Modifier étudiant ✏️</h1>

    <form @submit.prevent="submit" style="margin-top: 15px;">
      <div class="field">
        <label>Matricule</label>
        <input v-model="form.matricule" type="text" />
        <small v-if="form.errors.matricule" class="err">{{ form.errors.matricule }}</small>
      </div>

      <div class="field">
        <label>Nom</label>
        <input v-model="form.nom" type="text" />
        <small v-if="form.errors.nom" class="err">{{ form.errors.nom }}</small>
      </div>

      <div class="field">
        <label>Prénom</label>
        <input v-model="form.prenom" type="text" />
        <small v-if="form.errors.prenom" class="err">{{ form.errors.prenom }}</small>
      </div>

      <div class="field">
        <label>Filière</label>
        <input v-model="form.filiere" type="text" />
        <small v-if="form.errors.filiere" class="err">{{ form.errors.filiere }}</small>
      </div>

      <div class="field">
        <label>Niveau</label>
        <select v-model="form.niveau">
          <option value="L1">L1</option>
          <option value="L2">L2</option>
          <option value="L3">L3</option>
          <option value="M1">M1</option>
          <option value="M2">M2</option>
        </select>
        <small v-if="form.errors.niveau" class="err">{{ form.errors.niveau }}</small>
      </div>

      <div class="field">
        <label>Groupe</label>
        <select v-model="form.group_id">
          <option value="">-- Choisir un groupe --</option>
          <option v-for="g in groups" :key="g.id" :value="g.id">
            {{ g.name }}
          </option>
        </select>
        <small v-if="form.errors.group_id" class="err">{{ form.errors.group_id }}</small>
      </div>

      <div style="margin-top: 14px; display:flex; gap:10px;">
        <button type="submit" :disabled="form.processing">
          {{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}
        </button>

        <Link href="/etudiants">Annuler</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  etudiant: Object,
  groups: { type: Array, default: () => [] },
})

const groups = props.groups

const form = useForm({
  matricule: props.etudiant.matricule,
  nom: props.etudiant.nom,
  prenom: props.etudiant.prenom,
  filiere: props.etudiant.filiere,
  niveau: props.etudiant.niveau,
  group_id: props.etudiant.group_id,
})

function submit() {
  form.put(`/etudiants/${props.etudiant.id}`, {
    preserveScroll: true,
  })
}
</script>

<style scoped>
.field { margin: 10px 0; display:flex; flex-direction:column; gap:6px; }
input, select { padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; }
.err { color: #b91c1c; }
button { padding: 8px 14px; cursor: pointer; }
button:disabled { opacity: .6; cursor: not-allowed; }
</style>
