import "./libs/trix";
import './bootstrap';
import moment from 'moment';
import Pikaday from 'pikaday';

window.Pikaday = Pikaday;
window.moment = moment;
// import Alpine from 'alpinejs';
// import focus from '@alpinejs/focus'

// window.Alpine = Alpine;

// Alpine.start();
// Alpine.plugin(focus)


window.addEventListener('alert', (event) => {
    let data = event.detail;

    Swal.fire({
        position: 'top-end',
        toast: true,
        icon: data.type,
        text: data.text,
        timerProgressBar: true,
        timer: 3000,
        showConfirmButton: false,
        showCloseButton: true,
    })
});

// If addEventListener doesn't work, try
window.addEventListener('confirm', (event) => {
    let data = event.detail;

    Swal.fire({
        title: data.title || 'Are you sure?',
        text: data.text || 'You won\'t be able to revert this!',
        icon: data.icon || 'warning',
        showCancelButton: true,
        confirmButtonText: data.confirmButtonText || 'Yes, proceed!',
        cancelButtonText: data.cancelButtonText || 'Cancel',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch(data.onConfirmed); // Dispatches to Livewire's backend
        }
    });
});

