(function () {
  "use strict";

  // Mobile menu
  const toggle = document.querySelector(".nav-toggle");
  const nav = document.getElementById("nav");
  const setMenu = (open) => {
    nav.classList.toggle("open", open);
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    toggle.querySelector("i").className = open ? "bi bi-x" : "bi bi-list";
  };
  toggle.addEventListener("click", () => setMenu(!nav.classList.contains("open")));
  nav.querySelectorAll("a").forEach((a) => a.addEventListener("click", () => setMenu(false)));

  // Links that preselect an option in a form: "Request a demo" (product) and "Register interest" (course)
  const preselect = (attr, selectId) => {
    const select = document.getElementById(selectId);
    if (!select) return;
    document.querySelectorAll("[" + attr + "]").forEach((link) => {
      link.addEventListener("click", () => { select.value = link.getAttribute(attr); });
    });
  };
  preselect("data-interest", "interest");
  preselect("data-course", "course");

  document.getElementById("year").textContent = new Date().getFullYear();

  // Anonymous visit counter for the admin "Visitors" page (no cookies, no IP stored)
  try {
    const visit = new FormData();
    visit.append("p", location.pathname);
    visit.append("r", document.referrer);
    visit.append("u", new URLSearchParams(location.search).get("utm_source") || "");
    if (navigator.sendBeacon) navigator.sendBeacon("academy/api/track.php", visit);
  } catch (e) { /* counting must never break the page */ }

  // Christmas offer: shows itself only between these dates (Kampala time)
  const OFFER = { start: "2026-12-01T00:00:00+03:00", end: "2027-01-01T00:00:00+03:00", discount: 0.2 };
  const now = new Date();
  const offerOn = now >= new Date(OFFER.start) && now < new Date(OFFER.end);
  if (offerOn) {
    document.querySelectorAll("[data-offer]").forEach((el) => { el.hidden = false; });
    const offerBar = document.getElementById("offer-bar");
    if (offerBar) offerBar.querySelector(".offer-close").addEventListener("click", () => { offerBar.hidden = true; });
  }

  // Prices: show the price (with the offer if it is on) and the monthly installment
  const DEPOSIT = 0.3, MONTHS = 6;
  const ugx = (n) => "UGX " + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  document.querySelectorAll("[data-price]").forEach((card) => {
    const nowEl = card.querySelector(".price-now");
    const monthlyEl = card.querySelector(".price-monthly");
    const price = Number(card.dataset.price);
    if (!price) {
      nowEl.innerHTML = '<span class="price-todo">UGX [price]</span>';
      monthlyEl.textContent = "UGX [...]";
      return;
    }
    const finalPrice = offerOn ? price * (1 - OFFER.discount) : price;
    if (offerOn) {
      const oldEl = card.querySelector(".price-old");
      oldEl.textContent = ugx(price);
      oldEl.hidden = false;
    }
    nowEl.textContent = ugx(finalPrice);
    // Rounded up to the nearest 1,000 shillings
    monthlyEl.textContent = ugx(Math.ceil(finalPrice * (1 - DEPOSIT) / MONTHS / 1000) * 1000);
  });

  // Course prices on the Learn AI page come from the academy, so admin price changes show here too
  const coursePrices = document.querySelectorAll("[data-course-price]");
  if (coursePrices.length) {
    fetch("academy/api/prices.php")
      .then((res) => res.json())
      .then((data) => coursePrices.forEach((el) => {
        const price = data.prices && data.prices[el.dataset.coursePrice];
        if (price) el.textContent = ugx(price);
      }))
      .catch(() => { /* keep the price written in the page */ });
  }

  // Fade sections in as they scroll into view
  const reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("in");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add("in"));
  }

  // "Start a project" pop-up (falls back to the contact section without <dialog> support)
  const modal = document.getElementById("project-modal");
  if (modal && typeof modal.showModal === "function") {
    document.querySelectorAll("[data-open-project]").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        setMenu(false);
        modal.showModal();
      });
    });
    modal.querySelector("[data-close-modal]").addEventListener("click", () => modal.close());
    // Click on the dark backdrop closes it
    modal.addEventListener("click", (e) => { if (e.target === modal) modal.close(); });
  }

  // WhatsApp nudge: shown once per visit, after 20 seconds or once the visitor is a third of the way down
  const bubble = document.getElementById("wa-bubble");
  const storage = {
    get(key) { try { return sessionStorage.getItem(key); } catch (e) { return null; } },
    set(key, value) { try { sessionStorage.setItem(key, value); } catch (e) { /* ignore */ } }
  };
  if (bubble && !storage.get("waNudge")) {
    let shown = false;
    const showBubble = () => {
      if (shown || (modal && modal.open)) return;
      shown = true;
      bubble.hidden = false;
      storage.set("waNudge", "1");
    };
    const timer = setTimeout(showBubble, 20000);
    const onDeepScroll = () => {
      if (window.scrollY > (document.documentElement.scrollHeight - window.innerHeight) / 3) {
        showBubble();
        document.removeEventListener("scroll", onDeepScroll);
      }
    };
    document.addEventListener("scroll", onDeepScroll, { passive: true });
    bubble.querySelector(".wa-bubble-close").addEventListener("click", () => {
      bubble.hidden = true;
      clearTimeout(timer);
    });
  }

  // Forms: send without leaving the page
  document.querySelectorAll(".js-form").forEach((form) => {
    const status = form.querySelector(".form-status");
    const button = form.querySelector("button[type=submit]");
    const buttonText = button.textContent;
    const oneOf = form.dataset.oneOf ? form.dataset.oneOf.split(",").map((id) => document.getElementById(id)) : [];

    const showStatus = (text, ok) => {
      status.textContent = text;
      status.className = "form-status " + (ok ? "ok" : "error");
    };

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      let firstInvalid = null;
      form.querySelectorAll("[required]").forEach((field) => {
        const bad = !field.value.trim();
        field.setAttribute("aria-invalid", String(bad));
        if (bad && !firstInvalid) firstInvalid = field;
      });
      // At least one way to reach the visitor (email or phone)
      if (oneOf.length) {
        const missing = oneOf.every((field) => !field.value.trim());
        oneOf.forEach((field) => field.setAttribute("aria-invalid", String(missing)));
        if (missing && !firstInvalid) firstInvalid = oneOf[0];
      }
      if (firstInvalid) {
        showStatus(form.dataset.invalidMessage || "Please fill in your name, email or phone, and a message.", false);
        firstInvalid.focus();
        return;
      }

      button.disabled = true;
      button.textContent = "Sending...";
      try {
        const res = await fetch(form.action, {
          method: "POST",
          body: new FormData(form),
          headers: { "X-Requested-With": "fetch" }
        });
        const data = await res.json();
        showStatus(data.message, data.ok);
        if (data.ok) form.reset();
      } catch (err) {
        showStatus("Sorry, something went wrong. Please call or WhatsApp us instead.", false);
      } finally {
        button.disabled = false;
        button.textContent = buttonText;
      }
    });
  });
})();
