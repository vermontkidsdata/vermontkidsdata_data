<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container">
<h4 style="margin-top: 20px;">Users <div style="float: right"><a href="/users/edit">
							<i class="fas fa-user" style="color: #808080;"></i></a></div></h4>
<?php //print_r($dataset); ?>
	<div class="row">
	<div class="p-3 mb-2 bg-info text-white" style= "margin-bottom: 10px; margin-top: 20px; width: 100%">data.vermontkidsdata.org system users</div>
		 <table border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
      <thead>
        <tr class="webpartcaption">
          <th align="left" valign="bottom">
            User ID
          </th>
          <th align="left" valign="bottom">
            User Name
          </th>
          <th align="left" valign="bottom">
            Email
          </th>
          
          <th></th>
          <th></th>
        </tr>
      </thead>
      <?php foreach($users as $u) {  ?>
       <tr onmouseover="javascript:this.style.backgroundColor='#C0C0C0';" onmouseout="javascript:this.style.backgroundColor='';">
            <td valign="top" align="left" >
            <b>
            <a href="/<?php echo $u->user_id; ?>" style="color: #4f5d73;"><?php echo $u->user_id; ?></a>
            </b>
            </td>
            <td>
            <?php echo $u->username; ?>
            </td>
            <td>
            <?php echo $u->email; ?>
            </td>
            
            <td valign="top" align="left" ></td>
            <td valign="top" align="left" ><a  href="/<?php echo $u->user_id; ?>">
							<i class="cil-pencil"></i></a></td>
						<td valign="top" align="left" width="25%"><a  href="#">
							<i class="cil-trash"></i></a></td>
       </tr>
       <?php }   ?>
       </table>
	</div>

</div>


<?php $this->load->view('footer'); ?>