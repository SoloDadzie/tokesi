import './bootstrap';
import '@fontsource/dm-serif-display';
import '@fontsource/manrope/200.css'; // Light
import '@fontsource/manrope/400.css'; // Regular
import '@fontsource/manrope/500.css'; // Medium
import '@fontsource/manrope/600.css'; // Semi-bold
import '@fontsource/manrope/700.css'; // Bold
import '@fontsource/manrope/800.css'; // Extra-bold

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import cart functionality
import './cart.js';