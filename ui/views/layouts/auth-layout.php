<?php $slot = $slot ?? ''; ?>

<main class='bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10'>
    <div class="w-full max-w-sm">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col items-center gap-4">
                <a
                    href="/"
                    class="flex flex-col items-center gap-2 font-medium">
                    <div class="flex w-15 items-center justify-center rounded-md">
                        <?= view(
                            'components/ui/auth-logo',
                            ['class' => "w-15 fill-current text-(--foreground) dark:text-white"]
                        ) ?>
                    </div>
                    <span class="sr-only"><?= $page_title ?? '' ?></span>
                </a>

                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-medium"><?= $page_title ?? '' ?></h1>
                    <p class="text-muted-foreground text-center text-sm text-balance">
                        <?= $description ?? '' ?>
                    </p>
                </div>
            </div>

            <?= $slot ?>
        </div>
    </div>
</main>
