<?php

function feed_position(){ ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var grid = document.querySelector('#masonry-grid');
        var msnry = new Masonry(grid, {
            itemSelector: '.card',
            columnWidth: '.card',
            percentPosition: true,
            gutter: 20
        });
    });
</script> <?php
}

function open_bar_script(){ ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        if ($navbarBurgers.length > 0) {
            $navbarBurgers.forEach(function ($el) {
                $el.addEventListener('click', function () {
                    var target = $el.dataset.target;
                    var $target = document.getElementById(target);

                    $el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                    var $createPostButton = document.getElementById('createPostButton');
                    if ($createPostButton) {
                        $createPostButton.classList.toggle('is-hidden-touch');
                    }
                });
            });
        }
    });
</script><?php
}


function edit_script(){ ?>
<script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function checkUsername() {
            const username = document.getElementById('name').value;
            fetch('check_username.php?name=' + encodeURIComponent(username))
                .then(response => response.json())
                .then(data => {
                    const usernameFeedback = document.getElementById('usernameFeedback');
                    if (data.exists) {
                        usernameFeedback.textContent = 'Username is already taken';
                        usernameFeedback.classList.add('has-text-danger');
                        usernameFeedback.classList.remove('has-text-success');
                    } else {
                        usernameFeedback.textContent = 'Username is available';
                        usernameFeedback.classList.add('has-text-success');
                        usernameFeedback.classList.remove('has-text-danger');
                    }
                });
        }

        function openAvatarModal() {
            document.getElementById('changeAvatarModal').classList.add('is-active');
        }

        function updateAvatar() {
            const newAvatarUrl = document.getElementById('newAvatarURL').value;
            if (newAvatarUrl) {
                document.getElementById('avatar').value = newAvatarUrl;
                document.querySelector('.profile-avatar').src = newAvatarUrl;
                document.getElementById('changeAvatarModal').classList.remove('is-active');
            }
        }
    </script> 
<?php
}



function chat_script(){ ?>
         document.addEventListener('DOMContentLoaded', () => {
            // Elements
            const chatOptionsBtns = document.querySelectorAll('.chat-options-btn');
            const editChatBtns = document.querySelectorAll('.edit-chat-btn');
            const deleteChatBtns = document.querySelectorAll('.delete-chat-btn');
            const editChatModal = document.getElementById('editChatModal');
            const chatNameInput = document.getElementById('chatNameInput');
            const chatItems = document.querySelectorAll('.chat-item');
            const messagesContainer = document.getElementById('messages');
            const messageInput = document.getElementById('messageInput');
            const sendMessageBtn = document.getElementById('sendMessageBtn');
            let currentChatId;

            // Function to open edit chat modal
            const openEditChatModal = (chatId, chatName) => {
                currentChatId = chatId;
                chatNameInput.value = chatName;
                editChatModal.classList.add('is-active');
            };

            // Close modal
            document.querySelectorAll('.modal .delete, .modal-card-foot .button').forEach((element) => {
                element.addEventListener('click', () => {
                    editChatModal.classList.remove('is-active');
                });
            });

            // Add event listeners to edit buttons
            editChatBtns.forEach((button) => {
                button.addEventListener('click', (event) => {
                    const chatId = event.currentTarget.getAttribute('data-chat-id');
                    const chatItem = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);
                    const chatName = chatItem.querySelector('.chat-name').innerText;
                    openEditChatModal(chatId, chatName);
                });
            });

            // Save chat name
            document.getElementById('saveChatNameBtn').addEventListener('click', () => {
                const newChatName = chatNameInput.value;
                if (currentChatId && newChatName) {
                    fetch('includes/update_chat_name.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            chat_id: currentChatId,
                            chat_name: newChatName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const chatItem = document.querySelector(`.chat-item[data-chat-id="${currentChatId}"]`);
                            chatItem.querySelector('.chat-name').innerText = newChatName;
                            editChatModal.classList.remove('is-active');
                        } else {
                            alert('Error updating chat name');
                        }
                    });
                }
            });

            // Add event listeners to delete buttons
            deleteChatBtns.forEach((button) => {
                button.addEventListener('click', (event) => {
                    const chatId = event.currentTarget.getAttribute('data-chat-id');
                    if (confirm('Are you sure you want to delete this chat?')) {
                        fetch('includes/delete_chat.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                chat_id: chatId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`.chat-item[data-chat-id="${chatId}"]`).remove();
                            } else {
                                alert('Error deleting chat');
                            }
                        });
                    }
                });
            });

            // Function to load chat messages
            const loadChatMessages = (chatId) => {
                fetch(`includes/load_messages.php?chat_id=${chatId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messagesContainer.innerHTML = '';
                            data.messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.classList.add('message', message.sent_by == <?= $_SESSION['user_id'] ?> ? 'sent' : 'received');
                                // Check if message contains image URLs
                                const messageContent = message.content;
                                if (containsImageURL(messageContent)) {
                                    const imgElement = document.createElement('img');
                                    imgElement.src = messageContent; // Assuming message content is the image URL
                                    imgElement.classList.add('message-image');
                                    messageDiv.appendChild(imgElement);
                                } else {
                                    messageDiv.innerHTML = `<div class="message-content">${messageContent}</div>`;
                                }
                                messagesContainer.appendChild(messageDiv);
                            });
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        } else {
                            messagesContainer.innerHTML = '<p>Error loading messages</p>';
                        }
                    });
            };

            // Function to check if a string contains image URL
            const containsImageURL = (text) => {
                const regex = /(http(s?):)([/|.|\w|\s|-])*\.(?:jpg|gif|png)/gi;
                return regex.test(text);
            };

            // Add event listeners to chat items
            chatItems.forEach((item) => {
                item.addEventListener('click', () => {
                    const chatId = item.getAttribute('data-chat-id');
                    loadChatMessages(chatId);
                    currentChatId = chatId;
                });
            });

            // Send message
            sendMessageBtn.addEventListener('click', () => {
                const messageContent = messageInput.value.trim();
                if (messageContent && currentChatId) {
                    fetch('includes/send_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            chat_id: currentChatId,
                            message_content: messageContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Optionally, you can update the UI to reflect the sent message
                            messageInput.value = ''; // Clear input after sending
                            loadChatMessages(currentChatId); // Reload messages to display the sent message
                        } else {
                            alert('Error sending message');
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                    });
                }
            });

        });
        document.addEventListener('DOMContentLoaded', () => {
        const messageInput = document.getElementById('messageInput');

        messageInput.addEventListener('input', () => {
            setTimeout(() => {
                messageInput.style.height = 'auto';
                messageInput.style.height = `${messageInput.scrollHeight}px`;
            }, 0);
            });
        });
    <?php
    }
    