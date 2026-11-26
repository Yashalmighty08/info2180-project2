// Contact details screen. Owner: Part 4.
// Fills pages/contact.php from api/contact_get.php, runs the two buttons, and hosts Part 5's notes.
//
// Agreements with other parts:
//   - Opened with loadPage('contact', { id }) (the Dashboard's View link uses data-page="contact" data-id="N").
//   - Loads Part 5 into <section id="notes-section"> with loadFragment('#notes-section', 'notes', { contact_id }).
//   - Listens for the document event 'contact:changed' (Part 5 fires it after saving a note) and redraws.

// Only one contact screen is open at a time, so only one 'contact:changed' listener should be active.
let contactChangedHandler = null;

Pages.contact = function (container, params) {
    const view = container.querySelector('#contact');
    if (!view) return;

    const contactId = view.dataset.id;
    const messageBox = view.querySelector('#contact-msg');
    const assignBtn = view.querySelector('#assign-btn');
    const switchBtn = view.querySelector('#switch-btn');

    // Copy each value from the reply into the element with the matching data-field.
    // textContent (never innerHTML) keeps whatever was typed into the database from running as HTML.
    function fillFields(details) {
        Object.entries(details).forEach(([field, value]) => {
            view.querySelectorAll('[data-field="' + field + '"]').forEach((el) => {
                el.textContent = value;
            });
        });

        switchBtn.textContent = details.type === 'Sales Lead' ? 'Switch to Support' : 'Switch to Sales Lead';

        assignBtn.disabled = details.assigned_to_me;
        assignBtn.textContent = details.assigned_to_me ? 'Assigned to you' : 'Assign to me';
    }

    function showNotFound(text) {
        view.replaceChildren();
        const p = document.createElement('p');
        p.className = 'message error';
        p.textContent = text;
        view.appendChild(p);
    }

    async function refreshDetails() {
        // If another screen replaced this one, stop listening instead of redrawing a detached page.
        if (!document.body.contains(view)) {
            stopListening();
            return;
        }
        const reply = await apiGet('api/contact_get.php', { id: contactId });
        if (!reply.success) {
            showNotFound(reply.message || 'Contact not found.');
            stopListening();
            return;
        }
        fillFields(reply.data);
    }

    // Both buttons work the same way: post the id, show the reply, redraw the details.
    async function runAction(button, url) {
        button.disabled = true;
        try {
            const reply = await apiPost(url, { id: contactId });
            showReply(messageBox, reply);
        } catch (err) {
            showReply(messageBox, { success: false, message: 'Something went wrong. Please try again.' });
        }
        button.disabled = false;
        await refreshDetails();
    }

    function stopListening() {
        if (contactChangedHandler) {
            document.removeEventListener('contact:changed', contactChangedHandler);
            contactChangedHandler = null;
        }
    }

    assignBtn.addEventListener('click', () => runAction(assignBtn, 'api/assign_contact.php'));
    switchBtn.addEventListener('click', () => runAction(switchBtn, 'api/switch_type.php'));

    // Replace any listener left over from a previously opened contact.
    stopListening();
    contactChangedHandler = refreshDetails;
    document.addEventListener('contact:changed', contactChangedHandler);

    refreshDetails();
    loadFragment('#notes-section', 'notes', { contact_id: contactId });
};
