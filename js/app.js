/* ===========================================
   HerGrowth — JavaScript principal
   =========================================== */

let COACHES = [];

const apiCoachPath = location.pathname.includes("/pages/") ? "../php/coachs_api.php" : "php/coachs_api.php";
fetch(apiCoachPath)
  .then(r => r.json())
  .then(data => {
    if (Array.isArray(data)) {
      COACHES = data;
      renderCoachCards(document.getElementById("coachs-grid"), COACHES);
      if (typeof initCoachsPage === "function") initCoachsPage();
    }
  })
  .catch(e => console.error("Erreur chargement coachs:", e));

const RESERVATIONS = [
  { id:1, coach:"Inès Benmoussa", domaine:"Sport",     date:"2025-08-12", heure:"10:00", statut:"confirme",  type:"Individuelle", prix:45 },
  { id:2, coach:"Sara Khalil",    domaine:"Coding",    date:"2025-08-15", heure:"14:00", statut:"attente",   type:"Individuelle", prix:60 },
  { id:3, coach:"Amira Toumi",    domaine:"Nutrition", date:"2025-07-28", heure:"11:00", statut:"confirme",  type:"Groupe",       prix:30 },
];

const BADGES_USER = ["💪 Motivée", "🌱 En équilibre"];

/* ==============================
   Utilitaires
============================== */
function stars(n) {
  const full = Math.floor(n);
  return "★".repeat(full) + (n % 1 >= 0.5 ? "½" : "") + "☆".repeat(5 - Math.ceil(n));
}
function statusLabel(s) {
  const map = { confirme:"Confirmé", attente:"En attente", annule:"Annulé" };
  return map[s] || s;
}
function formatDate(d) {
  return new Date(d).toLocaleDateString("fr-FR", { day:"numeric", month:"long", year:"numeric" });
}

/* ==============================
   Navbar
============================== */
(function initNavbar() {
  const burger = document.getElementById("burger");
  const navLinks = document.querySelector(".nav-links");
  if (burger && navLinks) {
    burger.addEventListener("click", () => navLinks.classList.toggle("open"));
  }
  // Active link
  const links = document.querySelectorAll(".nav-links a");
  links.forEach(l => { if (l.href === location.href) l.classList.add("active"); });
})();

/* ==============================
   Index — Coach Cards
============================== */
function renderCoachCards(container, coaches) {
  if (!container) return;
  container.innerHTML = coaches.slice(0, 3).map(c => `
    <div class="coach-profile-card">
      <div class="coach-img ${c.bg}">
        ${c.emoji}
        ${c.verified ? '<div class="verified-badge">✓ Vérifiée</div>' : ''}
      </div>
      <div class="coach-body">
        <div class="coach-meta">
          <div>
            <div class="coach-name">${c.nom}</div>
            <div class="coach-role">${c.specialite}</div>
          </div>
          <div class="coach-price">${c.prix}DT/h</div>
        </div>
        <div class="coach-bio">${c.bio}</div>
        <div class="coach-footer">
          <div class="stars">${stars(c.note)} <span>(${c.avis})</span></div>
          <button class="btn btn-sm btn-rose" onclick="openBooking(${c.id})">Réserver</button>
        </div>
      </div>
    </div>`).join("");
}

