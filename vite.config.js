import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/login.css',
                'resources/js/login.js',
                'resources/css/register.css',
                'resources/js/register.js',
                'resources/css/sidebarMahasiswa.css',
                'resources/js/sidebarMahasiswa.js',
                'resources/css/find-opportunity.css',
                'resources/js/find-opportunity.js',
                'resources/css/explore-program.css',
                'resources/js/explore-program.js',
                'resources/css/programManagement.css',
                'resources/js/programManagement.js',
                'resources/css/sidebarAdmin.css',
                'resources/js/sidebarAdmin.js',
                'resources/css/adminProfile.css',
                'resources/js/adminProfile.js',
                'resources/css/schedule-management.css',
                'resources/js/schedule-management.js',
                'resources/css/dashboardUser.css',
                'resources/css/admin-dashboard.css',
                'resources/js/admin-dashboard.js',
                'resources/css/announcements.css',
                'resources/js/announcements.js',
                'resources/css/DetailProgram.css',
                'resources/css/ProfileUser.css',
                'resources/js/ProfileUser.js',
                'resources/css/RegistrationForm.css',
                'resources/js/RegistrationForm.js'
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
