<dialog
    id="nav-popup"
    class="backdrop:backdrop-blur-sm bg-white p-4 pb-8 max-w-100 w-full ml-auto md:hidden">
    <div>
        <button
            aria-label="close modal"
            id="close-nav-menu-btn"
            class="text-5xl ml-auto font-normal size-8 grid place-content-center">
            &times;
        </button>
    </div>

    <?= component('layout/nav-menu', [
        'class' => ''
    ]) ?>
</dialog>
