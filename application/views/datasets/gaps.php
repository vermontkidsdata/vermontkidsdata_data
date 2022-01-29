<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container-fluid" style="margin-top: 30px;">

<style type="text/css" src="/stylesheets/Datacatalog.css"></style>


    <script type="text/javascript" src="/scripts/kinetic/kinetic-v4.5.4.min.js"></script>
    <script type="text/javascript" src="/scripts/datacatalog/catalogajax.js"></script>
    <script type="text/javascript" src="/scripts/datacatalog/catalog.js"></script>



<div class="container-fluid">
	<div class="row">
		<div class="col-md-6"><h3>Vermont's Early Childhood Systems Needs Assessment - Data Gaps</h3></div>
	</div>
	<div class="row">
		<table  cellpadding="3">
			<tr>
				<td><img src="/images/catalog/element_1.png" /></td>
				<td class="catalog-txt">Data available and currently in our system
				<td><img src="/images/catalog/element_2.png" /></td>
				<td class="catalog-txt">Data limited and not yet in our system
				<td><img src="/images/catalog/element_3.png" /></td>
				<td class="catalog-txt">Data gap</td>
				<td style="padding-left: 50px;">
					<div id="text-box" style="display: none"></div>
				</td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" style="" border=0 align="center" >

		</table>
	</div>
</div>

<div id="container" style=" margin-bottom: 20px;  width: 100%; height: 640px;">
					Please note this data catalog requires a modern browser that supports HTML5. 
					Upgrade your browser to start using it now!</div>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #99a2ab;display: none" border=1 align="center" >
	<tr>
		<td id="control-cell" valign="top" style=" padding-top: 10px; padding-right: 5px; padding-left: 5px; width: 300px;">
		
		<table cellpadding="0" cellspacing="0" border=0><tr><td>
		<div style="background-color: #aeb5c5; padding: 10px;" id="intro-container">
			<div style="font-weight: bold; margin-bottom: 10px;">ECDRS Data Catalogue Visualization Tool - Work In Progress (09-23-13)</div>
			<div style="line-height: 20px;">
			<p>
			The ECDRS data catalogue represents a unified data dictionary as data elements are identified and brought into the ECDRS. It is also used as an index so end users can search, map, and organize data elements. We also hope to expand its capacity so that ECDRS data contributors can collaboratively grow the data catalogue.
			</p><p>
			The data elements used in ECDRS were identified when unpacking the eleven key policy questions. These data elements were subsequently grouped into logical multiple views (policy questions, indicators of progress and socio-demographic factors) with relationships defined to form a data model. 
</p><p>
			Each data element has attributes, key descriptors (e.g., name, definition source and data steward) assigned. The data elements are acquired through building data sharing agreements with governmental and non-governmental organizations at the national, state and local level as well as acquired through public datasets such as the Census Bureau.
