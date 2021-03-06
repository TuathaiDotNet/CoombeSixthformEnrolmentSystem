<?php
header('Content-type: text/javascript');

require_once('../config.inc.php');
require_once('../select_widget.php');

$table_id = '#qualifications';
$AJAXSource = '/api/get_gcse_qualifications.php';
$AJAXUpdate = '/api/ajax_update_gcse_grades.php';
// Query -- id, Type, Length, EquivalentGCSE
$edit_column = 5;
$del_column  = 6;
?>

$(document).ready(function()
{
	var nEditing = null;
	
	var Table = $('<?php echo $table_id; ?>').dataTable( {
		'bProcessing': true,
		'sAjaxSource': '<?php echo $AJAXSource; ?>',
		'bPaginate'  : false,
		'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
			$('td:eq(<?php echo $edit_column; ?>)', nRow).html( '<button class=\"edit\">Edit</button>' );
			$('td:eq(<?php echo $del_column; ?>)', nRow).html( '<button class=\"delete\">Delete</button>' );
		}
	} );

// Makes buttons into jquery buttons
	$(".edit").button();
	$(".delete").button();

/** This runs when the user clicks the save button in a row. */
	function restoreRow ( oTable, nRow )
	{
		var aData = oTable.fnGetData(nRow);
		var jqTds = $('>td', nRow);
		for ( var i=0, iLen=jqTds.length ; i<iLen ; i++ ) {
			oTable.fnUpdate( aData[i], nRow, i, false );
		}
		oTable.fnUpdate( '<button class="edit">Edit</button>', nRow, 5, false );
		oTable.fnDraw();
	}

/** This is run when a user clicks Edit or Save. 
  *
  * We take the value from some cells and create some select boxes with
  * those entries pre-selected.
  *
  * We trigger the above function to update the grade select box depending 
  * on the pre-selected value from GCSE_Type.
  *
  * Finally, we change the Edit button to a Save button. When clicked, the 
  * callback functions action depends on the value of this.
  */
	function editRow ( oTable, nRow, add )
	{
		var aData = oTable.fnGetData(nRow);
		var jqTds = $('>td', nRow);
		jqTds[0].innerHTML = '<input type="text" value="'+aData[0]+'" disabled="disabled">';
		jqTds[1].innerHTML = '<input type="text" value="'+aData[1]+'">';
		jqTds[2].innerHTML = '<input type="text" value="'+aData[2]+'">';
		jqTds[3].innerHTML = '<input type="text" value="'+aData[3]+'">';
		jqTds[4].innerHTML = '<input type="text" value="'+aData[4]+'">';

		if (add == true) {
			jqTds[5].innerHTML = '<button class=\"edit\">Add</button>';
		}
		else
		{
			jqTds[5].innerHTML = '<button class=\"edit\">Save</button>';
		}
	}
		
/** Post the data from this row via AJAX.
  */
	function saveRow ( oTable, nRow, action )
	{
		var jqInputs  = $('input',  nRow);
		var jqSelects = $('select', nRow);
		var act       = "new";

		if (action == 'Save') {
			act = 'update';
		}

		var request = $.ajax({
			url: '/api/ajax_update_gcse_qualifications.php',
			type: 'POST',
			data: { 
				action             : act,
				id                 : jqInputs[0].value,
				Type               : jqInputs[1].value,
				Length             : jqInputs[2].value,
				EquivalentGCSE     : jqInputs[3].value,
				Notes              : jqInputs[4].value
			},
			dataType: 'html'
		} );

		request.done(function( msg ) {
			$('#debug').html( msg );
		} );

		request.fail(function(jqXHR, textStatus) {
			alert( 'Request failed: ' + textStatus );
		} )

		oTable.fnUpdate( jqInputs[0].value, nRow, 0, false );
		oTable.fnUpdate( jqInputs[1].value, nRow, 1, false );
		oTable.fnUpdate( jqInputs[2].value, nRow, 2, false );
		oTable.fnUpdate( jqInputs[3].value, nRow, 3, false );
		oTable.fnUpdate( jqInputs[4].value, nRow, 4, false );
		
		oTable.fnUpdate( '<button class="edit">Edit</button>', nRow, 5, false );
		oTable.fnDraw();
	}

/** Add a click handler to the rows. This adds a highlight to the currently selected row. 
  */
	$('#qualifications tbody').click( function( event ) {
		$(Table.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	} ) ;
	
	var nEditing = null;

	$('#qualifications .edit_results').live('click', function (event) {
		event.preventDefault();
	} );
	
/** Delete Click handler. Calls 'ajax_update_qualifications.php 
  * via AJAX, then deletes the row in the datatable.
  */
	$('.delete').live('click', function (e)
	{
		e.preventDefault();

		var nRow     = $(this).parents('tr')[0];
		var jqTds    = $('>td', nRow);

		var request = $.ajax({
			url: '/api/ajax_update_gcse_qualifications.php',
			type: 'POST',
			data: { 
				action             : 'delete',
				id                 : jqTds[0].innerHTML
			},
			dataType: 'html'
		} );

		request.done(function( msg ) {
			$('#debug').html( msg );
		} );

		request.fail(function(jqXHR, textStatus) {
			alert( 'Request failed: ' + textStatus );
		} )

	Table.fnDeleteRow( nRow );
	} );
	
	$('#new').click( function (e) {
		e.preventDefault();
		
		var aiNew = Table.fnAddData( [ '', '', '', '', '',
			'<button class="edit">Add</button>', '<button class="delete">Delete</button>' ] );
		var nRow = Table.fnGetNodes( aiNew[0] );
		editRow( Table, nRow, true );
		nEditing = nRow;
	} );
		
	$('.edit').live('click', function (e)
	{
		/* Get the row as a parent of the link that was clicked on */
		var nRow = $(this).parents('tr')[0];
		
		if ( nEditing !== null && nEditing != nRow ) {
			/* Currently editing - but not this row - restore 
			 * the old before continuing to edit mode          */
			restoreRow( Table, nEditing );
			editRow( Table, nRow );
			nEditing = nRow;
		}
		else if ( nEditing == nRow && this.innerHTML == 'Save') {
			/* Editing this row and want to save it */
			saveRow( Table, nEditing, 'Save' );
			nEditing = null;
		}
		else if ( nEditing == nRow && this.innerHTML == 'Add') {
			/* Editing this row and want to save it */
			saveRow( Table, nEditing, 'Add' );
			nEditing = null;
		}
		else {
			/* No edit in progress - lets start one */
			editRow( Table, nRow );
			nEditing = nRow;
		}
	} );
} );
