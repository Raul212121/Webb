/* =========================
   STATE
========================= */
let currentNPC = null;

/* =========================
   NPC CONFIG
========================= */
const NPC_CONFIG = {
  magazin_general: {
    imagePrefix: 'Magazinul_General',
    buttons: {
      1: { left: 38, bottom: 52 },
      2: { left: 60, bottom: 52 },
      3: { left: 81, bottom: 52 },
      4: { left: 102, bottom: 52 },
      5: { left: 123, bottom: 52 }
    }
  },

  negustor_de_arme: {
    imagePrefix: 'negustorul_de_arme',
    buttons: {
      1: { left: 60, bottom: 52 },
      2: { left: 82, bottom: 52 },
      3: { left: 103, bottom: 52 }
    }
  },

  negustor_de_armuri: {
    imagePrefix: 'Negustorul_de_armuri',
    buttons: {
      1: { left: 49, bottom: 52 },
      2: { left: 70, bottom: 52 },
      3: { left: 92, bottom: 52 },
      4: { left: 113, bottom: 52 }
    }
  },

  maestru: {
    imagePrefix: 'maestru',
    buttons: {
      1: { left: 81, bottom: 52 }
    }
  },

  fierar: {
    imagePrefix: 'fierar',
    buttons: {
      1: { left: 60, bottom: 52 },
      2: { left: 82, bottom: 52 },
      3: { left: 103, bottom: 52 }
    }
  },

  femeia_batrana: {
    imagePrefix: 'femeia_batrana',
    buttons: {
      1: { left: 38, bottom: 52 },
      2: { left: 60, bottom: 52 },
      3: { left: 81, bottom: 52 },
      4: { left: 102, bottom: 52 },
      5: { left: 123, bottom: 52 }
    }
  },
  
  invatator_magie: {
    imagePrefix: 'invatator_de_magie',
    buttons: {
      1: { left: 81, bottom: 52 }
    }
  }
};

/* =========================
   NPC MODAL LOGIC
========================= */
function openNPC(npcKey) {
  const npc = NPC_CONFIG[npcKey];
  if (!npc) return;

  currentNPC = npcKey;

  const modal = document.getElementById('npc-modal');
  modal.classList.remove('hidden');

  // NPC preview
  document.getElementById('npc-modal-image').src =
    `img/npc/${npcKey}/npc.png`;

  // Configure buttons per NPC
  document.querySelectorAll('.shop-btn').forEach(btn => {
    const page = Number(btn.dataset.page);
    const cfg = npc.buttons[page];

    if (!cfg) {
      btn.style.display = 'none';
    } else {
      btn.style.display = 'block';
      btn.style.left = cfg.left + 'px';
      btn.style.bottom = cfg.bottom + 'px';
    }
  });

  // Default to first page
  switchPage(1);
}

function switchPage(page) {
  if (!currentNPC) return;

  const npc = NPC_CONFIG[currentNPC];
  if (!npc || !npc.buttons[page]) return;

  document.getElementById('shop-image').src =
    `img/npc/${currentNPC}/${npc.imagePrefix}_${page}.png`;
}

function closeNPC() {
  const modal = document.getElementById('npc-modal');
  modal.classList.add('hidden');
  currentNPC = null;
}

/* =========================
   PAGE NAVIGATION (SPA)
========================= */
document.querySelectorAll('.top-bar nav a[data-page]').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();

    const page = link.dataset.page;

    // Inchide orice modal deschis
    closeNPC();

    // Active link
    document.querySelectorAll('.top-bar nav a')
      .forEach(a => a.classList.remove('active'));
    link.classList.add('active');

    // Switch page
    document.querySelectorAll('.page')
      .forEach(p => p.classList.remove('active'));
    const target = document.getElementById('page-' + page);
    if (target) target.classList.add('active');
  });
});


function animateNPC(npcKey, totalFrames, interval = 3000) {
  const card = document.querySelector(`.npc-card[data-npc="${npcKey}"]`);
  if (!card) return;

  const img = card.querySelector('.npc-image img');
  let index = 1;

  setInterval(() => {
    img.style.opacity = 0;

    setTimeout(() => {
      index++;
      if (index > totalFrames) index = 1;
      img.src = `img/npc/${npcKey}/npc_${index}.png`;
      img.style.opacity = 1;
    }, 200);

  }, interval);
}


document.addEventListener('DOMContentLoaded', () => {
  animateNPC('invatator_magie', 3, 3000);
});


function openDungeon(id) {
  document.getElementById('dungeon-image').src =
    `img/dungeon/dungeon_${id}.png`;

  document.getElementById('boss-image').src =
    `img/dungeon/mob_${id}.png`;
}


const DUNGEON_CONFIG = {
  1: { left: 20, top: 42 },
  2: { left: 20, top: 91 },
  3: { left: 20, top: 142 },
  4: { left: 20, top: 192 },
  5: { left: 20, top: 243 },
  6: { left: 20, top: 293 },
  7: { left: 20, top: 342 },
  8: { left: 20, top: 393 }
};
document.querySelectorAll('.dungeon-btn').forEach(btn => {
  const id = btn.dataset.id;
  const cfg = DUNGEON_CONFIG[id];

  if (!cfg) {
    btn.style.display = 'none';
    return;
  }

  btn.style.left = cfg.left + 'px';
  btn.style.top = cfg.top + 'px';

  btn.addEventListener('click', () => {
    openDungeon(id);
  });
});
const SISTEME_CONFIG = {
  wikipedia: {
    title: 'Wikipedia',
    image: 'img/sisteme/wikipedia.png',
    desc: 'Sistem informational complet integrat in joc.Aici poti cauta informatii drop si multe altele!'
  },
  biolog: {
    title: 'Biolog',
    image: 'img/sisteme/biolog.png',
    desc: 'Biolog portabil, accesibil oriunde.'
  },
  battlepass: {
    title: 'Battle Pass',
    image: 'img/sisteme/battlepass.png',
    desc: 'Progres suplimentar cu recompense exclusive.'
  },
  inventar_special: {
    title: 'Inventar Special',
    image: 'img/sisteme/inventar_special.png',
    desc: 'Inventar extins pentru obiecte de upgrade, potiuni, sistemul de bonusat si cufere!'
  },
  dungeon: {
    title: 'Dungeon Browser',
    image: 'img/sisteme/dungeon.png',
    desc: 'Acces rapid la dungeonuri si informatii despre bosi.'
  },
  ranking: {
    title: 'Ranking',
    image: 'img/sisteme/ranking.png',
    desc: 'Clasamente live pentru jucatori si bresle.'
  },
  shop: {
    title: 'Shop',
    image: 'img/sisteme/shop.png',
    desc: 'Magazin offline, cu sistem de Licitatie!.'
  },
  switchbot: {
    title: 'Switchbot',
    image: 'img/sisteme/switchbot.png',
    desc: 'Schimbare automata a bonusurilor pe iteme.Acest sistem este setat la 7 bns/ secunda!'
  }
};

document.querySelectorAll('.sistem-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const key = btn.dataset.sistem;
    const sistem = SISTEME_CONFIG[key];
    if (!sistem) return;

    document.getElementById('sistem-image').src = sistem.image;
    document.getElementById('sistem-title').textContent = sistem.title;
    document.getElementById('sistem-desc').textContent = sistem.desc;

    document.getElementById('sistem-preview').classList.remove('hidden');
  });
});

function closeSistem() {
  document.getElementById('sistem-preview').classList.add('hidden');
}
