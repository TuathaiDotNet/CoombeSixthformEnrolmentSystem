<?php
header('Content-type: text/javascript');

require_once('../config.inc.php');
require_once('../select_widget.php');

$table_id = '#grades';
$AJAXSource = '/api/get_gcse_grades.php';
$AJAXUpdate = '/api/ajax_update_gcse_grades.php';
// Query -- id, Grade, Points, QualificationID
$edit_column = 4;
$del_column  = 5;
?>

$(document).ready(function()
{
	var nEditing = null;
	var Table    = $('<?php echo $table_id; ?>').dataTable( {
		'bProcessing': true,
		'sAjaxSource': '<?php echo $AJAXSource; ?>',
		'bPaginate'  : false,
		'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
			$('td:eq(<?php echo $edit_column; ?>)', nRow).html( '<button class=\"edit\">Edit</button>' );
			$('td:eq(<?php echo $del_column; ?>)', nRow).html( '<button class=\"delete\">Delete</button>' );
		}
	} );

<?php	echo(create_select_builder('build_GCSE_Type_select',     "SELECT * from GCSE_Qualification ORDER BY id",      'gcse_type',    'id', 'Type', 'Length')); ?>
	
//		makes buttons into jquery buttons
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
		oTable.fnUpdate( '<button class=\"edit\">Edit</button>', nRow, <?php echo $edit_column; ?>, false );
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
		jqTds[3].innerHTML = build_GCSE_Type_select    (aData[3]);

		if (add == true) {
			jqTds[<?php echo $edit_column; ?>].innerHTML = '<button class=\"edit\">Add</button>';
		}
		else
		{
			jqTds[<?php echo $edit_column; ?>].innerHTML = '<button class=\"edit\">Save</button>';
		}
	}

/** Post the data from this row to a php script via AJAX.
  *
  * Return false if there is a problem with the data.
  */
	function saveRow ( oTable, nRow, action )
	{
		var jqInputs  = $('input',  nRow);
		var jqSelects = $('select', nRow);
		
		//test if Points value is numeric.
		if (! $.isNumeric(jqInputs[2].value) ) 
		{
			alert("Points must be a number. (Recieved value: "+jqInputs[2].value+" )");
			return false;
		}
		
		var act       = "new";

		if (action == 'Save') {
			act = 'update';
		}

		var request = $.ajax({
			url: '<?php echo $AJAXUpdate; ?>',
			type: 'POST',
			data: { 
				action             : act,
				id                 : jqInputs[0].value,
				Grade              : jqInputs[1].value,
				Points             : jqInputs[2].value,
				QualificationID    : $('select.gcse_type > option')[jqSelects[0].selectedIndex].id
			},
			dataType: 'html'
		} );

		request.done(function( msg ) {
			$('#debug').html( msg );
		} );

		request.fail(function(jqXHR, textStatus) {
			alert( 'Request failed: ' + textStatus );
			return false;
		} )

		oTable.fnUpdate( jqInputs[0].value, nRow, 0, false );
		oTable.fnUpdate( jqInputs[1].value, nRow, 1, false );
		oTable.fnUpdate( jqInputs[2].value, nRow, 2, false );
		oTable.fnUpdate( jqSelects[0].options[jqSelects[0].selectedIndex].text,            nRow, 3, false );
		
		oTable.fnUpdate( '<button class=\"edit\">Edit</button>', nRow, <?php echo $edit_column; ?>, false );
		oTable.fnDraw();
		return true;
	}

/** Add a click handler to the rows. This adds a highlight to the currently selected row. 
  */
	$('<?php echo $table_id; ?> tbody').click( function( event ) {
		$(Table.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	} ) ;
	
	var nEditing = null;

	$('<?php echo $table_id; ?> .edit_results').live('click', function (event) {
		event.preventDefault();
	} );
	
/** Delete Click handler. Calls '<?php echo $AJAXUpdate; ?>'
  * via AJAX, then deletes the row in the datatable.
  */
	$('.delete').live('click', function (e)
	{
		e.preventDefault();

		var nRow     = $(this).parents('tr')[0];
		var jqTds    = $('>td', nRow);

		var request = $.ajax({
			url: '<?php echo $AJAXUpdate; ?>',
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
		
		var aiNew = Table.fnAddData( [ '', '', '', '',
			'<button class=\"edit\">Add</button>', '<button class=\"delete\">Delete</button>' ] );
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
			if (saveRow( Table, nEditing, 'Save' )) {
				nEditing = null;
			}
		}
		else if ( nEditing == nRow && this.innerHTML == 'Add') {
			/* Editing this row and want to save it */
			if (saveRow( Table, nEditing, 'Add' )) {
				nEditing = null;
			}
		}
		else {
			/* No edit in progress - lets start one */
			editRow( Table, nRow );
			nEditing = nRow;
		}
	} );
} );
