$(document).ready(function() {
    /*
    *@data table in history log
    */

    var oTable = $('.dataTable_his').dataTable({
	 'destroy': true,
  	'paging': true,
  	'lengthChange': true,
  	'searching': true,
  	'ordering': true,
  	'info': true,
  	'autoWidth': true
    });

    var allPages = oTable.fnGetNodes();

    $('body').on('click', '#checkall', function () {
        if ($(this).hasClass('allChecked')) {
            $('input[type="checkbox"]', allPages).prop('checked', false);
        } else {
            $('input[type="checkbox"]', allPages).prop('checked', true);
        }
        $(this).toggleClass('allChecked');
    })

    // $("#checkall").click(function(){
    //     $('input:checkbox').not(this).prop('checked', this.checked);
    // });
    /*
    *@END data table in history log
    */
});