// resources/js/app.js
import Swal from 'sweetalert2';
window.Swal = Swal; // Torna o Swal globalmente acessível se precisar

import Alpine from 'alpinejs'
import mask from '@alpinejs/mask'


Alpine.plugin(mask)

window.Alpine = Alpine

Alpine.start()
