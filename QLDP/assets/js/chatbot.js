class Chatbot {
    constructor() {
        this.container = document.querySelector('.chatbot-container');
        this.trigger = document.querySelector('.chat-trigger');
        this.messagesContainer = document.querySelector('.chatbot-messages');
        this.input = document.querySelector('.chatbot-input input');
        this.sendButton = document.querySelector('.send-button');
        
        this.initializeEventListeners();
        this.initializeDraggable();
    }

    initializeEventListeners() {
        // Toggle chatbot
        this.trigger.addEventListener('click', () => this.toggleChat());
        document.querySelector('.close-chat').addEventListener('click', () => this.toggleChat());

        // Send message
        this.sendButton.addEventListener('click', () => this.sendMessage());
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }

    toggleChat() {
        this.container.style.display = 
            this.container.style.display === 'none' ? 'block' : 'none';
        this.trigger.style.display = 
            this.trigger.style.display === 'none' ? 'flex' : 'none';
    }

    async sendMessage() {
        const message = this.input.value.trim();
        if (!message) return;

        // Add user message
        this.addMessage(message, 'user-message');
        this.input.value = '';

        try {
            const response = await fetch('/QLDP/chatbot/process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}`
            });
            const data = await response.json();
            this.addMessage(data.response, 'bot-message');
        } catch (error) {
            this.addMessage('Xin lỗi, có lỗi xảy ra!', 'bot-message');
        }
    }

    addMessage(content, className) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', className);
        messageDiv.textContent = content;
        this.messagesContainer.appendChild(messageDiv);
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }

    initializeDraggable() {
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;

        const dragStart = (e) => {
            if (e.target.closest('.chatbot-header')) {
                initialX = e.clientX - xOffset;
                initialY = e.clientY - yOffset;
                isDragging = true;
            }
        };

        const drag = (e) => {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;
                this.container.style.transform = 
                    `translate(${currentX}px, ${currentY}px)`;
            }
        };

        const dragEnd = () => isDragging = false;

        this.container.addEventListener('mousedown', dragStart);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => new Chatbot());