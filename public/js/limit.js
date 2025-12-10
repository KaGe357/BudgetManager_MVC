const getLimitForCategory = async (category) => {
    try {
        const res = await fetch(`/api/expense/limit/${category}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('ERROR', e);
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
