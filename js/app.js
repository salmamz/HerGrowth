/* ═══════════════════════════════════════════
   HerGrowth — JavaScript principal
   ═══════════════════════════════════════════ */

/* ── Données mock (remplacées par PHP/BDD en prod) ── */
const COACHES = [
  { id:1, nom:"Inès Benmoussa",   domaine:"Sport",       specialite:"Sport post-partum · Yoga",          emoji:"🧘‍♀️", bg:"bg-pink",     prix:45,  note:4.9, avis:128, en_ligne:true,  bio:"Certifiée STAPS, spécialisée dans la reprise sportive douce après accouchement. Approche bienveillante et progressive." },
  { id:2, nom:"Sara Khalil",      domaine:"Coding",      specialite:"Reconversion tech · Frontend",       emoji:"💻", bg:"bg-lavender", prix:60,  note:5.0, avis:94,  en_ligne:false, bio:"Dev senior passée par le bootcamp, coach en reconversion tech depuis 3 ans. Spécialisée JavaScript & React." },
  { id:3, nom:"Amira Toumi",      domaine:"Nutrition",   specialite:"Nutrition PCOS · Grossesse",         emoji:"🥗", bg:"bg-mint",     prix:50,  note:4.8, avis:211, en_ligne:true,  bio:"Diététicienne-nutritionniste spécialisée en santé hormonale féminine, PCOS et accompagnement périnatal." },
  { id:4, nom:"Leila Mansour",    domaine:"Perso",       specialite:"Développement personnel · Mindset",  emoji:"🧠", bg:"bg-peach",    prix:55,  note:4.7, avis:76,  en_ligne:true,  bio:"Coach certifiée ICF, ancienne cadre dirigeante. Elle t'aide à retrouver confiance et clarté professionnelle." },
  { id:5, nom:"Yasmine Hadj",     domaine:"Sport",       specialite:"Fitness · Pilates",                  emoji:"🏋️", bg:"bg-pink",     prix:40,  note:4.8, avis:153, en_ligne:true,  bio:"Coach fitness et pilates certifiée. Méthodes adaptées à tous les niveaux, du débutant à l'athlète confirmée." },
  { id:6, nom:"Nour Benali",      domaine:"Coding",      specialite:"Coding · Freelancing · UX Design",   emoji:"🎨", bg:"bg-lavender", prix:65,  note:4.9, avis:62,  en_ligne:false, bio:"Designer et développeuse front-end, freelance depuis 5 ans. Elle t'aide à te lancer et à décrocher tes premières missions." },
];

const RESERVATIONS = [
  { id:1, coach:"Inès Benmoussa", domaine:"Sport",     date:"2025-08-12", heure:"10:00", statut:"confirme",  type:"Individuelle", prix:45 },
  { id:2, coach:"Sara Khalil",    domaine:"Coding",    date:"2025-08-15", heure:"14:00", statut:"attente",   type:"Individuelle", prix:60 },
  { id:3, coach:"Amira Toumi",    domaine:"Nutrition", date:"2025-07-28", heure:"11:00", statut:"confirme",  type:"Groupe",       prix:30 },
];

const BADGES_USER = ["💪 Motivée", "🌱 En équilibre"];

/* ══════════════════════════════
   Utilitaires
══════════════════════════════ */
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

/* ══════════════════════════════
   Navbar
══════════════════════════════ */
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

/* ══════════════════════════════
   Index – Coach Cards
══════════════════════════════ */
function renderCoachCards(container, coaches) {
  if (!container) return;
  container.innerHTML = coaches.slice(0, 3).map(c => `
    <div class="coach-profile-card">
      <div class="coach-img ${c.bg}">${c.emoji}</div>
      <div class="coach-body">
        <div class="coach-meta">
          <div>
            <div class="coach-name" style="font-size:17px;font-weight:500;margin-bottom:3px">${c.nom}</div>
            <div class="coach-role">${c.specialite}</div>
          </div>
          <div class="coach-price">${c.prix}€/h</div>
        </div>
        <div class="coach-bio">${c.bio}</div>
        <div class="coach-footer">
          <div class="stars">${stars(c.note)} <span style="color:var(--text-muted);font-size:12px">(${c.avis} avis)</span></div>
          <button class="btn btn-sm btn-rose" onclick="openBooking(${c.id})">Réserver</button>
        </div>
      </div>
    </div>`).join("");
}
renderCoachCards(document.getElementById("coachs-grid"), COACHES);

