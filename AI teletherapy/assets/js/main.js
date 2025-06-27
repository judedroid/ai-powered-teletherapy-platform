document.addEventListener("DOMContentLoaded", () => {
    console.log("AI Teletherapy Platform Loaded");
});

// API setup
const API_KEY = "AIzaSyCuT3Nu62nuMPMLTIhniCGfzNwWb2Cs35o";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`;

// Chatbot class to handle both contexts
class Chatbot {
    constructor(chatBodySelector, messageInputSelector, sendButtonSelector) {
        this.chatBody = document.querySelector(chatBodySelector);
        this.messageInput = document.querySelector(messageInputSelector);
        this.sendButton = document.querySelector(sendButtonSelector);

        if (this.sendButton) {
            this.sendButton.addEventListener("click", (event) => this.handleSendMessage(event));
        }
    }

    // Create a message element
    createMessageElement(message, isBot = false) {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", isBot ? "bot-message" : "user-message");
        const messageContent = document.createElement("p");
        messageContent.textContent = message;
        messageDiv.appendChild(messageContent);
        return messageDiv;
    }

    // Send a message to the API and handle the response
    async sendMessageToAPI(userMessage) {
        // Add user message to the chat
        const userMessageElement = this.createMessageElement(userMessage);
        this.chatBody.appendChild(userMessageElement);
        this.chatBody.scrollTo({ top: this.chatBody.scrollHeight, behavior: "smooth" });

        // Add a placeholder for the bot's response
        const botMessageElement = this.createMessageElement("...", true);
        this.chatBody.appendChild(botMessageElement);
        this.chatBody.scrollTo({ top: this.chatBody.scrollHeight, behavior: "smooth" });

        // Prepare the API request
        const requestBody = {
            prompt: {
                text: userMessage,
            },
        };

        try {
            const response = await fetch(API_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(requestBody),
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error("API Error:", errorData);
                throw new Error("Failed to fetch response from the API.");
            }

            const data = await response.json();
            console.log("API Response:", data); // Log the full API response for debugging
            const botResponse = data.candidates[0]?.content || "I'm sorry, I couldn't process that.";

            // Update the bot's response
            botMessageElement.querySelector("p").textContent = botResponse;
        } catch (error) {
            console.error("Error:", error);
            botMessageElement.querySelector("p").textContent = "An error occurred. Please try again.";
        }
    }

    // Handle sending a message
    handleSendMessage(event) {
        event.preventDefault();
        const userMessage = this.messageInput.value.trim();
        if (userMessage) {
            this.sendMessageToAPI(userMessage);
            this.messageInput.value = ""; // Clear the input field
        }
    }
}

// Initialize chatbot for the chatbot area (embedded in other pages)
if (document.querySelector(".chatbot-popup")) {
    const chatbotPopup = new Chatbot(".chat-body", ".message-input", "#send-message");
}

// Initialize chatbot for the dedicated chat page (chat.html)
if (document.querySelector(".chat-container")) {
    const chatPage = new Chatbot(".chat-body", "#message-input", "#send-message");
}
