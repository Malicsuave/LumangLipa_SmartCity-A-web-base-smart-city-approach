export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {
            // Add additional options for better handling of vendor prefixes
            remove: false, // Don't remove existing prefixes
            flexbox: 'no-2009', // Use modern flexbox where possible
            grid: 'autoplace', // Enable grid prefixes where needed
        },
    },
};
