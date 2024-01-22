
  /**
   * Initiate Datatables
   * https://github.com/fiduswriter/Simple-DataTables
   * https://fiduswriter.github.io/simple-datatables/demos/
   */

  import {
      DataTable
      //, makeEditable
      } from "/assets/js/dataTableModule.js"
  const datatables = document.querySelectorAll('.datatable');
  window.dt=[];
    datatables.forEach(datatable => {
        
        var i = new DataTable(datatable);
        i.dom.addEventListener("click", e => {console.log(e); })
        window.dt.push({
          "name": datatable.id,
          "data": i
        });
        //a.on('datatable.update', (i,e) => {
        //datatable.id
        //a.data.data[i].cells[...].text
        //a.data.headings[...].data
        //console.log(i,e,a.data);});
        /*

        */
        /* editor
        let inline = false;
        var editor = new makeEditable(a,{
            contextMenu: true,
            hiddenColumns: true,
            excludeColumns: [0], // make the "Ext." column non-editable
            inline,
          menuItems: [{
              text: "<span class='mdi mdi-lead-pencil'></span> Edit Cell",
              action: (editor, _event) => {
                  const td = editor.event.target.closest("td")
                  return editor.editCell(td)} 
          },{
              text: "<span class='mdi mdi-lead-pencil'></span> Edit Row",
              action: (editor, _event) => {
                  const tr = editor.event.target.closest("tr")
                  return editor.editRow(tr)}
          },{
              separator: true
          },{
              text: "<span class='mdi mdi-delete'></span> Remove",
              action: (editor, _event) => {
                  if (confirm("Are you sure?")) {
                    const tr = editor.event.target.closest("tr")
                    editor.removeRow(tr)}}
          }]
        });  editor ende*/
        /**
         * hier ist wichtig ! Events abfangen vom Editor:
         * editor.dt.on("editable.save.cell", (value, oldData, rowIndex, columnIndex) => {console.log(value, oldData, rowIndex, columnIndex);});
        */
    });