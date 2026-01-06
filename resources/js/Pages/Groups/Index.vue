<template>
  <AppLayout title="Groupes / Classes" subtitle="Liste des groupes + effectifs">
    <div>
      <h1>Groupes / Classes 🏫</h1>

      <table border="1" cellpadding="8" cellspacing="0" style="margin-top:12px; width:100%;">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Nom</th>
            <th style="width:160px;">Code</th>
            <th style="width:140px;">Nb étudiants</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="g in groups" :key="g.id">
            <td>{{ g.id }}</td>
            <td>{{ g.name }}</td>
            <td>{{ g.code ?? '-' }}</td>
            <td>{{ g.etudiants_count ?? 0 }}</td>
          </tr>

          <tr v-if="groups.length === 0">
            <td colspan="4">Aucun groupe.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  groups: { type: Array, default: () => [] }
})
</script>
