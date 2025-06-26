const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Routes
app.get('/', (req, res) => {
    res.send('AI Teletherapy Backend is running.');
});

app.post('/contact', (req, res) => {
    const { name, email, subject, message } = req.body;
    // Handle form submission logic here (e.g., save to database, send email)
    res.status(200).json({ message: 'Contact form submitted successfully.' });
});

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
