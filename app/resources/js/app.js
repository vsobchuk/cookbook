require('./bootstrap');

import Alpine from 'alpinejs';

import {
    toggleFavorite,
} from './alpine/components'

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start()
})

document.addEventListener('alpine:initializing', () => {
    Alpine.data('toggleFavorite', toggleFavorite)
})
