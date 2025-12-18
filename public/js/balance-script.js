document.addEventListener("DOMContentLoaded", () => {
    const applyDateRange = document.getElementById("applyDateRange");
    const dateRangeSelect = document.getElementById("dateRangeSelect");
    const startDateInput = document.getElementById("startDateInput");
    const endDateInput = document.getElementById("endDateInput");

    dateRangeSelect.addEventListener("change", () => {
        const customStartDateRange = document.getElementById("customStartDateRange");
        const customEndDateRange = document.getElementById("customEndDateRange");
        if (dateRangeSelect.value === "custom") {
            customStartDateRange.style.display = "block";
            customEndDateRange.style.display = "block";
        } else {
            customStartDateRange.style.display = "none";
            customEndDateRange.style.display = "none";
        }
    });

    applyDateRange.addEventListener("click", () => {
        let startDate = startDateInput.value;
        let endDate = endDateInput.value;

        // Obsługa zakresów dat
        if (dateRangeSelect.value === "thisMonth") {
            const today = new Date();
            startDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-01`;
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split("T")[0];
        } else if (dateRangeSelect.value === "lastMonth") {
            const today = new Date();
            const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            startDate = firstDayLastMonth.toISOString().split("T")[0];
            endDate = lastDayLastMonth.toISOString().split("T")[0];
        } else if (dateRangeSelect.value === "allTime") {
            startDate = "2000-01-01";
            endDate = new Date().toISOString().split("T")[0];
        }

        
        applyDateRange.disabled = true;
        applyDateRange.textContent = "Ładowanie...";

        fetch("/api/balance", {  
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ start_date: startDate, end_date: endDate }),
        })
        .then(res => {
            // Sprawdzenie statusu HTTP przed parsowaniem JSON
            if (!res.ok) {
                throw new Error(`Błąd HTTP: ${res.status} ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
           // console.log("Debug: Odpowiedź serwera:", data); // 🔍 Debugowanie

            if (data.success) {
                document.getElementById("balance").textContent = `${data.totalBalance.toFixed(2)} PLN`;

                updateTable(".table-incomes tbody", data.incomes, "income_category_name", "total_incomes");
                updateTable(".table-expenses tbody", data.expenses, "expense_category_name", "total_expenses");

                
                const modal = bootstrap.Modal.getInstance(document.getElementById("balanceModal"));
                if (modal) modal.hide();
            } else {
                showError(data.error || "Nieznany błąd serwera");
            }
        })
        .catch(err => {
            console.error("Debug: Błąd fetch:", err);
            showError("Wystąpił błąd: " + err.message);
        })
        .finally(() => {
            applyDateRange.disabled = false;
            applyDateRange.textContent = "Zastosuj";
        });
    });

    /**
     * Wyświetla komunikat błędu użytkownikowi
     */
    function showError(message) {
        // Użyj Bootstrap Toast jeśli dostępny, w przeciwnym razie alert
        const toastContainer = document.getElementById('toastContainer');
        if (toastContainer) {
            const toastHtml = `
                <div class="toast align-items-center text-bg-danger border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toast = new bootstrap.Toast(toastContainer.lastElementChild);
            toast.show();
        } else {
            alert(message);
        }
    }

    function updateTable(selector, items, nameKey, valueKey) {
        const tbody = document.querySelector(selector);
        if (!tbody) return;

        tbody.innerHTML = items
            .map(item => `
                <tr>
                    <td>${item[nameKey]}</td>
                    <td>${parseFloat(item[valueKey]).toFixed(2)} PLN</td>
                </tr>
            `)
            .join("");
    }
});
