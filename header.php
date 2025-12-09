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
                    fontFamily: {
                        sans: ['Lato', 'sans-serif'],
                        heading: ['Lato', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
                            primary: '#3b82f6',
                            gold: '#F1C40F',
                        },
                        ui: {
                            bg: '#f8fafc',
                            surface: '#ffffff',
                            text: '#334155',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-ui-bg font-sans antialiased'); ?>>

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block">
                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        $logo_id = get_theme_mod('custom_logo');
                        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                        echo '<img src="' . esc_url($logo_url) . '" class="h-12 w-auto" alt="Playbooks by Concentrated Wisdom Logo">';
                    } else {
                        // Fallback if no logo is uploaded
                        echo '<span class="text-xl font-bold text-brand-dark">Concentrated Wisdom</span>';
                    }
                    ?>
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:block">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary_menu',
                    'container'      => false,
                    'menu_class'     => 'flex gap-8 text-ui-text font-medium',
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
                ?>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-2xl text-brand-dark">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-slate-200">
            <nav class="px-4 py-4">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary_menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-col gap-4 text-ui-text font-medium',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>
        </div>
    </header>

    <script>
        // Simple mobile toggle
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');

        if (btn) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>