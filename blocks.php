<?php 
include 'header.inc.php';

print_header($title = 'Coombe Sixth form registration form.', $hide_title_bar = 'false', $script = "
	$(document).ready(function() {
		var studentTable = $('#students').dataTable( {
			'bProcessing': true,
			'sAjaxSource': 'get_students.php',
			'sScrollY'   : '150px',
			'bPaginate'  : false,
			'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
				$('td:eq(6)', nRow).html( '<a class=\"edit\" href=\"\">Edit</a>' );
				$('td:eq(7)', nRow).html( '<a class=\"delete\" href=\"\">Delete</a>' );
			}
			//'aoColumnDefs': [ {
			///	'sClass'  : 'center',
			//	'aTargets': [ -1, -2 ]
			//} ]
		} );
	});
");
?>
   <div class='block' >
    <table class='with-borders-horizontal'>
     <tr >
      <td>
       <p><a id="new_student" href="">Add New Student</a></p>
       <div id="dynamic">
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="students">
         <thead>
          <tr>
			<th width="7%">Student ID</th>
			<th width="20%">First Name</th>
			<th width="20%">Surname</th>
			<th width="20%">Previous Institution</th>
			<th width="7%">Enrolment Year</th>
			<th width="20%">Student Type</th>
			<th>Edit</th>
			<th>Delete</th>
          </tr>
         </thead>
         <tbody>
          <tr>
           <td colspan="9" class="dataTables_empty">Loading data from server</td>
          </tr>
         </tbody>
        </table>
       </td>
      </tr>
     </table>
   </div>
   
  <form>
   <div class='block' >
    <table class='with-borders-horizontal'>
     <tr >
      <td>Mobile Number: <input type='text' /></td>
      <td>Sequence Number: <input type='text' /> </td>
      <td>
	   <label><input type='radio' name='site' value='clarence_A_Lev' />A/AS@Clarence Ave.</label>&nbsp;&nbsp;&nbsp;
	   <label><input type='radio' name='site' value='clarence_IB' />IB@Clarence Ave.</label>&nbsp;&nbsp;&nbsp;
	   <label><input type='radio' name='site' value='college' />College Gdns.</label></td>
     </tr>
     <tr >
      <td></td>
	  <td><input type='submit' text='Search' /><input type='reset' text='clear' /> </td>
      <td></td>
     </tr>
     </table>
   </div>

   

   <div class='block' style=' margin-top: 0px; width: 17%; float: right;'>
   <table class='with-borders-horizontal'>
    <tr>
     <td colspan='4' stlye='text-align: center;'>GCSE GRADES - For Official Use Only</td>
    </tr>
    <tr><td style='width: 30%'>English           </td><td><input type='text' style='width: 100%'/></td>	</tr>
    <tr><td>Maths             </td><td><input type='text'  style='width: 100%'/></td>	</tr>
    <tr><td>Core Science      </td><td><input type='text' style='width: 100%' /> </td>	</tr>
    <tr><td>Aditional Science </td><td><input type='text' style='width: 100%' /></td>	</tr>
    <tr><td>Triple Science    </td><td>B&nbsp;<input type='text' style='width: 80%'  /><br />C&nbsp;<input type='text' style='width: 80%' /><br />P&nbsp;<input type='text' style='width: 80%' /></td></tr>
    <tr><td>Average GCSE Score</td><td><input type='text' style='width: 100%' /></td>	</tr>
   </table>
  </div>
  
<?php

function print_block_element($block, $id, $Name)
{
	echo 1;
}
?>
  
  <div class='block' style='width: 78%;'>
   <table class='with-borders-horizontal'>
    <tr>
     <td>A</td>
     <td>B </td>
     <td>C</td>
	 <td>D</td>
	 <td>E</td>
	 <td>Twilight</td>
	</tr>
    <tr>
     <td>
