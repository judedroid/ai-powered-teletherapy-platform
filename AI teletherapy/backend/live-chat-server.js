const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

const clients = new Map();

wss.on('connection', (ws) => {
    ws.on('message', (message) => {
        const parsedMessage = JSON.parse(message);
        const { userId, content } = parsedMessage;

        // Broadcast message to all connected clients
        clients.forEach((client, id) => {
            if (id !== userId) {
                client.send(JSON.stringify({ userId, content }));
            }
        });
    });

    ws.on('close', () => {
        clients.delete(ws.userId);
    });

    ws.on('error', (error) => {
        console.error('WebSocket error:', error);
    });
});

console.log('WebSocket server running on ws://localhost:8080');
