// resources/js/app.js
import Swal from 'sweetalert2';
window.Swal = Swal; // Torna o Swal globalmente acessível se precisar

import mask from '@alpinejs/mask'

// O Livewire (via @livewireScripts) inicializa e expõe sua própria instância
// do Alpine. Chamar Alpine.start() aqui criaria uma segunda instância e
// quebraria os diretivos wire:* (ex: wire:click passa a não fazer nada).
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(mask)
})
