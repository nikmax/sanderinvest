<footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span><?=$company;?></span></strong>. All Rights Reserved
    </div>
    <div class="credits">Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a></div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
	  <i class="bi bi-arrow-up-short"></i></a>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<!--script src="assets/vendor/simple-datatables/simple-datatables.js"></script-->

  <!-- Vendor JS Files -->
  <!--script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script-->
  <!--script src="assets/vendor/php-email-form/validate.js"></script-->

<script src="assets/js/main.js"></script>
<script src="assets/js/activate.js"></script>


  /**
   * Initiate Datatables
   */

<script type="module">
    import {
        DataTable,
        makeEditable
    } from "/assets/js/dataTableModule.js"
    const datatables = document.querySelectorAll('.datatable');
    datatables.forEach(datatable => {
        var a = new DataTable(datatable);
        a.on('datatable.update', (i,e) => {

      //datatable.id
      //a.data.data[i].cells[...].text
      //a.data.headings[...].data
          console.log(i,e,a.data);});
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
        });
        editor.dt.on("editable.save.cell", (value, oldData, rowIndex, columnIndex) => {console.log(value, oldData, rowIndex, columnIndex);});
    });
</script>

</body>
</html>
