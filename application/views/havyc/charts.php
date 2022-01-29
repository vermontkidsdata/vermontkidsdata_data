<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container">
<h4 style="margin-top: 20px;">Charts / Visualizations<div style="float: right"><a href="/charts/edit">
							<i class="cil-chart	"></i></a></div></h4>


	<div class="row">
		 <table border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-striped">	
      <thead>
        <tr class="webpartcaption">
        	
          <th align="left" valign="bottom">
            Title
          </th>
          <th align="left" valign="bottom">
            Chart Type
          </th>
          <th align="left" valign="bottom">
            Chart ID (for shortcode)
          </th>
          <th align="left" valign="bottom">
            Embed Code
          </th>          
          <th></th>
          <th></th>
        </tr>
      </thead>
      
<?php foreach($charts as $c) {  ?>
       <tr onmouseover="javascript:this.style.backgroundColor='#C0C0C0';" onmouseout="javascript:this.style.backgroundColor='';">
             <td valign="top" align="left" ><b><?php echo $c->chart_title; ?></b></td>
             <td valign="top" align="left" ><?php echo $c->chart_type; ?></td>
             <td valign="top" align="left" ><?php echo $c->id; ?></td>           
            <td valign="top" align="left" >
            <div style="display:none"><textarea style="width: 300px; height: 100px;"><iframe width="560" height="315" src="http://bbf:8080/havyc/chart_embed/<?php echo $c->id;?>" frameborder="0"></iframe></textarea></div>
            </td>
            <td valign="top" align="left" ><a href="/charts/edit/<?php echo $c->id; ?>">
							<i class="cil-pencil"></i></a></td>
						<td valign="top" align="left" width="25%"><a href="#">
							<i class="cil-trash"></i></a></td>
       </tr>
       <?php  } ?>
       </table>
	</div>

</div>


<?php $this->load->view('footer'); ?>