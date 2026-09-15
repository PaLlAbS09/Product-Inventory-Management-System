
document.addEventListener('DOMContentLoaded', () => {
    // Global Event Listener to close modals when pressing the "Escape" key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('[id$="Modal"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    const forms = modal.querySelectorAll('form');
                    forms.forEach(form => form.reset());
                }
            });
        }
    });

   
    window.apiFetch = async function(url, options = {}) {
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Fetch Error:', error);
            alert('A system error occurred while processing your request. Please check your connection or try again later.');
            return null;
        }
    }
});