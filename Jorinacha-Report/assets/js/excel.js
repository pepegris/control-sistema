
function exportReportToExcel() {
    let table = document.getElementsByID("tblData");
    TableToExcel.convert(table[0], { 
      name: `file.xlsx`,
      sheet: {
        name: 'Sheet 1'
      }
    });
  }