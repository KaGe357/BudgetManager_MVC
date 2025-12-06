const getLimitForCategory = async (category) => {
    try {
        const encodedCategory = encodeURIComponent(category);
        const res = await fetch(`/api/limit?category=${encodedCategory}`);
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const data = await res.json();
        return data;
    } catch (e) {
        console.error('ERROR fetching limit:', e);
        return { limit: null };
    }
};

const handleLimitButtonClick = async (button) => {
    const categoryId = button.getAttribute('data-category-id');
    const categoryName = button.getAttribute('data-category-name');
    
    // Wypełnij podstawowe dane
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
