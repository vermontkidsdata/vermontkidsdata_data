<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container-fluid">

<div class="main-container">

	<div class="row">
		<div><h4>Manaage Custom Geography Maps for Census and ACS Reports</h4></div>
		<input type="hidden" value="<?php echo $auth_user_id; ?>" id="userId" />
	</div>
	
	<div class="row">
		<div id="react_file_upload"></div>
	</div>

</div>

</div>

<!-- Load React. -->
  <!-- Note: when deploying, replace "development.js" with "production.min.js". -->
  <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <!-- Load Babel so we can use ES6 and JSX -->
  <!-- <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script> -->
  

  <!-- Load our React component. -->
  <script type="module" src="/scripts/react/dist/components/upload-files-component.js<?php echo '?v='.time(); ?>"></script>
  <script type="module" src="/scripts/react/dist/services/upload-files-service.js<?php echo '?v='.time(); ?>"></script>
  <script type="module" src="/scripts/react/dist/http-common.js<?php echo '?v='.time(); ?>"></script>
  <script type="module" src="/scripts/react/dist/components/react_file_upload.js<?php echo '?v='.time(); ?>"></script>


<?php $this->load->view('footer'); ?> 
