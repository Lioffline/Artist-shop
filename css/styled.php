<?php function profile_settings_setup(){
    ?>
        .icon-user {
        margin-right: 5px;
        }
        body {
            background-color: #14161a; /* Цвет фона страницы */
        }
        .cards-container {
            background-color: #14161a; /* Цвет фона контейнера карточек */
            padding: 20px; /* Добавляем отступы */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px; /* Расстояние между карточками */
            justify-items: center;
        }
        .card {
            background-color: #14161a;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
            border: none; /* Убрать обводку */
            max-width: 300px; /* Максимальная ширина карточки */
            position: relative;
        }
        container.image.is-128x128.is-inline-block {
            width: 128px;
            height: 128px;
            overflow: hidden; /* Чтобы скрыть части изображения, выходящие за пределы контейнера */
        }
        figure.image.is-128x128.is-inline-block {
            width: 128px;
            height: 128px;
            overflow: hidden; /* Чтобы скрыть части изображения, выходящие за пределы контейнера */
        }
        .card img {
            width: 100%;
            height: 100%; /* Задаем высоту и ширину изображения равными размеру контейнера */
            object-fit: cover; /* Обрезка изображения до квадратной формы */
        }
        .card:hover img {
            opacity: 0.5; /* Затемнение изображения при наведении */
        }
        .card .info {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            color: white;
            font-size: 12px;
            text-align: left;
            opacity: 0;
            transition: opacity 0.3s ease; /* Плавное появление при наведении */
        }
        .card:hover .info {
            opacity: 1; /* Показать информацию при наведении */
        }
        .card .info .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-align: left; /* Выровнять по левому краю */
        }
        .card .info .avatar {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .card .info .avatar img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .card-footer-item {
            display: flex; /* Применяем flex для управления расположением элементов */
            align-items: center; /* Выравниваем элементы по вертикали */
        }

        .card-footer-item i {
            margin-right: 5px; /* Добавляем правый отступ между иконкой и текстом */
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50vh; /* Задаем полную высоту видимой области */
        }
        .card-footer-item {
        margin-right: 10px; /* Отступ между кнопками */
        }
        .post-container {
        max-width: 800px;
        width: auto;
        margin: 20px auto;
        padding: 20px;
        background-color: #1d2125;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 0px;
        }
        .post-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
<?php }

function index_settings_setup(){
    ?>
        .icon-user {
        margin-right: 5px;
        }
        body {
            background-color: #14161a; /* Цвет фона страницы */
        }
        .cards-container {
            background-color: #14161a; /* Цвет фона контейнера карточек */
            padding: 20px; /* Добавляем отступы */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px; /* Расстояние между карточками */
            justify-items: center;
            animation-name: expandPosts;
            animation-duration: 1s;
            animation-fill-mode: forwards;
        }


        @keyframes expandPosts {
            from {
                height: 0;
                opacity: 0;
            }
            to {
                height: auto;
                opacity: 1;
            }
        }
        .card {
            background-color: #14161a;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
            border: none; /* Убрать обводку */
            max-width: 300px; /* Максимальная ширина карточки */
            position: relative;
        }
        .card img {
            width: 100%;
            height: auto; /* Автоматическая высота для сохранения пропорций */
            object-fit: cover; /* Обрезка изображения для заполнения карточки */
            transition: opacity 0.3s ease; /* Анимация затемнения */
        }
        .card:hover img {
            opacity: 0.5; /* Затемнение изображения при наведении */
        }
        .card .info {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            color: white;
            font-size: 12px;
            text-align: left;
            opacity: 0;
            transition: opacity 0.3s ease; /* Плавное появление при наведении */
        }
        .card:hover .info {
            opacity: 1; /* Показать информацию при наведении */
        }
        .card .info .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-align: left; /* Выровнять по левому краю */
        }
        .card .info .avatar {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .card .info .avatar img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }
<?php }

