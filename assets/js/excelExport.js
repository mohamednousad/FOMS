document.addEventListener("DOMContentLoaded", function () {
  const selectAllCheckbox = document.getElementById("selectAll");
  const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
  const printBtn = document.getElementById("printBtn");

  // Select All or Deselect All Rows
  selectAllCheckbox.addEventListener("change", function () {
    rowCheckboxes.forEach((checkbox) => {
      checkbox.checked = selectAllCheckbox.checked;
    });
  });

  // Ensure Select All syncs with individual row selection
  rowCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      if (!this.checked) {
        selectAllCheckbox.checked = false;
      } else if ([...rowCheckboxes].every((cb) => cb.checked)) {
        selectAllCheckbox.checked = true;
      }
    });
  });

  // Export to Excel on Button Click
  printBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const selectedRows = [];

    rowCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        const row = checkbox.closest("tr");
        const rowData = Array.from(row.cells)
          .slice(1, -1)
          .map((cell) => cell.innerText);
        selectedRows.push(rowData);
      }
    });

    if (selectedRows.length === 0) {
      alert("Please select at least one row to export.");
      return;
    }

    const worksheet = XLSX.utils.aoa_to_sheet([
      ["NIC Number", "Name", "Branch", "Phone", "Address", "Purpose", "Date"],
      ...selectedRows,
    ]);

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Selected Data");
    XLSX.writeFile(workbook, "visitorData.xlsx");
  });
});
