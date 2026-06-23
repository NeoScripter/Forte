<?php foreach (\Flash::instance()->getMessages() as $message) : ?>
    <div data-toast class="fixed z-100 flex justify-between items-start gap-2 bg-background animate-slide-down border border-border text-popover-foreground shadow-sm py-2 px-4 rounded-sm top-8 inset-x-0 mx-auto max-w-100 w-9/10 transition-[opacity,translate] duration-500 ease-in">
        <p>
            <?= $message['text'] ?>
        </p>
        <button class="bg-red-500 shrink-0 translate-x-2 transition-[bg-color,scale] hover:scale-105 hover:bg-red-400 text-white flex items-center justify-center rounded-sm size-6 p-px relative">
            <span class="absolute m-auto inset-0 size-9"></span>
            <?= svg('x') ?>
        </button>
    </div>
<?php endforeach; ?>
