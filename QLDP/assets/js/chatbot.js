class Chatbot {
    constructor() {
        this.container = document.querySelector('.chatbot-container');
        this.trigger = document.querySelector('.chat-trigger');
        this.messagesContainer = document.querySelector('.chatbot-messages');
        this.input = document.querySelector('.chatbot-input input');
        this.sendButton = document.querySelector('.send-button');
        
        // Đảm bảo các phần tử tồn tại
        if (!this.container || !this.trigger || !this.messagesContainer || !this.input || !this.sendButton) {
            console.error('Không tìm thấy các phần tử cần thiết cho chatbot');
            return;
        }

        // Hiển thị nút trigger mặc định
        this.trigger.style.display = 'flex';
        this.container.style.display = 'none';
        
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
        if (this.container.style.display === 'none') {
            this.container.style.display = 'block';
            this.trigger.style.display = 'none';
        } else {
            this.container.style.display = 'none';
            this.trigger.style.display = 'flex';
        }
    }

    async sendMessage() {
        const message = this.input.value.trim();
        if (!message) return;

        // Add user message
        this.addMessage(message, 'user-message');
        this.input.value = '';

        try {
            console.log('Sending message:', message);
            const response = await fetch('/QLDP/chatbot/process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}`
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Received response:', data);
            
            if (!data.response) {
                throw new Error('Invalid response format');
            }
            
            this.addMessage(data.response, 'bot-message');
        } catch (error) {
            console.error('Error:', error);
            this.addMessage('Xin lỗi, có lỗi xảy ra! Chi tiết: ' + error.message, 'bot-message');
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
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing chatbot...');
    new Chatbot();
});