</p><p>
			The visualization of the data catalogue lets users visually explore the catalog and the relationships between the data elements, constructs and policy questions. It can help make sense of complex information and relationships through visual means of order and clarity, facilitated by technology. We give one example in this tool using the construct of attendance, as it is a very important factor in school success among children and youth. 
			If you are interested in helping us build out our data visualization tool let us know. 
			
			</p>
			<p>Start by clicking the button to the right &raquo;</p> 
			</div>
		</div>
		</td></tr></table>
		
		<div style="background-color: #aeb5c5; padding: 10px; display: none" id="control-container" >
		<table cellpadding="0" cellspacing="0" border=0>
			<tr>
				<td>
				<div class="zoom-container">
					<table width="100" align="center"  cellpadding="0" cellspacing="0" >
						<tr>
							<td><div class="zoom-control" onclick="zoomStageOut();">-</div></td>
							<td class="zoom-tick tick-on" id="zoom1"></td>
							<td class="zoom-tick tick-off" id="zoom2"></td>
							<td class="zoom-tick tick-off" id="zoom3"></td>
							<td class="zoom-tick tick-off" id="zoom4"></td>
							<td class="zoom-tick tick-off" id="zoom5"></td>
							<td class="zoom-tick tick-off" id="zoom6"></td>
							<td class="zoom-tick tick-off" id="zoom7"></td>
							<td class="zoom-tick tick-off" id="zoom8"></td>
							<td class="zoom-tick tick-off" id="zoom9"></td>
							<td class="zoom-tick tick-off" id="zoom10"></td>
							<td><div class="zoom-control" onclick="zoomStageIn();">+</div></td>
						</tr>
					</table>
				</div>
				</td>
				<td style="padding-left: 5px; padding-right: 5px;"><input type="button" class="catalog-btn" value="View Your Data" onclick="showData();" id="data-button" /></td>
				<td><input type="button" class="catalog-btn" value="Get Your Story" onclick="showStory();" id="story-button" /></td>
			</tr>
		</table>
		
		<table style="" width="100%"  cellpadding="0" cellspacing="0" class="info-table">
			<tr>
				<td valign="top">
				<div class="catalog-label" style="font-weight: bold;">Visualize Data Elements</div>
				<div style="">
					<select id="data_element" name="data_element" style="font-size: 11px;">
						<option value="none">-- Select a Data Element --</option>
						<?php foreach($dataElements as $d){ ?>
						<option value="<?php echo $d->variable_name; ?>"><?php if(strlen($d->variable_name) > 35){ echo substr($d->variable_name, 0, 34); } else { echo $d->variable_name; }?></option>
						<?php } ?>
					</select>
					<input type="button" class="catalog-btn" value="Visualize" onclick="showDataRelationships();" />
					<div class="catalog-label" style="font-weight: bold;">Explore Data Associations</div>
					<select id="meta" name="meta" style="font-size: 11px; height: 20px;">
						<option value="none">-- Select a Meta Keyword --</option>
						<option value="attendance">attendance</option>
					</select>
					<input type="button" class="catalog-btn" value="Find" onclick="showDataMetaRelationships();" />
					<div id="metaElts"></div>
				</div>
				</td>
			</tr>
			<tr>
			<td>
				
			</td>
			</tr>
		</table>
				
		<div style="margin-top: 10px">
		
		</div>
		</div>
		
		</td>
		<td width="950" valign="top" align="left" style="padding-top: 10px;">
		<div style="width:955px; overflow: hidden">
			<table width="1910" cellpadding="0" cellspacing="0" id="catalog-table" style="margin-left: 0px;">
				<tr>
					<td width="955" valign="top">
					<div id="container" style=" margin-bottom: 20px;  width: 950px; height: 640px;">
					Please note this data catalog requires a modern browser that supports HTML5. 
					Upgrade your browser to start using it now!</div></td>
					<td width="955" valign="top">
						<div id="story" name="story" style=" border: 2px solid #9199a4; margin-bottom: 20px;width: 950px; height: 100%; min-height: 640px; background-color: white;">
						<div style="padding: 30px;">
							<div class="story-title">The Story that Your Data Tells</div>
							<div class="story-content" id="story-content"></div>
						</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		
		
		</td>
		
	</tr>
</table>



<script type="text/javascript">
    var stage = new Kinetic.Stage({
        container: 'container',
        width: 1200,
        height: 700
    });
    var imageObj = new Image();
    var imageObjStarSmall = new Image();
    var imageObjElementNoData = new Image();
    var imageObjElementNoDataNotCollected = new Image();
    var imageObjElementData = new Image();
    imageObj.src = '/images/catalog/clickhere.png';
    //imageObj.src = '/images/catalog/sun.png';
    imageObjStarSmall.src = '/images/catalog/star-small.png';
    imageObjElementNoData.src = '/images/catalog/element_star_nodata.png';
    imageObjElementNoDataNotCollected.src = '/images/catalog/element_star_nodata_not_collected.png';
    imageObjElementData.src = '/images/catalog/element_star.png';

    var imgWidth = 246;
    var imgHeight = 245;
    //var imgWidth = 107;
    //var imgHeight = 100;

    var imgX = (stage.attrs.width/2) - (imgWidth/2);
    var imgY = (stage.attrs.height/2) - (imgHeight/2);
    //console.log(imgX);
    catalogInit()

</script>



 <script>

      

    </script>

</div>


<?php $this->load->view('footer'); ?>