/* ==============================
   Page Coachs — Filtres
============================== */
function initCoachsPage() {
  const grid = document.getElementById("all-coachs-grid");
  if (!grid) return;

  function render(data) {
    grid.innerHTML = data.map(c => `
        <div class="coach-profile-card">
          <div class="coach-img ${getCoachBg(c.domaine)}">
            ${getCoachEmoji(c.nom)}
            <div class="verified-badge">✓ Vérifiée</div>
          </div>
          <div class="coach-body">
            <div class="coach-meta">
              <h3 class="coach-name">${c.nom}</h3>
              <span class="coach-role">${c.specialite}</span>
              <div class="stars" style="margin-top:4px">
                ${stars(c.avg_rating)} <span>(${c.count_avis || 0})</span>
              </div>
            </div>
            <p class="coach-bio">${c.bio}</p>
            <div class="coach-footer">
              <div class="coach-price">${c.prix}DT<span>/séance</span></div>
              <div style="display:flex; gap:8px">
                <button class="btn btn-ghost btn-sm" onclick="openChat(${c.user_id}, '${c.nom}')" title="Discuter"><span class="icon">💬</span></button>
                <button class="btn btn-primary btn-sm" onclick="openBooking(${c.id}, '${c.nom}', ${c.prix})">Réserver</button>
              </div>
            </div>
          </div>
        </div>
      `).join("");
  }

  function filter() {
    const domaine = document.getElementById("f-domaine")?.value || "";
    const prix    = document.getElementById("f-prix")?.value || "";
    const note    = parseFloat(document.getElementById("f-note")?.value || "0");
    const mode    = document.getElementById("f-mode")?.value || "";

    let list = [...COACHES];
    if (domaine) list = list.filter(c => c.domaine === domaine);
    if (prix === "low")  list = list.filter(c => c.prix <= 45);
    if (prix === "mid")  list = list.filter(c => c.prix > 45 && c.prix <= 60);
    if (prix === "high") list = list.filter(c => c.prix > 60);
    if (note) list = list.filter(c => c.note >= note);
    if (mode === "online")    list = list.filter(c => c.en_ligne);
    if (mode === "presential") list = list.filter(c => !c.en_ligne);
    render(list);
  }

  ["f-domaine","f-prix","f-note","f-mode"].forEach(id => {
    document.getElementById(id)?.addEventListener("change", filter);
  });
  document.getElementById("f-search")?.addEventListener("input", e => {
    const q = e.target.value.toLowerCase();
    render(COACHES.filter(c => c.nom.toLowerCase().includes(q) || c.specialite.toLowerCase().includes(q)));
  });
  render(COACHES);
}

/* ==============================
   Modal Réservation
============================== */
let selectedDate = null;
let selectedTime = null;
let selectedCoach = null;

function openBooking(coachId) {
  selectedCoach = COACHES.find(c => c.id === coachId);
  if (!selectedCoach) return;
  const modal = document.getElementById("booking-modal");
  if (!modal) { location.href = "pages/login.php"; return; }
  document.getElementById("modal-coach-name").textContent = selectedCoach.nom;
  document.getElementById("modal-coach-role").textContent = selectedCoach.specialite;
  document.getElementById("modal-coach-price").textContent = selectedCoach.prix + "DT/h";
  renderCalendar();
  modal.classList.add("open");
}

function closeBooking() {
  document.getElementById("booking-modal")?.classList.remove("open");
  selectedDate = selectedTime = null;
}

function renderCalendar() {
  const grid = document.getElementById("cal-grid");
  if (!grid) return;
  const today = new Date();
  const days = [];
  for (let i = 0; i < 14; i++) {
    const d = new Date(today); d.setDate(today.getDate() + i);
    days.push(d);
  }
  grid.innerHTML = days.map(d => {
    const label = d.toLocaleDateString("fr-FR", { day:"numeric", month:"short" });
    const val   = d.toISOString().split("T")[0];
    return `<div class="cal-day${selectedDate === val ? " selected":""}" onclick="selectDate('${val}',this)">${label}</div>`;
  }).join("");
}

function selectDate(val, el) {
  selectedDate = val;
  document.querySelectorAll(".cal-day").forEach(d => d.classList.remove("selected"));
  el.classList.add("selected");
}

function selectTime(val, el) {
  selectedTime = val;
  document.querySelectorAll(".time-slot").forEach(s => s.classList.remove("selected"));
  el.classList.add("selected");
}

function confirmBooking() {
  if (!selectedDate || !selectedTime) { alert("Merci de choisir une date et un créneau."); return; }
  if (!selectedCoach) { alert("Aucun coach sélectionné."); return; }

  const type    = document.getElementById("seance-type")?.value || "Individuelle";
  const message = document.getElementById("booking-modal").querySelector("textarea")?.value || "";

  const isInPages = location.pathname.includes("/pages/");
  const apiBase   = isInPages ? "../php/reservations.php" : "php/reservations.php";

  const body = new FormData();
  body.append("coach_id", selectedCoach.id);
  body.append("date",     selectedDate);
  body.append("heure",    selectedTime);
  body.append("type",     type);
  body.append("message",  message);

  fetch(apiBase + "?action=create", { method: "POST", body })
    .then(r => r.json())
    .then(data => {
      if (data.error) { alert("Erreur : " + data.error); return; }
      document.getElementById("booking-modal")?.classList.remove("open");
      showToast("Réservation confirmée !");
      selectedDate = selectedTime = null;
    })
    .catch(() => alert("Erreur réseau. Vérifie que tu es connecté."));
}

