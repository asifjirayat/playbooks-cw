<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#2563eb',
                            accent: '#f97316',
                            yellow: '#FFBC00',
                        },
                        ui: {
                            bg: '#ffffff',
                            bgYellow: '#fff8e5',
                            bgYellowIcon: '#ffbc00',

                            surface: '#f8fafc',
                            surfaceHover: '#f1f5f9',

                            border: '#e2e8f0',
                            borderStrong: '#cbd5e1',

                            text: '#0f172a',
                            textSoft: '#334155',
                            subtext: '#64748b',
                            muted: '#94a3b8',
                            textTertiary: '#956d00',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-ui-bg font-sans antialiased'); ?>>

    <!-- AUDIOBOOK HEADER -->
    <header class="w-full bg-ui-bg/90 backdrop-blur-md sticky top-0 z-50 border-b border-ui-border">

        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

            <!-- Back to Library -->
            <a href="<?php echo esc_url(home_url('/library')); ?>"
                class="flex items-center gap-2 text-ui-subtext hover:text-ui-text transition text-sm font-semibold">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Library
            </a>

            <!-- Right utilities -->
            <div class="flex items-center gap-4 text-ui-text">

                <!-- Bookmark -->
                <button id="cw-header-bookmark"
                    class="text-xl hover:text-brand-primary transition">
                    <i class="fa-regular fa-bookmark"></i>
                </button>

                <!-- Share -->
                <button class="text-xl hover:text-brand-primary transition"
                    onclick="navigator.share && navigator.share({ title: '<?php echo esc_js(get_the_title()); ?>', url: window.location.href });">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>

            </div>
        </div>

    </header>