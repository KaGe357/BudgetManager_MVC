document.addEventListener("DOMContentLoaded", () => {
    const applyDateRange = document.getElementById("applyDateRange");
    const dateRangeSelect = document.getElementById("dateRangeSelect");
    const startDateInput = document.getElementById("startDateInput");
    const endDateInput = document.getElementById("endDateInput");

    let incomeChart, expenseChart; // Zmienna na wykresy

    function createChart(canvasId, labels, data, title, backgroundColors) {
        const ctx = document.getElementById(canvasId).getContext("2d");
        return new Chart(ctx, {
            type: "pie",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: "rgba(255, 255, 255, 0.5)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                    title: {
                        display: true,
                        text: title,
                    },
                },
            },
        });
    }

    function updateChart(chart, labels, data) {
        chart.data.labels = labels;
        chart.data.datasets[0].data = data;
        chart.update();
    }

    applyDateRange.addEventListener("click", () => {
        let startDate = startDateInput.value;
        let endDate = endDateInput.value;

        if (dateRangeSelect.value === "thisMonth") {
            const today = new Date();
            startDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-01`;
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split("T")[0];
        } else if (dateRangeSelect.value === "lastMonth") {
            const today = new Date();
            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1).toISOString().split("T")[0];
            endDate = new Date(today.getFullYear(), today.getMonth(), 0).toISOString().split("T")[0];
        } else if (dateRangeSelect.value === "allTime") {
            startDate = "2000-01-01";
            endDate = new Date().toISOString().split("T")[0];
        }

        fetch("fetch_balance.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ start_date: startDate, end_date: endDate }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById("balance").textContent = `${data.totalBalance.toFixed(2)} PLN`;

                    const textElement = document.getElementById("text");
                    if (textElement) {
                        textElement.textContent =
                            data.totalBalance > 0
                                ? "Świetnie zarządzasz swoimi finansami!"
                                : data.totalBalance < 0
                                ? `Bilans na minusie: ${Math.abs(data.totalBalance).toFixed(2)} PLN`
                                : "Bilans wynosi zero.";
                    }

                    updateTable(".table-incomes tbody", data.incomes, "income_category_name", "total_incomes");
                    updateTable(".table-expenses tbody", data.expenses, "expense_category_name", "total_expenses");

                    const incomeLabels = data.incomes.map((item) => item.income_category_name);
                    const incomeValues = data.incomes.map((item) => parseFloat(item.total_incomes));

                    const expenseLabels = data.expenses.map((item) => item.expense_category_name);
                    const expenseValues = data.expenses.map((item) => parseFloat(item.total_expenses));

                    if (incomeChart) {
                        updateChart(incomeChart, incomeLabels, incomeValues);
                    } else {
                        incomeChart = createChart("incomeChart", incomeLabels, incomeValues, "Dochody", [
                            "rgba(75, 192, 192, 0.6)",
                            "rgba(255, 99, 132, 0.6)",
                            "rgba(255, 206, 86, 0.6)",
                            "rgba(54, 162, 235, 0.6)",
                            "rgba(153, 102, 255, 0.6)",
                        ]);
                    }

                    if (expenseChart) {
                        updateChart(expenseChart, expenseLabels, expenseValues);
                    } else {
                        expenseChart = createChart("expenseChart", expenseLabels, expenseValues, "Wydatki", [
                            "rgba(255, 99, 132, 0.6)",
                            "rgba(54, 162, 235, 0.6)",
                            "rgba(75, 192, 192, 0.6)",
                            "rgba(153, 102, 255, 0.6)",
                            "rgba(255, 159, 64, 0.6)",
                        ]);
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById("balanceModal"));
                    if (modal) modal.hide();
                } else {
                    alert("Błąd serwera: " + data.error);
                }
            })
            .catch((err) => {
                alert("Wystąpił błąd: " + err.message);
            });
    });

    function updateTable(selector, items, nameKey, valueKey) {
        const tbody = document.querySelector(selector);
        if (!tbody) {
            console.error(`Tabela z selektorem ${selector} nie została znaleziona.`);
            return;
        }

        tbody.innerHTML = items
            .map((item) => {
                const name = item[nameKey] || "Nieznana kategoria";
                const value = parseFloat(item[valueKey]) || 0;
                return `
                    <tr>
                        <td>${name}</td>
                        <td>${value.toFixed(2)} PLN</td>
                    </tr>
                `;
            })
            .join("");
    }
});