/* ==============================
   Dashboard Navigation
============================== */
function showSection(id) {
  // Hide all sections
  document.querySelectorAll('.dashboard-section').forEach(s => s.style.display = 'none');
  // Show target section
  const target = document.getElementById('section-' + id);
  if (target) target.style.display = 'block';

  // Update active nav link
  document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
  const navLink = document.getElementById('nav-' + id);
  if (navLink) navLink.classList.add('active');
}

/* ==============================
   Dashboard utilisatrice
============================== */
function initDashboard() {
  const tbody = document.getElementById("resa-tbody");
  const tbodyShort = document.getElementById("resa-tbody-short");
  if (!tbody && !tbodyShort) return;

  if (tbody) tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Chargement...</td></tr>`;
  if (tbodyShort) tbodyShort.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Chargement...</td></tr>`;

  fetch("../php/reservations.php?action=list")
    .then(r => r.json())
    .then(data => {
      if (!Array.isArray(data) || data.length === 0) {
        const empty = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">Aucune réservation. <a href="coachs.php" style="color:var(--rose-deep)">Trouver une coach →</a></td></tr>`;
        if (tbody) tbody.innerHTML = empty;
        if (tbodyShort) tbodyShort.innerHTML = empty;
        return;
      }
      
      const rows = data.map(r => `
        <tr>
          <td><strong>${r.coach_nom}</strong></td>
          <td>${r.specialite || '-'}</td>
          <td>${formatDate(r.date)} – ${r.heure}</td>
          <td>${r.type}</td>
          <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
          <td>${r.prix}DT</td>
          <td><button class="btn btn-sm btn-danger" onclick="cancelResa(${r.id})">Annuler</button></td>
        </tr>`).join("");
      
      const rowsShort = data.slice(0, 3).map(r => `
        <tr>
          <td><strong>${r.coach_nom}</strong></td>
          <td>${formatDate(r.date)}</td>
          <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
          <td><button class="btn btn-sm btn-ghost" onclick="showSection('reservations')">Détails</button></td>
        </tr>`).join("");

      if (tbody) tbody.innerHTML = rows;
      if (tbodyShort) tbodyShort.innerHTML = rowsShort;
    })
    .catch(() => {
      const err = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">Impossible de charger les données.</td></tr>`;
      if (tbody) tbody.innerHTML = err;
      if (tbodyShort) tbodyShort.innerHTML = err;
    });
}

/* ==============================
   Dashboard Coach
============================== */
function initCoachDashboard() {
  const tbody = document.getElementById("coach-resa-tbody");
  const tbodyShort = document.getElementById("coach-resa-tbody-short");
  if (!tbody && !tbodyShort) return;

  if (tbody) tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Chargement...</td></tr>`;
  if (tbodyShort) tbodyShort.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Chargement...</td></tr>`;

  fetch("../php/reservations.php?action=coach-list")
    .then(r => r.json())
    .then(data => {
      if (!Array.isArray(data) || data.length === 0) {
        const empty = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">Aucune séance prévue.</td></tr>`;
        if (tbody) tbody.innerHTML = empty;
        if (tbodyShort) tbodyShort.innerHTML = empty;
        return;
      }

      const rows = data.map(r => `
        <tr>
          <td><strong>${r.prenom} ${r.nom}</strong></td>
          <td>${r.email}</td>
          <td>${formatDate(r.date)} – ${r.heure}</td>
          <td>${r.type}</td>
          <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
          <td>${r.prix}DT</td>
          <td>
            ${r.statut === 'attente' ? `<button class="btn btn-sm btn-primary" onclick="acceptResaCoach(${r.id})">Accepter</button>` : ''}
            ${r.statut !== 'annule' ? `<button class="btn btn-sm btn-danger" onclick="cancelResaCoach(${r.id})">Annuler</button>` : ''}
          </td>
        </tr>`).join("");

      const rowsShort = data.slice(0, 3).map(r => `
        <tr>
          <td><strong>${r.prenom} ${r.nom}</strong></td>
          <td>${formatDate(r.date)}</td>
          <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
          <td><button class="btn btn-sm btn-ghost" onclick="showSection('reservations')">Voir</button></td>
        </tr>`).join("");

      if (tbody) tbody.innerHTML = rows;
      if (tbodyShort) tbodyShort.innerHTML = rowsShort;
    })
    .catch(() => {
      const err = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">Impossible de charger les données.</td></tr>`;
      if (tbody) tbody.innerHTML = err;
      if (tbodyShort) tbodyShort.innerHTML = err;
    });
}

