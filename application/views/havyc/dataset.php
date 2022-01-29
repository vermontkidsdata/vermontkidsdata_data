<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<!-- 	window.chartColors = {
			red: 'rgb(255, 99, 132)',
			orange: 'rgb(255, 159, 64)',
			yellow: 'rgb(255, 205, 86)',
			green: 'rgb(75, 192, 192)',
			blue: 'rgb(54, 162, 235)',
			purple: 'rgb(153, 102, 255)',
			grey: 'rgb(201, 203, 207)'
		}; -->


<?php //print_r($columns); ?>

<div class="container">

    <?php if(isset($dataset)){ ?>
    <h4 style="margin-top: 20px;">Vermontkidsdata.org Summary Data Set: <?php echo $dataset->title; ?></h4>
    <?php } else { ?>
    <h4 style="margin-top: 20px;">Create Vermontkidsdata.org Summary Data Set</h4>
    <h6>* Note a prerequisite for creatinig a data set is that a table has been created in MySQL</h6>
    <?php }  ?>

    <div style="">
    <form method="post" action="/havyc/save_dataset">
        <input type="hidden" id="id" name="id" value="<?php if(isset($dataset)){ echo $dataset->id; } ?>" />
        <div class="form-row">
            <div class="form-group col-md-12">
            <label for="title">Data Set Name</label>           
              <input type="text" class="form-control" id="title" name="title" value="<?php if(isset($dataset)){ echo $dataset->title; } ?>"> 
             </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
            <label for="data_table">Database Table Name</label>
              <input type="text" class="form-control" id="data_table" name="data_table" value="<?php if(isset($dataset)){ echo $dataset->data_table; } ?>">
            </div>

             <div class="form-group col-md-6">
              <label for="owner">Data Set Owner</label>
              <input type="text" class="form-control" id="owner" name="owner"   value="<?php if(isset($dataset->owner)){ echo $dataset->owner; } ?>">
            </div>
         </div>

        <div class="form-row">
        <div class="col-md-12">
       <label for="columns">Database Table Columns</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">Comma separated list</div>
            </div>
            <input type="text" class="form-control" id="columns" name="columns" value="<?php if(isset($dataset)){ echo $dataset->columns; } ?>">
          </div>
        </div>
        </div>

        <div class="form-row" style="display: none">
            <div class="col-md-6">
                 <label class="my-1 mr-2" for="form_input">Additional Form Inputs</label>
                  <select class="custom-select my-1 mr-sm-2" id="form_input">
                    <option selected>Add an input...</option>
                    <option value="1">Year</option>
                    <option value="2">School Year</option>
                    <option value="3">Number</option>
                      <option value="3">Percent</option>
                      <option value="3">Count</option>

                  </select>
            </div>
            <div class="col-md-6">
            </div>
        </div>

        <div class="form-row" style="margin-top: 2em;">
            <input type="submit" value="Save" />
        </div>

    </form></div>

<?php if(isset($dataset)){ ?>
    

<form method="post" action="/havyc/add_data">
<input type="hidden" value="<?php echo $dataset->id; ?>" name="dataset_id" id="dataset_id" />
<div class="row">
	<div class="p-3 mb-2 bg-info text-white" style= "margin-bottom: 10px; margin-top: 20px; width: 100%">
	<?php echo $dataset->title; ?> Current Raw Data
	</div>

		 <table border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
      <thead>
        <tr class="webpartcaption">
        <?php foreach($columns as $c) { ?>
          <th align="left" valign="bottom">
           <?php echo $c; ?>
          </th>
          <?php } ?> 
          <th></th>        
        </tr>
      </thead>
      
      <tr>	
			<td></td>
      <?php $this->load->view('havyc/data_entry/'.$dataset->data_table); ?>
      	<td><input type="radio" name="is_current" value="1" checked /> Yes <input type="radio" name="is_current" value="0" /> No </td>
				<td><input type="submit" value="Add" /></td>
			</tr>
      
      <?php foreach($datasetdata as $d) {  ?>
       <tr onmouseover="javascript:this.style.backgroundColor='#C0C0C0';" onmouseout="javascript:this.style.backgroundColor='';">
            <?php foreach($columns as $c) { $colName = $c; ?>
            <td valign="top" align="left" ><?php echo $d->$colName; ?></td>
            <?php } ?>
            <td><a class="" href="/havyc/delete_data/<?php echo $dataset->id; ?>/<?php echo $d->id; ?>" style="font-size: 1em;">
						<i class="cil-trash"></i></a></td>
       </tr>
       <!--
       <tr>	
				<td></td>
       	<?php $this->load->view('havyc/data_entry/'.$dataset->data_table); ?>
       	<td><input type="radio" name="is_current" value="1" checked /> Yes <input type="radio" name="is_current" value="0" /> No </td>
				<td><input type="submit" value="Update" /></td>
			</tr>
			  -->
       
       <?php } ?>
      
       </table>
    
        <?php }  ?>


	</div>
	</form>
	
	<?php //print_r( $datasetdata ); ?>


<?php $this->load->view('footer'); ?>