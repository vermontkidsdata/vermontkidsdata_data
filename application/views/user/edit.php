<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container" >
<h4 style="margin-top: 20px;">Add/Edit User</h4>

	
<form method="post" action="/users/save">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="firstname">First Name</label>
      <input type="text" class="form-control" id="firstname" name="firstname" >
    </div>
    <div class="form-group col-md-6">
      <label for="lastname">Last Name</label>
      <input type="text" class="form-control" id="lastname" name="lastname">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="username">User Name</label>
      <input type="text" class="form-control" id="username" name="username" >
    </div>
    <div class="form-group col-md-6">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
  </div>
  <div class="form-row">
     <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="email" name="email" >
    </div>
    <div class="form-group col-md-6">
      <label for="inputState">Role</label>
      <select id="role" name="role" class="form-control">
        <option selected>Choose...</option>
        <option value="9">Administrator</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" >
  </div>
  
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">City</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">State</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Zip</label>
      <input type="text" class="form-control" id="inputZip">
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Save</button>
</form>

</div>



<?php $this->load->view('footer'); ?>