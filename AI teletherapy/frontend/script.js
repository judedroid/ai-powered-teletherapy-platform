document.getElementById('contact-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = {
        name: e.target.name.value,
        email: e.target.email.value,
        subject: e.target.subject.value,
        message: e.target.message.value,
    };

    try {
        const response = await fetch('http://localhost:3000/contact', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData),
        });

        if (response.ok) {
            alert('Form submitted successfully.');
            e.target.reset();
        } else {
            alert('Failed to submit the form.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while submitting the form.');
    }
});
