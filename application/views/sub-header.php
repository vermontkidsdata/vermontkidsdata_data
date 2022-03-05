
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">

<div class="c-sidebar-brand d-md-down-none" >
<svg class="" width="200" height="99" alt="Vermontkidsdata Logo">
<image href="/images/vtkids-logotype-white.png" height="99" width="200"/>
</svg>
</div>


<ul class="c-sidebar-nav">
<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/">
<svg class="c-sidebar-nav-icon">
<use xlink:href="/images/coreui/sprites/free.svg#cil-speedometer"></use>
</svg> Dashboard</a></li>
<li class="c-sidebar-nav-title">Data Management</li>
<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/havyc">
<i class="fas fa-database left-menu-icon"></i> Summary Data Sets</a></li>

<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/datasets">
<i class="fas fa-database left-menu-icon"></i> Raw Data Sets</a></li>

<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/havyc/charts">
<i class="far fa-chart-bar left-menu-icon"></i> Charts/Visualizations</a></li>

<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/census">
<i class="fas fa-globe left-menu-icon"></i> Census/ACS Data</a></li>

<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/census/reports">
<i class="fas fa-suitcase left-menu-icon"></i>Census Reports</a></li>

<li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/census/geography_map">
<i class="fas fa-globe-americas left-menu-icon"></i>Geography Maps</a></li>




</ul>
</div>

<div class="c-wrapper">
<header class="c-header c-header-light c-header-fixed">

<button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" 
data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
<svg class="c-icon c-icon-lg">
<use xlink:href="/images/coreui/sprites/free.svg#cil-menu"></use>
</svg>
</button>

<ul class="c-header-nav d-md-down-none">
<li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="/">Dashboard</a></li>
<li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="/users"><i class="fas fa-user left-menu-icon"></i> Users</a></li>
<li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="/"><i class="fas fa-cog left-menu-icon"></i> Settings</a></li>
<li class="c-header-nav-item px-3">From GitHub</li>
<li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="/userlogin/logout">Logout</a></li>

</ul>

<div class="c-subheader justify-content-between px-3" style="display: none">

<ol class="breadcrumb border-0 m-0 px-0 px-md-3">
<li class="breadcrumb-item">Dashboard</li>
<?php if(isset($title)) { echo $title; } else { ?>
<li class="breadcrumb-item"><a href="/zilliance/manage_resource">Manage Resources</a></li>
<?php } ?>

</ol>
<div class="c-subheader-nav d-md-down-none mfe-2">

<a class="c-subheader-nav-link" href="#">
<svg class="c-icon">
<use xlink:href="/images/coreui/sprites/free.svg#cil-graph"></use>
</svg> &nbsp;Dashboard</a><a class="c-subheader-nav-link" href="#">
<svg class="c-icon">
<use xlink:href="/images/coreui/sprites/free.svg#cil-settings"></use>
</svg> &nbsp;Settings</a></div>
</div>



</header>
