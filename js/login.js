// Login form. Owner: Member 1. Sends the form with fetch and follows the reply.
const form = document.getElementById('login-form');
const message = document.getElementById('login-message');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    message.textContent = '';
    message.className = 'message';
    document.querySelectorAll('.field-error').forEach((s) => (s.textContent = ''));

    const res = await fetch('api/login.php', { method: 'POST', body: new FormData(form) });
    const reply = await res.json();

    if (reply.success) {
        window.location = reply.data.redirect;
        return;
    }
    message.textContent = reply.message;
    message.className = 'message error';
    for (const [field, text] of Object.entries(reply.errors)) {
        const span = document.querySelector('.field-error[data-for="' + field + '"]');
        if (span) span.textContent = text;
    }
});
