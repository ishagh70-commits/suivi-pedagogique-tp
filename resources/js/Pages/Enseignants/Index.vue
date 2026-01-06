<template>
  <div>
    <h1>Liste des enseignants 👨‍🏫</h1>

    <table border="1" cellpadding="6">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Spécialité</th>
          <th>Étudiants encadrés</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="ens in enseignants" :key="ens.id">
          <td>{{ ens.id }}</td>
          <td>{{ ens.nom }}</td>
          <td>{{ ens.prenom }}</td>
          <td>{{ ens.specialite }}</td>
          <td>
            <ul>
              <li v-for="et in ens.etudiants" :key="et.id">
                {{ et.nom }} {{ et.prenom }} ({{ et.matricule }})
              </li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({ enseignants: Array });
</script>
