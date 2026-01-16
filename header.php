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
                            dark: '#0f172a',
                            darker: '#020617',
                            text: '#f8fafc',
                            yellow: '#FFBC00',
                            primary: '#2563eb',
                            accent: '#f97316',
                        },
                        ui: {
                            bg: '#020617',
                            surface: '#0f172a',
                            border: '#1e293b',
                            text: '#e2e8f0',
                            subtext: '#94a3b8',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-ui-bg font-sans antialiased'); ?>>

    <!-- Global Header -->
    <header class="bg-ui-bg/90 backdrop-blur shadow-lg shadow-black/35 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block">

                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        $logo_id = get_theme_mod('custom_logo');
                        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                        echo '<img src="' . esc_url($logo_url) . '" class="h-10 md:h-12 w-auto" alt="Concentrated Wisdom Logo">';
                    } else {
                        echo '<span class="text-xl md:text-2xl font-bold text-brand-yellow leading-none">Concentrated Wisdom</span>';
                    }
                    ?>

                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:block cw-desktop-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary_menu',
                    'container'      => false,
                    'menu_class'     => 'flex gap-6 lg:gap-8 text-ui-text font-medium text-sm no-underline hover:no-underline',
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
                ?>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-ui-text text-3xl">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="hidden md:hidden bg-ui-surface border-t border-ui-border">
            <nav class="px-4 py-4">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary_menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-col gap-4 text-ui-text font-medium text-base',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>
        </div>

    </header>

    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');

        if (btn) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>