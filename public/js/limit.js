const getLimitForCategory = async (category) => {
    try {
        // Enkoduj nazwę kategorii dla bezpieczeństwa URL
        const encodedCategory = encodeURIComponent(category);
        const res = await fetch(`/api/expense/limit/${encodedCategory}`);
        
        // Sprawdź czy odpowiedź jest OK
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const data = await res.json();
        
        // Sprawdź czy odpowiedź zawiera dane
        if (!data) {
            console.warn('Brak danych dla kategorii:', category);
            return { limit: null, spent: 0 };
        }
        
        return data;
        
    } catch (e) {
        console.error('Błąd podczas pobierania limitu dla kategorii:', category, e);
        
        // Pokaż komunikat użytkownikowi
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show';
        alertDiv.innerHTML = `
            <strong>Uwaga!</strong> Nie udało się pobrać informacji o limicie.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container').prepend(alertDiv);
        
        // Zwróć bezpieczne wartości domyślne
        return { limit: null, spent: 0 };
    }
};

const handleLimitButtonClick = async (button) => {
    const categoryId = button.getAttribute('data-category-id');
    const categoryName = button.getAttribute('data-category-name');
    
    document.getElementById('categoryId').value = categoryId;
    document.getElementById('categoryName').textContent = categoryName;
    
    const limitInput = document.getElementById('limitInput');
    limitInput.value = '';
    limitInput.disabled = true;
    
    const data = await getLimitForCategory(categoryName);
    
    limitInput.value = data.limit || '';
    limitInput.disabled = false;
    limitInput.focus();
};
