// Custom Modal System
class Modal {
    constructor() {
        this.createModalContainer();
    }

    createModalContainer() {
        if (document.getElementById('custom-modal-container')) return;
        
        const container = document.createElement('div');
        container.id = 'custom-modal-container';
        container.className = 'fixed inset-0 z-[9999] hidden items-center justify-center p-4';
        container.innerHTML = `
            <div class="modal-overlay absolute inset-0 bg-black/50" onclick="closeModal()"></div>
            <div class="modal-content relative bg-white rounded max-w-md w-full p-6 shadow-xl transform transition-all">
                <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div id="modal-body"></div>
            </div>
        `;
        document.body.appendChild(container);
    }

    show(title, message, type = 'info', buttons = []) {
        const container = document.getElementById('custom-modal-container');
        const body = document.getElementById('modal-body');
        
        const icons = {
            success: '<svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error: '<svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            warning: '<svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };

        body.innerHTML = `
            <div class="text-center">
                ${icons[type] || icons.info}
                <h3 class="text-xl font-bold text-gray-900 mb-2">${title}</h3>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex gap-3 justify-center">
                    ${buttons.map(btn => `
                        <button onclick="${btn.action}" class="px-6 py-2 rounded font-semibold transition-colors ${btn.class || 'bg-gray-200 text-gray-800 hover:bg-gray-300'}">
                            ${btn.text}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
        
        container.classList.remove('hidden');
        container.classList.add('flex');
    }

    hide() {
        const container = document.getElementById('custom-modal-container');
        container.classList.add('hidden');
        container.classList.remove('flex');
    }
}

// Global instance
const modal = new Modal();

function showModal(title, message, type = 'info', buttons = []) {
    modal.show(title, message, type, buttons);
}

function closeModal() {
    modal.hide();
}

// Helper functions for common modals
function confirmModal(message, onConfirm, onCancel = closeModal) {
    showModal('Confirm Action', message, 'warning', [
        { text: 'Cancel', action: 'closeModal()', class: 'bg-gray-200 text-gray-800 hover:bg-gray-300' },
        { text: 'Confirm', action: `(${onConfirm.toString()})(); closeModal();`, class: 'bg-orange-600 text-white hover:bg-orange-700' }
    ]);
}

function alertModal(message, type = 'info') {
    showModal(type === 'error' ? 'Error' : 'Notice', message, type, [
        { text: 'OK', action: 'closeModal()', class: 'bg-orange-600 text-white hover:bg-orange-700' }
    ]);
}