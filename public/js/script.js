document.addEventListener('DOMContentLoaded', function() {
  fetch('menu.html')
    .then(response => response.text())
    .then(data => {
      const menuPlaceholder = document.getElementById('menu-placeholder');
      if (menuPlaceholder) {
        menuPlaceholder.innerHTML = data;
      }
    })
    .catch(error => console.error('Błąd ładowania menu:', error));
  
});