function chat_settings_setup(){
    ?>

        .chat-container {
            display: flex;
            height: calc(95vh - 52px);
        }
        .chat-list {
            width: 25%;
            overflow-y: auto;
            background-color: #1d2125;
        }
        .chat-content {
            width: 80%;
            padding: 1em;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .chat-item {
            margin-top: 10;
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        .chat-item:hover {
            background-color: #444;
        }
        .chat-item img {
            border-radius: 10%;
            margin-right: 10px;
            width: 50px;
            height: 50px;
            overflow: hidden;
            display: flex;
            object-fit: cover;
        }
        .chat-item .chat-info {
            flex-grow: 1;
        }
        .chat-item .chat-info .chat-name {
            font-weight: bold;
            color: #fff;
        }

        .chat-item .chat-actions {
            display: none;
            position: relative;
        }
        .chat-item:hover .chat-actions {
            display: flex;
            align-items: center;
        }
        .chat-actions-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #1d2125;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
            border-radius: 5px;
            display: none;
            z-index: 10;
        }
        .chat-item:hover .chat-actions-menu {
            display: block;
        }
        .chat-actions-menu .menu-item {
            padding: 8px 12px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .chat-actions-menu .menu-item:hover {
            background-color: #444;
        }

        .message {
            padding: 0px;
        }
        .message.sent {
            text-align: right;
        }
        .message-content {
            display: inline-block;
            padding: 10px;
            border-radius: 5px;
            background-color: #1d2125;
        }
        .message.sent .message-content {
            background-color: #1d2125;
            color: white;
        }
        .messages-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1em;
        }
        .message-form {
            display: flex;
            margin-top: 20px;
            position: relative;
        }
        .message-form textarea {
            flex-grow: 1;
            margin-right: 10px;
        }

        .icon-user {
            margin-right: 5px;
        }

        .message-image {
            max-width: 50%;
            height: auto;
            display: block;
        }


        .input-wrapper {
            display: flex;
            flex-grow: 1;
            align-items: center;
            position: relative;
        }

        .input-wrapper textarea {
            flex-grow: 1;
            margin: 0;
            padding-left: 60x; /* Добавить отступ слева для кнопки кошелька */
            padding-right: 60px; /* Добавить отступ справа для кнопки отправки */
            resize: none;
            min-height: 40px;
            overflow: hidden;
            border-radius: 20px;
        }

        .input-wrapper .button.is-wallet {
            left: 20px;
            background: transparent;
            border: none;
            color: #fff;
            z-index: 999;
        }

        .input-wrapper .button.is-send-message {
            position: absolute;
            right: 10px;
            background: #3273dc;
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Убедитесь, что текст не обтекает кнопки */
        .input-wrapper {
            flex-grow: 1;
            position: relative;
        }

        .input-wrapper textarea {
            width: 100%;
            min-height: 40px;
            max-height: 150px; /* Ограничение максимальной высоты */
            border-radius: 20px;
            padding: 10px 40px; /* Отступы для кнопок */
        }

        .input-wrapper .button.is-wallet,
        .input-wrapper .button.is-send-message {
            position: absolute;
            top: 70%;
            background: none;
            border: none;
            color: #fff;
        }

        .input-wrapper .button.is-wallet {
            left: -5px;
        }

        .input-wrapper .button.is-send-message {
            right: 0px;

            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-wrapper .button.is-send-message i,
        .input-wrapper .button.is-wallet i {
            font-size: 18px;
        }

<?php }

function scrollbar_settings_setup(){
    ?>
       /* Для Chrome, Safari и Edge */
        ::-webkit-scrollbar {
            width: 8px; 
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }

        ::-webkit-scrollbar-thumb {
            background: #888; 
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }

        /* Для Firefox */
        .scrollable-element {
            scrollbar-width: thin; 
            scrollbar-color: #888 #f1f1f1;
        }
<?php }

 

function search_settings_setup(){
    ?>
        .icon-user {
        margin-right: 5px;
        }
        body {
            background-color: #14161a; /* Цвет фона страницы */
        }
        .cards-container {
            background-color: #14161a; /* Цвет фона контейнера карточек */
            padding: 20px; /* Добавляем отступы */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px; /* Расстояние между карточками */
            justify-items: center;
        }
        .card {
            background-color: #14161a;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
            border: none; /* Убрать обводку */
            max-width: 300px; /* Максимальная ширина карточки */
            position: relative;
        }
        .card img {
            width: 100%;
            height: auto; /* Автоматическая высота для сохранения пропорций */
            object-fit: cover; /* Обрезка изображения для заполнения карточки */
            transition: opacity 0.3s ease; /* Анимация затемнения */
        }
        .card:hover img {
            opacity: 0.5; /* Затемнение изображения при наведении */
        }
        .card .info {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            color: white;
            font-size: 12px;
            text-align: left;
            opacity: 0;
            transition: opacity 0.3s ease; /* Плавное появление при наведении */
        }
        .card:hover .info {
            opacity: 1; /* Показать информацию при наведении */
        }
        .card .info .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-align: left; /* Выровнять по левому краю */
        }
        .card .info .avatar {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .card .info .avatar img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }
<?php }

function post_settings_setup(){
    ?>
        body {
            background-color: #14161a;
            color: #b5b5b5;
        }
        .post-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #1d2125;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .post-header img {
            width: 50px;
            height: 50px;
            border-radius: 10%;
            overflow: hidden;
            display: flex;
            margin-right: 10px;
            object-fit: cover;
        }
        .post-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .post-subtitle {
            font-size: 1rem;
            color: #808080;
        }
        .post-content {
            margin-bottom: 20px;
        }
        .post-content img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .post-tags {
            margin-top: 10px;
            color: #808080;
        }
        .tag {
            margin-right: 5px;
            background-color: #363636;
            color: #b5b5b5;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .post-meta {
            margin-top: 10px;
            color: #808080;
        }
        .delete-post-btn {
            background-color: #ff3860;
            color: white;
            padding: 15px 30px;
            border-radius: 4px;
            cursor: pointer;
        }
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            color: #808080;
        }

        .posted-by {
            flex: 1;
        }

        .post-options {
            flex-shrink: 0; 
        }

        .delete-post-btn {
            background-color: #ff3860;
            color: white;
            padding: 10px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .options-icon {
            cursor: pointer;
            color: #808080;
        }

        .options-menu {
            display: none;
            position: absolute;
            background-color: #1d2125;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000; 
        }

        .post-options:hover .options-menu {
            display: block;
        }

        .options-menu button {
            background-color: #ff3860;
            color: white;
            padding: 10px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
        }
<?php }

function edit_settings_setup(){
    ?>
        .icon-user {
        margin-right: 5px;
        }
        .menu-container {
            display: flex;
        }
        .menu {
            width: 60%;
        }
        .content {
            width: 75%;
            padding: 20px;
        }
        .hidden {
            display: none;
        }
        .avatar-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .avatar-container img {
            display: block;
            transition: opacity 0.3s ease;
        }

        .avatar-container:hover img {
            opacity: 0.7;
        }

        .avatar-container .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .avatar-container:hover .overlay {
            opacity: 1;
        }
<?php }

function createpost_settings_setup(){
    ?>
        .icon-user {
            margin-right: 5px;
        }
        .menu-container {
            display: flex;
        }
        .menu {
            width: 20%;
            margin-right: 20px;
        }
        .content {
            width: 80%;
            padding: 20px;
        }
        .hidden {
            display: none;
        }
        .avatar-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            background-color: #f0f0f0;
            text-align: center;
            overflow: hidden; /* Ensure image doesn't overflow */
            border-radius: 10px;
            width: 100%; /* Ensure container fills its parent */
        }
        .avatar-container img {
            display: block;
            width: 100%; /* Ensure image fills its container */
            height: auto;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }
        .avatar-container .placeholder {
            font-size: 2em;
            color: #ccc;
            width: 100%;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-container:hover img {
            opacity: 0.7;
        }
        .avatar-container .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .avatar-container:hover .overlay {
            opacity: 1;
        }
        .post-container {
            display: flex;
            justify-content: space-between;
            border-radius: 10px;
        }
        .post-image-container {
            flex-basis: 35%;
        }
        .post-details {
            flex-basis: 50%;
        }
<?php }


function navbar_settings_setup(){
    ?>
        .fixed-navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000; /* Установите z-index таким образом, чтобы меню было поверх других элементов */
        }
        body {
            padding-top: 50px; /* Вы можете настроить высоту отступа по вашему усмотрению */
        }
<?php }

function avatar_settings_setup($size) {
    $containerSize = ($size == 1)? '128px' : '50px';
    $borderRadius = '10%';

    echo <<<EOT
    .avatar-container {
        width: {$containerSize};
        height: {$containerSize};
        border-radius: {$borderRadius};
        overflow: hidden;
        display: flex;
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    EOT;
}

