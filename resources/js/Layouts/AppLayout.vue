<template>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ collapsed }">
      <div class="brand">
        <div class="logo">SP</div>
        <div class="brand-text" v-if="!collapsed">
          <div class="title">Suivi Pédagogique</div>
          <div class="subtitle">TP / Démo</div>
        </div>
      </div>

      <nav class="nav">
        <Link class="nav-link" :class="{ active: isActive('/etudiants') }" href="/etudiants">
          <span class="icon">🎓</span>
          <span class="label" v-if="!collapsed">Étudiants</span>
        </Link>

        <Link class="nav-link" :class="{ active: isActive('/enseignants') }" href="/enseignants">
          <span class="icon">👨‍🏫</span>
          <span class="label" v-if="!collapsed">Enseignants</span>
        </Link>

        <Link class="nav-link" :class="{ active: isActive('/groups') }" href="/groups">
          <span class="icon">🏫</span>
          <span class="label" v-if="!collapsed">Groupes / Classes</span>
        </Link>

        <Link class="nav-link" :class="{ active: isActive('/emploi') }" href="/emploi">
          <span class="icon">🗓️</span>
          <span class="label" v-if="!collapsed">Emploi du temps</span>
        </Link>

        <Link class="nav-link" :class="{ active: isActive('/presences') }" href="/presences">
          <span class="icon">✅</span>
          <span class="label" v-if="!collapsed">Présences</span>
        </Link>
      </nav>

      <div class="sidebar-footer" v-if="!collapsed">
        <div class="hint">
          Astuce démo : commence par <b>Étudiants</b> → <b>Présences</b> → <b>Export</b>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="topbar">
        <button class="burger" @click="collapsed = !collapsed" title="Menu">
          ☰
        </button>

        <div class="topbar-title">
          <div class="page-title">{{ title }}</div>
          <div class="page-subtitle" v-if="subtitle">{{ subtitle }}</div>
        </div>

        <div class="topbar-right">
          <span class="chip">Local</span>
        </div>
      </header>

      <main class="content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
})

const page = usePage()
const collapsed = ref(false)

function isActive(prefix) {
  const url = page.url || ''
  // actif si l’URL commence par /etudiants, /presences, etc.
  return url === prefix || url.startsWith(prefix + '?') || url.startsWith(prefix + '/')
}
</script>

<style scoped>
:root { color-scheme: light; }
* { box-sizing: border-box; }

.app{
  min-height: 100vh;
  display: flex;
  background: #f6f7fb;
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  color: #111827;
}

.sidebar{
  width: 260px;
  background: #111827;
  color: #e5e7eb;
  display: flex;
  flex-direction: column;
  padding: 14px;
  transition: width .2s ease;
}
.sidebar.collapsed{ width: 86px; }

.brand{
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 6px 14px;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.logo{
  width: 42px; height: 42px;
  border-radius: 10px;
  background: rgba(255,255,255,.12);
  display: grid; place-items: center;
  font-weight: 800;
  letter-spacing: .5px;
}
.brand-text .title{ font-weight: 800; font-size: 14px; }
.brand-text .subtitle{ font-size: 12px; opacity: .75; margin-top: 2px; }

.nav{
  margin-top: 12px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.nav-link{
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 10px;
  border-radius: 10px;
  text-decoration: none;
  color: #e5e7eb;
  transition: background .15s ease, transform .05s ease;
}
.nav-link:hover{ background: rgba(255,255,255,.10); }
.nav-link:active{ transform: scale(.99); }
.nav-link.active{
  background: rgba(59,130,246,.25);
  outline: 1px solid rgba(59,130,246,.35);
}
.icon{ width: 22px; text-align: center; }
.label{ font-weight: 600; }

.sidebar-footer{
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid rgba(255,255,255,.08);
}
.hint{
  font-size: 12px;
  opacity: .80;
  line-height: 1.35;
}

.main{
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.topbar{
  height: 62px;
  background: #ffffff;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  padding: 0 16px;
  gap: 12px;
}
.burger{
  border: 1px solid #e5e7eb;
  background: #fff;
  border-radius: 10px;
  padding: 8px 10px;
  cursor: pointer;
}
.topbar-title{ flex: 1; min-width: 0; }
.page-title{ font-weight: 800; }
.page-subtitle{ font-size: 12px; color: #6b7280; margin-top: 2px; }

.topbar-right{ display: flex; align-items: center; gap: 10px; }
.chip{
  font-size: 12px;
  padding: 6px 10px;
  border-radius: 999px;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
}

.content{
  padding: 18px;
  min-width: 0;
}
</style>
