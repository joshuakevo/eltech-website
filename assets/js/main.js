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

  // "Request a demo" links preselect the matching product in the contact form
  const interest = document.getElementById("interest");
  document.querySelectorAll("[data-interest]").forEach((link) => {
    link.addEventListener("click", () => { interest.value = link.dataset.interest; });
  });

  document.getElementById("year").textContent = new Date().getFullYear();

  // Christmas offer: shows itself only between these dates (Kampala time)
  const OFFER = { start: "2026-12-01T00:00:00+03:00", end: "2027-01-01T00:00:00+03:00", discount: 0.2 };
  const now = new Date();
  const offerOn = now >= new Date(OFFER.start) && now < new Date(OFFER.end);
  if (offerOn) {
    document.querySelectorAll("[data-offer]").forEach((el) => { el.hidden = false; });
    const offerBar = document.getElementById("offer-bar");
    offerBar.querySelector(".offer-close").addEventListener("click", () => { offerBar.hidden = true; });
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

  // WhatsApp nudge: shown once per visit, after 20 seconds or once the visitor reaches the services
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
    const services = document.getElementById("services");
    const onDeepScroll = () => {
      if (services.getBoundingClientRect().top < window.innerHeight * 0.5) {
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
        showStatus(oneOf.length
          ? "Please add your name, an email or phone number, and your idea."
          : "Please fill in your name, email or phone, and a message.", false);
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