<?php
		$format = "        <label><input type='radio' name='block[%1s][%2s]' id='block' />%3s<span style='text-align: right;'>[25/30]</span></label><br />\n";

   printf($format, 'a', 0, 'Chemistry');
   printf($format, 'a', 0,  "English Language and Literature");
		?>
        <label><input type='radio' name='block[a][0]' id='block[a][0]' />Chemistry <span style='text-align: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][1]' id='block[a][1]' />Eng Lang and Lit <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][2]' id='block[a][2]' />Eng Lit <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][3]' id='block[a][3]' />French <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][4]' id='block[a][4]' />Maths and Stats <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][5]' id='block[a][5]' />Media Studies <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][6]' id='block[a][6]' />Product Design <span style='float: right;'>[25/30]</span></label><br />
        <label><input type='radio' name='block[a][7]' id='block[a][7]' />Sociology <span style='float: right;'>[25/30]</span></label><br />
     </td>
     <td>
         <label><input type='radio' name='block[b][]' />Art</label><br />
         <label><input type='radio' name='block[b][]' />Biology</label><br />
         <label><input type='radio' name='block[b][]' />Business Studies</label><br />
         <label><input type='radio' name='block[b][]' />Drama</label><br />
         <label><input type='radio' name='block[b][]' />History</label><br />
         <label><input type='radio' name='block[b][]' />IT</label><br />
         <label><input type='radio' name='block[b][]' />Maths and Mechanics</label><br />
         <label><input type='radio' name='block[b][]' />PE</label><br />
         <label><input type='radio' name='block[b][]' />Pyschology</label><br />
	 </td>
     <td>
         
         <label><input type='radio' name='block[c][]' />Biology</label><br />
         <label><input type='radio' name='block[c][]' />Eng Lit</label><br />
         <label><input type='radio' name='block[c][]' />Ethics</label><br />
         <label><input type='radio' name='block[c][]' />Geography</label><br />
         <label><input type='radio' name='block[c][]' />German</label><br />
         <label><input type='radio' name='block[c][]' />IT</label><br />
         <label><input type='radio' name='block[c][]' />Maths and Mechanics</label><br />
         <label><input type='radio' name='block[c][]' />Maths and Stats</label><br />
         <label><input type='radio' name='block[c][]' />Psychology</label><br />
         <label><input type='radio' name='block[c][]' />Sociology</label><br />
	 </td>
     <td>
	 
	 <label><input type='radio' name='block[d][]' />Art</label><br />
	 <label><input type='radio' name='block[d][]' />Chemistry</label><br />
	 <label><input type='radio' name='block[d][]' />Drama</label><br />
	 <label><input type='radio' name='block[d][]' />Economics</label><br />
	 <label><input type='radio' name='block[d][]' />Maths and Mechanics</label><br />
	 <label><input type='radio' name='block[d][]' />Music</label><br />
	 <label><input type='radio' name='block[d][]' />Politics</label><br />
	 <label><input type='radio' name='block[d][]' />Psychology</label><br />
	 <label><input type='radio' name='block[d][]' />Spanish</label><br />
	 </td>
     <td>
	 
	 <label><input type='radio' name='block[e][]' />Biology</label><br />
	 <label><input type='radio' name='block[e][]' />Business Studies</label><br />
	 <label><input type='radio' name='block[e][]' />Eng Lang and Lit</label><br />
	 <label><input type='radio' name='block[e][]' />Food</label><br />
	 <label><input type='radio' name='block[e][]' />History</label><br />
	 <label><input type='radio' name='block[e][]' />Maths and Statistics</label><br />
	 <label><input type='radio' name='block[e][]' />Media Studies</label><br />
	 <label><input type='radio' name='block[e][]' />Physics</label><br />
	 <label><input type='radio' name='block[e][]' />Textiles</label><br />
	 </td>
     <td>
	  <label><input type='radio' name='block[t][]' />Photography</label><br />
	  <label><input type='radio' name='block[t][]' />Further Maths</label><br />
	  <label><input type='radio' name='block[t][]' />Biology</label><br />
	 </td>
    </table>
   </div>
  </form>
 </body>
</html>