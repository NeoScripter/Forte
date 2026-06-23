<?php

$slot  = $slot  ?? '';
$class = $class ?? '';

$uid = uniqid('modal_');
$panel_class = trim('bg-user-background m-auto w-full rounded-sm max-w-9/10 px-7 py-10 sm:max-w-100 lg:max-w-160 ' . $class);
?>
<div
    id="<?= $uid ?>"
    class="size-screen pointer-events-none fixed inset-0 z-20 flex flex-wrap overflow-y-auto bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-in-out"
    onclick="if (event.target.id === '<?= $uid ?>') window.closeModal('<?= $uid ?>')"
>
    <div class="<?= $panel_class ?>">
        <?= $slot ?>
    </div>
</div>

<script>
window.openModal = window.openModal || function(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('pointer-events-none', 'opacity-0');
    modal.classList.add('opacity-100');
    document.documentElement.style.overflowY = 'clip';
};

window.closeModal = window.closeModal || function(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('pointer-events-none', 'opacity-0');
    modal.classList.remove('opacity-100');
    document.documentElement.style.overflowY = 'auto';
};

(function() {
    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[id^="modal_"]').forEach(function(modal) {
            if (modal.classList.contains('opacity-100')) {
                window.closeModal(modal.id);
            }
        });
    });
})();
</script>
