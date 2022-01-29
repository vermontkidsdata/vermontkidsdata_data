<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container">
<h4 style="margin-top: 20px;">Saved Reports</h4>
<?php //print_r($dataset); ?>
	<div class="row">
	<div class="p-3 mb-2 bg-info text-white" style= "margin-bottom: 10px; margin-top: 20px; width: 100%">Report configurations that have been saved</div>
		 <table border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
      <thead>
        <tr class="webpartcaption">
          <th align="left" valign="bottom">
            Name
          </th>
          <th align="left" valign="bottom">
            Source
          </th>
          <th align="left" valign="bottom">
            Year
          </th>
          <th align="left" valign="bottom">
            State
          </th>
          <th align="left" valign="bottom">
            Geography
          </th>
          
          <th></th>
          <th></th>
        </tr>
      </thead>
      <?php foreach($reports as $r) {  ?>
       <tr onmouseover="javascript:this.style.backgroundColor='#C0C0C0';" onmouseout="javascript:this.style.backgroundColor='';">
            <td valign="top" align="left" >
            <b>
            <a href="/<?php echo $r->id; ?>" style="color: #4f5d73;"><?php echo $r->name; ?></a>
            </b>
            </td>
            <td>
            <?php echo $r->datasetname; ?>
            </td>
            <td>
            <?php echo $r->year; ?>
            </td>
            <td>
            <?php echo $r->STATENAME; ?>
            </td>
            <td>
            <?php echo $r->geography; ?>
            </td>
            <td valign="top" align="left" ></td>
            <td valign="top" align="left" ><a  href="/<?php echo $r->id; ?>">
							<i class="cil-pencil"></i></a></td>
						<td valign="top" align="left" width="25%"><a  href="#">
							<i class="cil-trash"></i></a></td>
       </tr>
       <?php }   ?>
       </table>
	</div>

</div>


<?php $this->load->view('footer'); ?>