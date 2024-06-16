<?php

function edit_chat_name_module(){ ?>
    <!-- Modal for editing chat name -->
    <div class="modal" id="editChatModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Edit Chat</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form id="editChatForm">
                    <div class="field">
                        <label class="label">Chat Name</label>
                        <div class="control">
                            <input type="text" class="input" id="chatNameInput" name="chat_name">
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" id="saveChatNameBtn">Save changes</button>
                <button class="button">Cancel</button>
            </footer>
        </div>
    </div>
<?php }

    /*
        <!-- Новая кнопка для перехода на профиль пользователя -->
        <div class="menu-item view-profile-btn" data-user-id="<?php echo $chat['user_id'];?>">
            <i class="fas fa-user"></i> View Profile
        </div>

        // Обработчик для новой кнопки "View Profile"
        var profileButtons = document.querySelectorAll('.view-profile-btn');
        profileButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var userchatId = this.getAttribute('data-user-id');
                window.location.href = 'profile.php?id=' + encodeURIComponent(userchatId);
            });
        });
    */
