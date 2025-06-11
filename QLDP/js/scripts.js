document.addEventListener('DOMContentLoaded', () => {
    // Xử lý tìm kiếm theo thời gian thực
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const value = e.target.value.toLowerCase();
            const table = e.target.closest('.table-container').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    });
});