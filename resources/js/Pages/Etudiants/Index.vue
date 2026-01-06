<template>
  <div style="padding: 20px">
    <h1>Liste des étudiants 🎓</h1>

    <p style="margin: 10px 0">
      <Link href="/etudiants/create">➕ Ajouter un étudiant</Link>
    </p>

    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 12px; width: 100%;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Matricule</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Filière</th>
          <th>Niveau</th>
          <th>Groupe</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="e in etudiants" :key="e.id">
          <td>{{ e.id }}</td>
          <td>{{ e.matricule }}</td>
          <td>{{ e.nom }}</td>
          <td>{{ e.prenom }}</td>
          <td>{{ e.filiere }}</td>
          <td>{{ e.niveau }}</td>
          <td>{{ e.group ? e.group.name : '-' }}</td>

          <td style="white-space: nowrap;">
            <a :href="`/etudiants/${e.id}/edit`" style="margin-right: 10px;">
              ✏️ Modifier
            </a>

            <button @click="deleteEtudiant(e.id)" style="cursor: pointer;">
              🗑️ Supprimer
            </button>
          </td>
        </tr>

        <tr v-if="etudiants.length === 0">
          <td colspan="8">Aucun étudiant pour le moment.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

defineProps({
  etudiants: {
    type: Array,
    default: () => [],
  },
})

function deleteEtudiant(id) {
  if (!confirm("Tu es sûr de vouloir supprimer cet étudiant ?")) return

  router.delete(`/etudiants/${id}`, {
    preserveScroll: true,
  })
}
</script>
