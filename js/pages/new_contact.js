// New contact form. Owner: Part 3.
Pages.new_contact = function (container) {
    const form = container.querySelector('#contact-form');
    const box = container.querySelector('#contact-message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        box.textContent = '';
        box.className = 'message';

        const reply = await apiPost('api/add_contact.php', form);

        showFieldErrors(form, reply.errors);
        showReply(box, reply);

        if (reply.success) {
            loadPage('contact', { id: reply.data.id });   // open the new contact's details
        }
    });
};