function cancelResa(id) {
  if (!confirm("Annuler cette réservation ?")) return;
  const body = new FormData();
  body.append("reservation_id", id);
  fetch("../php/reservations.php?action=cancel", { method: "POST", body })
    .then(r => r.json())
    .then(data => {
      if (data.error) { alert("Erreur : " + data.error); return; }
      showToast("Réservation annulée.");
      initDashboard();
    })
    .catch(() => alert("Erreur réseau."));
}

function acceptResaCoach(id) {
  if (!confirm("Accepter cette réservation ?")) return;
  const body = new FormData();
  body.append("reservation_id", id);
  fetch("../php/reservations.php?action=coach-accept", { method: "POST", body })
    .then(r => r.json())
    .then(data => {
      if (data.error) { alert("Erreur : " + data.error); return; }
      showToast("Réservation confirmée !");
      initCoachDashboard();
    })
    .catch(() => alert("Erreur réseau."));
}

function cancelResaCoach(id) {
  if (!confirm("Annuler cette réservation ?")) return;
  const body = new FormData();
  body.append("reservation_id", id);
  fetch("../php/reservations.php?action=coach-cancel", { method: "POST", body })
    .then(r => r.json())
    .then(data => {
      if (data.error) { alert("Erreur : " + data.error); return; }
      showToast("Réservation annulée.");
      initCoachDashboard();
    })
    .catch(() => alert("Erreur réseau."));
}

/* ==============================
   Admin Dashboard
============================== */
function showAdminSection(id) {
  document.querySelectorAll('.dashboard-section').forEach(s => s.style.display = 'none');
  const target = document.getElementById('admin-section-' + id);
  if (target) target.style.display = 'block';
  document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
  const navLink = document.getElementById('nav-' + id);
  if (navLink) navLink.classList.add('active');
}

function initAdminDashboard() {
  const adminApi = "../php/admin_api.php";
  
  // Stats
  fetch(adminApi + "?action=stats")
    .then(r => r.json())
    .then(d => {
      document.getElementById("stat-users").textContent = d.users;
      document.getElementById("stat-coachs").textContent = d.coachs;
      document.getElementById("stat-resas").textContent = d.reservations;
      document.getElementById("stat-revenue").textContent = d.revenue + "DT";
    });

  // Load Coachs
  fetch(adminApi + "?action=list-coachs")
    .then(r => r.json())
    .then(data => {
      const tbody = document.getElementById("admin-coachs-tbody");
      if (!tbody) return;
      tbody.innerHTML = data.map(c => `
        <tr>
          <td><strong>${c.nom}</strong><br><small>${c.specialite}</small></td>
          <td>${c.domaine}</td>
          <td>${c.prix}DT/h</td>
          <td><span class="badge-status status-${c.valide == 1 ? 'confirme' : 'attente'}">${c.valide == 1 ? 'Validé' : 'En attente'}</span></td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="toggleCoachValidation(${c.id}, ${c.valide == 1 ? 0 : 1})">${c.valide == 1 ? 'Suspendre' : 'Valider'}</button>
            <button class="btn btn-sm btn-danger" onclick="deleteCoachAdmin(${c.id})">Supprimer</button>
          </td>
        </tr>`).join("");
    });

  // Load Reservations
  fetch(adminApi + "?action=list-reservations")
    .then(r => r.json())
    .then(data => {
      const tbody = document.getElementById("admin-resas-tbody");
      if (!tbody) return;
      tbody.innerHTML = data.map(r => `
        <tr>
          <td><strong>${r.prenom} ${r.nom}</strong></td>
          <td>${r.coach_nom}</td>
          <td>${formatDate(r.date)}</td>
          <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
          <td>${r.prix}DT</td>
          <td><button class="btn btn-sm btn-danger">Annuler</button></td>
        </tr>`).join("");
    });

  // Load Users
  fetch(adminApi + "?action=list-users")
    .then(r => r.json())
    .then(data => {
      const tbody = document.getElementById("admin-users-tbody");
      if (!tbody) return;
      tbody.innerHTML = data.map(u => `
        <tr>
          <td><strong>${u.prenom} ${u.nom}</strong></td>
          <td>${u.email}</td>
          <td>${formatDate(u.created_at)}</td>
          <td>${u.role}</td>
          <td><button class="btn btn-sm btn-danger">Bloquer</button></td>
        </tr>`).join("");
    });
}

