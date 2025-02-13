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
        .then(res => res.json())
        .then(data => {
            console.log("Debug: Odpowiedź serwera:", data); // 🔍 Debugowanie

            if (data.success) {
                document.getElementById("balance").textContent = `${data.totalBalance.toFixed(2)} PLN`;

                updateTable(".table-incomes tbody", data.incomes, "income_category_name", "total_incomes");
                updateTable(".table-expenses tbody", data.expenses, "expense_category_name", "total_expenses");

                
                const modal = bootstrap.Modal.getInstance(document.getElementById("balanceModal"));
                if (modal) modal.hide();
            } else {
                alert("Błąd serwera: " + data.error);
            }
        })
        .catch(err => {
            console.error("Debug: Błąd fetch:", err);
            alert("Wystąpił błąd: " + err.message);
        })
        .finally(() => {
            applyDateRange.disabled = false;
            applyDateRange.textContent = "Zastosuj";
        });
    });

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
