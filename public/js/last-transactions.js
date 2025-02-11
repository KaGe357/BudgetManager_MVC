document.addEventListener("DOMContentLoaded", () => {
    function fetchTransactions() {
        fetch("fetch_transactions.php")
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    updateTable(document.getElementById("incomeTransactionsList"), data.incomes);
                    updateTable(document.getElementById("expenseTransactionsList"), data.expenses);
                } else {
                    alert("Błąd: " + data.error);
                }
            })
            .catch((error) => {
                alert("Wystąpił błąd sieci: " + error.message);
                console.error("Fetch error:", error);
            });
    }

    function updateTable(tableElement, transactions) {
        if (!tableElement) {
            console.error("Nie znaleziono tabeli.");
            return;
        }

        tableElement.innerHTML = transactions
            .map(
                (transaction) => `
                <tr>
                    <td>${transaction.date || "Brak danych"}</td>
                    <td>${transaction.category || "Brak kategorii"}</td>
                    <td>${parseFloat(transaction.amount).toFixed(2)} PLN</td>
                    <td>${transaction.comment || "Brak komentarza"}</td>
                </tr>`
            )
            .join("");
    }

    fetchTransactions();
});
