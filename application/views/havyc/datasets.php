<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container">
<h4 style="margin-top: 20px;">External Datasets</h4>
<?php //print_r($dataset); ?>
	<div class="row">
	<div class="p-3 mb-2 bg-info text-white" style= "margin-bottom: 10px; margin-top: 20px; width: 100%">Data sets produced and owned by entities other than BBF</div>
		 <table border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
      <thead>
        <tr class="webpartcaption">
          <th align="left" valign="bottom">
            Title
          </th>
          <th align="left" valign="bottom">
            Owner
          </th>
          <th align="left" valign="bottom">
            Charts/Visualizations
          </th>
          <th align="left" valign="bottom">
            API Endpoint
          </th>
          
          <th></th>
          <th></th>
        </tr>
      </thead>
      <?php foreach($dataset as $d) { if($d->owner != 'havyc'){  ?>
       <tr onmouseover="javascript:this.style.backgroundColor='#C0C0C0';" onmouseout="javascript:this.style.backgroundColor='';">
            <td valign="top" align="left" ><b><a  href="/datasets/dataset/<?php echo $d->id; ?>" style="color: #4f5d73;"><?php echo $d->title; ?></a></b></td>
            <td>
            <ol>
            	<?php foreach($d->charts as $c) { ?>
            	<li><a href="/charts/edit/<?php echo $c->id; ?>"><?php echo $c->chart_title; ?></a></li>
            	<?php } ?>
            </ol>
         	
            </td>
            <td valign="top" align="left" ><a href="/v1/havyc_dataset_data/<?php echo $d->id; ?>">/v1/havyc_dataset_data/<?php echo $d->id; ?></a></td>
            <td valign="top" align="left" ><a  href="/havyc/dataset/<?php echo $d->id; ?>">
							<i class="cil-pencil"></i></a></td>
						<td valign="top" align="left" width="25%"><a  href="#">
							<i class="cil-trash"></i></a></td>
       </tr>
       <?php } }  ?>
       </table>
	</div>

</div>


<?php $this->load->view('footer'); ?>