/* ══════════════════════════════
   Page Coachs – Filtres
══════════════════════════════ */
function initCoachsPage() {
  const grid = document.getElementById("all-coachs-grid");
  if (!grid) return;

  function render(list) {
    grid.innerHTML = list.map(c => `
      <div class="coach-profile-card">
        <div class="coach-img ${c.bg}">${c.emoji}</div>
        <div class="coach-body">
          <div class="coach-meta">
            <div>
              <div class="coach-name" style="font-size:17px;font-weight:500;margin-bottom:3px">${c.nom}</div>
              <div class="coach-role">${c.specialite}</div>
            </div>
            <div class="coach-price">${c.prix}€/h</div>
          </div>
          <div class="coach-bio">${c.bio}</div>
          <div class="coach-footer">
            <div class="stars">${stars(c.note)} <span style="color:var(--text-muted);font-size:12px">(${c.avis} avis)</span></div>
            <button class="btn btn-sm btn-rose" onclick="openBooking(${c.id})">Réserver</button>
          </div>
        </div>
      </div>`).join("");
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
initCoachsPage();

/* ══════════════════════════════
   Modal Réservation
══════════════════════════════ */
let selectedDate = null;
let selectedTime = null;
let selectedCoach = null;

function openBooking(coachId) {
  selectedCoach = COACHES.find(c => c.id === coachId);
  if (!selectedCoach) return;
  const modal = document.getElementById("booking-modal");
  if (!modal) { location.href = "pages/login.html"; return; }
  document.getElementById("modal-coach-name").textContent = selectedCoach.nom;
  document.getElementById("modal-coach-role").textContent = selectedCoach.specialite;
  document.getElementById("modal-coach-price").textContent = selectedCoach.prix + "€/h";
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
  document.getElementById("booking-modal")?.classList.remove("open");
  showToast("Réservation confirmée ! 🎉");
}

/* ══════════════════════════════
   Dashboard utilisatrice
══════════════════════════════ */
function initDashboard() {
  const tbody = document.getElementById("resa-tbody");
  if (!tbody) return;
  tbody.innerHTML = RESERVATIONS.map(r => `
    <tr>
      <td><strong>${r.coach}</strong></td>
      <td>${r.domaine}</td>
      <td>${formatDate(r.date)} – ${r.heure}</td>
      <td>${r.type}</td>
      <td><span class="badge-status status-${r.statut}">${statusLabel(r.statut)}</span></td>
      <td>${r.prix}€</td>
      <td><button class="btn btn-sm btn-danger" onclick="cancelResa(${r.id})">Annuler</button></td>
    </tr>`).join("");

  const badgeList = document.getElementById("badge-list");
  if (badgeList) {
    badgeList.innerHTML = BADGES_USER.map(b => `<div class="badge-card" style="padding:16px 20px"><div class="badge-icon">${b.split(" ")[0]}</div><div class="badge-name">${b.split(" ").slice(1).join(" ")}</div></div>`).join("");
  }
}
initDashboard();

function cancelResa(id) {
  if (!confirm("Annuler cette réservation ?")) return;
  const row = document.querySelector(`[data-resa="${id}"]`);
  showToast("Réservation annulée.");
}

/* ══════════════════════════════
   Admin stats
══════════════════════════════ */
function initAdmin() {
  const adminGrid = document.getElementById("admin-coachs-grid");
  if (!adminGrid) return;
  adminGrid.innerHTML = COACHES.map(c => `
    <tr>
      <td><strong>${c.nom}</strong></td>
      <td>${c.domaine}</td>
      <td>${c.specialite.split("·")[0].trim()}</td>
      <td><span class="stars">${stars(c.note)}</span> ${c.note}</td>
      <td>${c.prix}€/h</td>
      <td><span class="badge-status status-confirme">Validée</span></td>
      <td>
        <button class="btn btn-sm btn-outline">Modifier</button>
        <button class="btn btn-sm btn-danger" style="margin-left:6px">Supprimer</button>
      </td>
    </tr>`).join("");
}
initAdmin();

/* ══════════════════════════════
   Auth forms
══════════════════════════════ */
(function initAuth() {
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", e => {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const pass  = document.getElementById("password").value;
      if (email && pass) {
        if (email.includes("admin")) {
          location.href = "admin/dashboard.html";
        } else {
          location.href = "pages/dashboard.html";
        }
      }
    });
  }
  const regForm = document.getElementById("register-form");
  if (regForm) {
    regForm.addEventListener("submit", e => {
      e.preventDefault();
      const pass  = document.getElementById("password").value;
      const pass2 = document.getElementById("password2").value;
      if (pass !== pass2) { alert("Les mots de passe ne correspondent pas."); return; }
      location.href = "pages/dashboard.html";
    });
  }
  // Role tabs
  document.querySelectorAll(".role-tab").forEach(tab => {
    tab.addEventListener("click", () => {
      document.querySelectorAll(".role-tab").forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
    });
  });
})();

/* ══════════════════════════════
   Toast notification
══════════════════════════════ */
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

/* ══════════════════════════════
   Fermer modal au clic extérieur
══════════════════════════════ */
document.addEventListener("click", e => {
  const modal = document.getElementById("booking-modal");
  if (modal && e.target === modal) closeBooking();
});