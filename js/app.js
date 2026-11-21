// The shell's JavaScript. Owner: shared kit. Nobody edits this file; ask the kit owner.
//
// What it gives every part:
//   loadPage('contact', {id: 7})      load pages/contact.php?id=7 into the main area (no refresh)
//   loadFragment('#notes-section', 'notes', {contact_id: 7})   load a piece into any element
//   apiPost('api/add_note.php', form)  send a form or object, get the JSON reply back
//   showReply(box, reply)              show reply.message in a .message box
//   showFieldErrors(form, reply.errors) fill the .field-error spans
//   Pages.name = function (container, params) { ... }   your page's script registers itself here
//
// Any element with data-page="name" (and optional data-id="7") opens that page when clicked.

const Pages = {};
const loadedFiles = {};

function loadFile(kind, path) {
    if (loadedFiles[path]) return loadedFiles[path];
    loadedFiles[path] = new Promise((resolve) => {
        const el = document.createElement(kind === 'css' ? 'link' : 'script');
        if (kind === 'css') { el.rel = 'stylesheet'; el.href = path; } else { el.src = path; }
        el.onload = resolve;
        el.onerror = resolve;          // a page does not have to have its own css or js
        document.head.appendChild(el);
    });
    return loadedFiles[path];
}

async function loadFragment(target, name, params = {}) {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return;
    const query = new URLSearchParams(params).toString();
    const res = await fetch('pages/' + name + '.php' + (query ? '?' + query : ''));
    if (res.status === 401) { window.location = 'login.php'; return; }
    if (!res.ok) {
        el.innerHTML = '<p class="message error">Could not load this page (error ' + res.status + ').</p>';
        return;
    }
    el.innerHTML = await res.text();
    await Promise.all([loadFile('css', 'css/pages/' + name + '.css'), loadFile('js', 'js/pages/' + name + '.js')]);
    if (typeof Pages[name] === 'function') Pages[name](el, params);
}

function loadPage(name, params = {}) {
    document.querySelectorAll('.menu a[data-page]').forEach((a) => a.classList.toggle('active', a.dataset.page === name));
    return loadFragment('#content', name, params);
}

async function apiPost(url, data) {
    const body = data instanceof HTMLFormElement ? new FormData(data) : data instanceof FormData ? data : toFormData(data);
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && !body.has('csrf_token')) body.append('csrf_token', meta.content);   // CSRF token (lecture 8)
    const res = await fetch(url, { method: 'POST', body });
    if (res.status === 401) { window.location = 'login.php'; return { success: false, message: 'Please log in.', errors: {}, data: {} }; }
    return res.json();
}
async function apiGet(url, params = {}) {
    const query = new URLSearchParams(params).toString();
    const res = await fetch(url + (query ? '?' + query : ''));
    if (res.status === 401) { window.location = 'login.php'; return { success: false, message: 'Please log in.', errors: {}, data: {} }; }
    return res.json();
}
function toFormData(obj) {
    const fd = new FormData();
    Object.entries(obj || {}).forEach(([k, v]) => fd.append(k, v));
    return fd;
}

function showReply(box, reply) {
    box.textContent = reply.message || '';
    box.className = 'message ' + (reply.success ? 'success' : 'error');
}
function showFieldErrors(form, errors) {
    form.querySelectorAll('.field-error').forEach((s) => (s.textContent = ''));
    Object.entries(errors || {}).forEach(([field, text]) => {
        const span = form.querySelector('.field-error[data-for="' + field + '"]');
        if (span) span.textContent = text;
    });
}

document.addEventListener('click', (event) => {
    const link = event.target.closest('[data-page]');
    if (!link) return;
    event.preventDefault();
    const params = {};
    Object.entries(link.dataset).forEach(([key, value]) => { if (key !== 'page') params[key] = value; });
    loadPage(link.dataset.page, params);
});
