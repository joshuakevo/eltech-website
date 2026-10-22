(function () {
  "use strict";

  const csrf = () => document.querySelector('meta[name="csrf-token"]').content;

  const post = async (url, data) => {
    const res = await fetch(url, {
      method: "POST",
      body: data,
      headers: { "X-CSRF-Token": csrf() },
      credentials: "same-origin"
    });
    return res.json();
  };

  const setStatus = (el, text, ok) => {
    if (!el) return;
    el.textContent = text;
    el.className = el.className.replace(/\s?(ok|error)/g, "") + (ok ? " ok" : " error");
  };

  /* ---------- Sign in ---------- */

  // Called by Google's button with the signed-in user's ID token
  window.onGoogleSignIn = async (response) => {
    const status = document.querySelector(".signin-status");
    setStatus(status, "Signing you in...", true);
    const data = new FormData();
    data.append("credential", response.credential);
    try {
      const result = await post("api/auth.php", data);
      if (result.ok) location.reload();
      else setStatus(status, result.message, false);
    } catch (e) {
      setStatus(status, "Sign-in failed. Please try again.", false);
    }
  };

  const devForm = document.querySelector("[data-dev-login]");
  if (devForm) {
    devForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = new FormData(devForm);
      data.append("dev", "1");
      const result = await post("api/auth.php", data);
      if (result.ok) location.reload();
      else setStatus(document.querySelector(".signin-status"), result.message, false);
    });
  }

  /* ---------- Notify me (coming soon courses) ---------- */

  document.querySelectorAll("[data-notify]").forEach((btn) => {
    btn.addEventListener("click", async () => {
      btn.disabled = true;
      const data = new FormData();
      data.append("course", btn.dataset.notify);
      const result = await post("api/notify.php", data);
      const done = document.createElement("p");
      done.className = "notify-done";
      done.innerHTML = '<i class="bi bi-bell-fill" aria-hidden="true"></i> ';
      done.append(result.message);
      btn.replaceWith(done);
    });
  });

  /* ---------- Simple forms (task, unlock request) ---------- */

  document.querySelectorAll(".js-academy-form").forEach((form) => {
    const status = form.querySelector(".form-status");
    const button = form.querySelector("button[type=submit]");
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (!form.reportValidity()) return;
      button.disabled = true;
      try {
        const result = await post(form.dataset.endpoint, new FormData(form));
        setStatus(status, result.message, result.ok);
        if (result.ok && form.hasAttribute("data-reload")) setTimeout(() => location.reload(), 1200);
        if (result.ok && form.classList.contains("task-form")) celebrate(30);
      } catch (err) {
        setStatus(status, "Something went wrong. Please try again.", false);
      } finally {
        button.disabled = false;
      }
    });
  });

  /* ---------- Quiz ---------- */

  const quiz = document.querySelector(".quiz-form");
  if (quiz) {
    const resultBox = quiz.querySelector(".quiz-result");
    const button = quiz.querySelector("button[type=submit]");

    quiz.addEventListener("submit", async (e) => {
      e.preventDefault();
      const unanswered = [...quiz.querySelectorAll(".question")].find((q) => !q.querySelector("input:checked"));
      if (unanswered) {
        resultBox.hidden = false;
        resultBox.className = "quiz-result is-fail";
        resultBox.textContent = "Please answer every question first.";
        unanswered.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
      }

      const data = new FormData(quiz);
      data.append("course", quiz.dataset.course);
      data.append("lesson", quiz.dataset.lesson);
      button.disabled = true;

      let result;
      try {
        result = await post("api/quiz.php", data);
      } catch (err) {
        result = { ok: false, message: "Something went wrong. Please try again." };
      }
      button.disabled = false;

      if (!result.ok) {
        resultBox.hidden = false;
        resultBox.className = "quiz-result is-fail";
        resultBox.textContent = result.message;
        return;
      }

      // Mark each question
      quiz.querySelectorAll(".question").forEach((q, i) => {
        const r = result.results[i];
        q.classList.remove("is-right", "is-wrong");
        q.classList.add(r.correct ? "is-right" : "is-wrong");
        const fb = q.querySelector(".question-feedback");
        fb.hidden = false;
        fb.innerHTML = r.correct
          ? '<i class="bi bi-check-circle-fill" aria-hidden="true"></i> '
          : '<i class="bi bi-lightbulb" aria-hidden="true"></i> ';
        fb.append((r.correct ? "Correct. " : "Not quite. ") + r.why);
      });

      resultBox.hidden = false;
      resultBox.innerHTML = "";
      if (result.passed) {
        resultBox.className = "quiz-result is-pass";
        const title = document.createElement("strong");
        title.textContent = result.score === result.total ? "Perfect score! " : "You passed! ";
        resultBox.append(title, `${result.score} out of ${result.total} correct.`);
        if (result.points) {
          const pts = document.createElement("span");
          pts.className = "points-pop";
          pts.textContent = `+${result.points} points`;
          resultBox.append(pts);
        }
        const next = document.createElement("a");
        next.className = "btn btn-primary";
        if (result.next_url) {
          next.href = result.next_url;
          next.textContent = result.next_locked ? "Unlock the next lesson" : "Next lesson";
        } else {
          next.href = "course.php?c=" + encodeURIComponent(quiz.dataset.course);
          next.textContent = "Back to the course";
        }
        resultBox.append(next);

        // Enable the bottom "Next lesson" button too
        const bottomNext = document.querySelector(".next-lesson");
        if (bottomNext) {
          bottomNext.classList.remove("is-disabled");
          bottomNext.removeAttribute("aria-disabled");
          if (result.next_url) bottomNext.href = result.next_url;
        }
        celebrate(result.score === result.total ? 80 : 50);
      } else {
        resultBox.className = "quiz-result is-fail";
        const title = document.createElement("strong");
        title.textContent = "Almost there. ";
        resultBox.append(title, `${result.score} out of ${result.total} correct. Read the hints above and try again.`);
      }
    });

    // Changing an answer clears its old marking
    quiz.addEventListener("change", (e) => {
      const q = e.target.closest(".question");
      if (!q) return;
      q.classList.remove("is-right", "is-wrong");
      q.querySelector(".question-feedback").hidden = true;
    });
  }

  // Disabled "Next lesson" explains itself
  document.querySelectorAll(".next-lesson").forEach((link) => {
    link.addEventListener("click", (e) => {
      if (!link.classList.contains("is-disabled")) return;
      e.preventDefault();
      document.getElementById("quiz").scrollIntoView({ behavior: "smooth" });
    });
  });

  /* ---------- A little celebration ---------- */

  function celebrate(count) {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const colors = ["#1565c0", "#2563eb", "#f59e0b", "#16a34a", "#e11d48", "#8db8ff"];
    const layer = document.createElement("div");
    layer.className = "confetti";
    layer.setAttribute("aria-hidden", "true");
    for (let i = 0; i < count; i++) {
      const piece = document.createElement("span");
      piece.style.left = Math.random() * 100 + "vw";
      piece.style.background = colors[i % colors.length];
      piece.style.animationDelay = Math.random() * 0.4 + "s";
      piece.style.animationDuration = 1.6 + Math.random() * 1.2 + "s";
      piece.style.transform = `rotate(${Math.random() * 360}deg)`;
      layer.appendChild(piece);
    }
    document.body.appendChild(layer);
    setTimeout(() => layer.remove(), 3500);
  }
})();
