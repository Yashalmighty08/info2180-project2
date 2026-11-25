// Notes box. Owner: Janae
Pages.notes = function (container, params) {
    const form = container.querySelector('#note-form');
    const box = container.querySelector('#note-message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        box.textContent = '';
        box.className = 'message';
        const reply = await apiPost('api/add_note.php', form);
        showFieldErrors(form, reply.errors);
        if (!reply.success) {
            showReply(box, reply);
            return;
        }
        await loadFragment(container, 'notes', params);              // redraw the list with the new note
        document.dispatchEvent(new CustomEvent('contact:changed'));   // tell the details page
    });
};