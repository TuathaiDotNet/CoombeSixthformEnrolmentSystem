<?php 
require_once('config.inc.php');
require_once('header.inc.php');
require_once('footer.inc.php');
require_once('select_widget.php');

print_header($title = 'Coombe Sixth Form Enrolment - GCSE Results', 
			$hide_title_bar = false,
			$script = "", 
			$exclude_datatables_js = false, 
			$meta = "", 
			$extra_script = "/results.js.php");
?>

   <div class='block' >
    <div id="dynamic">
    <span><a id="new_student" href="">Add New Student</a></span>
     <table cellpadding="0" cellspacing="0" border="0" class="display" id="students">
      <thead>
       <tr>
			<th width="7%">Student ID</th>
			<th width="20%">First Name</th>
			<th width="20%">Surname</th>
			<th width="20%">Previous Institution</th>
			<th width="7%">Enrolment Year</th>
			<th width="20%">Student Type</th>
			<th></th>
			<th></th>
			<th></th>
       </tr>
      </thead>
      <tbody>
       <tr>
        <td colspan="8" class="dataTables_empty">Loading data from server</td>
       </tr>
      </tbody>
     </table>
    </div><!-- class=dynamic -->
   </div><!-- class=block -->
 
   <div id="debug" class="debug"></div>

   <iframe frameborder=0 style="width: 78%; height: 410px;" id="students_results"></iframe>
   <iframe frameborder=0 style="width: 18%; height: 410px; float: right;" id="average_results"></iframe>

<?php
print_footer();
?>