function toggleCoachValidation(id, val) {
  const body = new FormData();
  body.append("id", id);
  body.append("valide", val);
  fetch("../php/admin_api.php?action=validate-coach", { method: "POST", body })
    .then(() => { showToast("Statut mis à jour."); initAdminDashboard(); });
}

function deleteCoachAdmin(id) {
  if (!confirm("Supprimer définitivement ce coach ?")) return;
  const body = new FormData();
  body.append("id", id);
  fetch("../php/admin_api.php?action=delete-coach", { method: "POST", body })
    .then(() => { showToast("Coach supprimé."); initAdminDashboard(); });
}

/* ==============================
   Chat & Messaging
============================== */
function openChat(contactId, name) {
  showSection('messages');
  loadChat(contactId, name);
}

function loadChat(contactId, name) {
  const container = document.getElementById('chat-container');
  if (!container) return;
  
  container.innerHTML = `<div class="chat-header"><h4>Discussion avec ${name}</h4></div><div id="chat-messages" class="chat-messages">Chargement...</div>
    <div class="chat-input-area">
      <input type="text" id="chat-input" placeholder="Écrivez votre message...">
      <button class="btn btn-primary btn-sm" onclick="sendMessage(${contactId}, '${name}')">Envoyer</button>
    </div>`;

  fetch(`../php/messages_api.php?action=get_chat&contact_id=${contactId}`)
    .then(r => r.json())
    .then(data => {
      const msgBox = document.getElementById('chat-messages');
      msgBox.innerHTML = data.map(m => `
        <div class="message ${m.expediteur_id == currentUserId ? 'sent' : 'received'}">
          <div class="msg-content">${m.contenu}</div>
          <div class="msg-time">${formatDate(m.created_at)}</div>
        </div>
      `).join("");
      msgBox.scrollTop = msgBox.scrollHeight;
    });
}

function sendMessage(destId, name) {
  const input = document.getElementById('chat-input');
  const text = input.value.trim();
  if (!text) return;

  const body = new FormData();
  body.append('destinataire_id', destId);
  body.append('contenu', text);

  fetch('../php/messages_api.php?action=send', { method: 'POST', body })
    .then(() => {
      input.value = '';
      loadChat(destId, name);
    });
}

/* ==============================
   Reviews / Avis
============================== */
function openReviewModal(resaId, coachId) {
  const note = prompt("Quelle note donnez-vous à cette séance ? (1 à 5)");
  if (!note || note < 1 || note > 5) return;
  const comment = prompt("Un petit commentaire ?");
  
  const body = new FormData();
  body.append('reservation_id', resaId);
  body.append('coach_id', coachId);
  body.append('note', note);
  body.append('commentaire', comment);

  fetch('../php/admin_api.php?action=submit-review', { method: 'POST', body })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        showToast("Merci pour votre avis !");
        initDashboard();
      }
    });
}
(function initAuth() {
  // Role tabs
  document.querySelectorAll(".role-tab").forEach(tab => {
    tab.addEventListener("click", () => {
      document.querySelectorAll(".role-tab").forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
    });
  });
})();

/* ==============================
   Toast notification
============================== */
function showToast(msg) {
  let t = document.getElementById("toast");
  if (!t) {
    t = document.createElement("div"); t.id = "toast";
    Object.assign(t.style, {
      position:"fixed", bottom:"32px", right:"32px", zIndex:"9999",
      background:getComputedStyle(document.documentElement).getPropertyValue("--rose-deep") || "#E8879D",
      color:"#fff", padding:"14px 24px", borderRadius:"50px",
      fontSize:"14px", fontFamily:"DM Sans,sans-serif",
      boxShadow:"0 8px 32px rgba(180,90,110,.35)",
      transition:"all .3s", opacity:"0", transform:"translateY(12px)"
    });
    document.body.appendChild(t);
  }
  t.textContent = msg;
  requestAnimationFrame(() => { t.style.opacity="1"; t.style.transform="translateY(0)"; });
  setTimeout(() => { t.style.opacity="0"; t.style.transform="translateY(12px)"; }, 3000);
}

/* ==============================
   Fermer modal au clic extérieur
============================== */
document.addEventListener("click", e => {
  const modal = document.getElementById("booking-modal");
  if (modal && e.target === modal) closeBooking();
});