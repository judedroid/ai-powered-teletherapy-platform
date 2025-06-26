const express = require('express');
const bodyParser = require('body-parser');
const bcrypt = require('bcrypt');
const cors = require('cors');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Mock database (replace with a real database in production)
const users = [
    { username: 'user', password: bcrypt.hashSync('password', 10) }, // Password is hashed
];

// Mock database for testimonials
const testimonials = [];

// Login endpoint
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    // Find user in the database
    const user = users.find((u) => u.username === username);
    if (!user) {
        return res.status(401).json({ message: 'Invalid username or password' });
    }

    // Compare passwords
    const isPasswordValid = bcrypt.compareSync(password, user.password);
    if (!isPasswordValid) {
        return res.status(401).json({ message: 'Invalid username or password' });
    }

    // Successful login
    res.status(200).json({ message: 'Login successful' });
});

// Endpoint to handle testimonial submissions
app.post('/testimonials', (req, res) => {
    const { name, message } = req.body;

    if (!name || !message) {
        return res.status(400).json({ message: 'Name and message are required.' });
    }

    // Save the testimonial to the mock database
    testimonials.push({ name, message, date: new Date() });
    res.status(201).json({ message: 'Testimonial submitted successfully.' });
});

// Endpoint to retrieve all testimonials (optional)
app.get('/testimonials', (req, res) => {
    res.status(200).json(testimonials);
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
