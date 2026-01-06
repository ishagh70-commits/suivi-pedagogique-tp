<template>
  <div style="padding: 20px; max-width: 700px;">
    <h1>Ajouter un étudiant 🎓</h1>

    <p v-if="page.props.flash?.success" style="color: green; margin: 10px 0;">
      {{ page.props.flash.success }}
    </p>

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
          <option value="">-- Choisir --</option>
          <option value="L1">Licence 1 (L1)</option>
          <option value="L2">Licence 2 (L2)</option>
          <option value="L3">Licence 3 (L3)</option>
          <option value="M1">Master 1 (M1)</option>
          <option value="M2">Master 2 (M2)</option>
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

        <p v-if="groups.length === 0" style="margin-top: 6px; color:#b45309;">
          ⚠️ Aucun groupe trouvé. Vérifie la table <b>groups</b> dans la BD.
        </p>
      </div>

      <div style="margin-top: 14px; display:flex; gap:10px;">
        <button type="submit" :disabled="form.processing">
          {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
        </button>

        <Link href="/etudiants">Annuler</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
  groups: { type: Array, default: () => [] },
})

const groups = props.groups

const form = useForm({
  matricule: '',
  nom: '',
  prenom: '',
  filiere: '',
  niveau: '',
  group_id: '',
})

function submit() {
  form.post('/etudiants', {
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
