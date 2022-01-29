<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$int = '$int';
$float = '$float';

/*
| -------------------------------------------------------------------
| QUERIES
| -------------------------------------------------------------------
| This file contains an array of queries.  It is used by the
| Download controller.
|
*/
$config["queries"] = array(
	"b17001_5_year_poverty_age_sex" => "SELECT 
    `b17001_5_year_poverty_age_sex`.`id` `id$int`,
    `b17001_5_year_poverty_age_sex`.`geography_name`,
    `b17001_5_year_poverty_age_sex`.`geography_code`,
    `b17001_5_year_poverty_age_sex`.`geography_label`,
    `b17001_5_year_poverty_age_sex`.`year` `year$int`,
    `b17001_5_year_poverty_age_sex`.`acs_year`,
    `b17001_5_year_poverty_age_sex`.`age_group`,
    `b17001_5_year_poverty_age_sex`.`gender`,
    `b17001_5_year_poverty_age_sex`.`value` `value$float`,
    `b17001_5_year_poverty_age_sex`.`value_MOE_90` `value_MOE_90$float`,
    `b17001_5_year_poverty_age_sex`.`value_MOE_95` `value_MOE_95$float`,
    `b17001_5_year_poverty_age_sex`.`total_estimate` `total_estimate$float`,
    `b17001_5_year_poverty_age_sex`.`total_estimate_MOE_90` `total_estimate_MOE_90$float`,
    `b17001_5_year_poverty_age_sex`.`total_estimate_MOE_95` `total_estimate_MOE_95$float`,
    `b17001_5_year_poverty_age_sex`.`value_pct` `value_pct$float`,
    `b17001_5_year_poverty_age_sex`.`value_CI_95` `value_CI_95$float`,
    `b17001_5_year_poverty_age_sex`.`value_CI_95_size` `value_CI_95_size$int`,
    `b17001_5_year_poverty_age_sex`.`value_CI_95_lower` `value_CI_95_lower$float`,
    `b17001_5_year_poverty_age_sex`.`value_CI_95_upper` `value_CI_95_upper$float`,
    `b17001_5_year_poverty_age_sex`.`is_current` `is_current$int`,
    `b17001_5_year_poverty_age_sex`.`acs_year_compare`,
    `b17001_5_year_poverty_age_sex`.`count_difference` `count_difference$int`,
    `b17001_5_year_poverty_age_sex`.`percent_difference` `percent_difference$float`,
    `b17001_5_year_poverty_age_sex`.`percent_change` `percent_change$float`,
    `b17001_5_year_poverty_age_sex`.`rank` `rank$int`,
    `b17001_5_year_poverty_age_sex`.`rank_dense` `rank_dense$int`,
    `b17001_5_year_poverty_age_sex`.`rank_desc`,
    `b17001_5_year_poverty_age_sex`.`at_or_above_poverty`,
    `b17001_5_year_poverty_age_sex`.`at_or_above_poverty_MOE_90`,
    `b17001_5_year_poverty_age_sex`.`at_or_above_poverty_MOE_95`
FROM `b17001_5_year_poverty_age_sex`",
    "b17001a_g_5_year_poverty_age_sex_race" => "SELECT
    `id` `id$int`,
    `location_name`,
    `location_code`,
    `location`,
    `year`,
    `years`,
    `age_group`,
    `sex`,
    `estimate`,
    `estimate_90_MOE`,
    `estimate_95_MOE`,
    `estimate_of_all`,
    `estimate_of_all_90_MOE`,
    `estimate_of_all_95_MOE`,
    `percent`,
    `_95_CI`,
    `_95_CI_size`,
    `_95_CI_lower`,
    `_95_CI_upper`,
    `is_current`,
    `year_compare`,
    `estimate_difference`,
    `percentage_point_difference`,
    `percentage_change`,
    `rank_raw`,
    `rank_dense`,
    `rank`,
    `at_or_above_poverty`,
    `at_or_above_poverty_90_MOE`,
    `at_or_above_poverty_95_MOE`,
    `race`
FROM
    `b17001a_g_5_year_poverty_age_sex_race`",
    "b17024_5_year_under_fpl" => "SELECT
	    `id` `id$int`,
	    `location_name`,
	    `location_code`,
	    `location`,
	    `geography`,
	    `year` `year$int`,
	    `years`,
	    `age_group`,
	    `sex`,
	    `estimate` `estimate$int`,
	    `estimate_MOE_90` `estimate_MOE_90$int`,
	    `estimate_MOE_95` `estimate_MOE_95$int`,
	    `estimate_of_all` `estimate_of_all$int`,
	    `estimate_of_all_90_MOE` `estimate_of_all_90_MOE$int`,
	    `estimate_of_all_95_MOE` `estimate_of_all_95_MOE$int`,
	    `percent` `percent$float`,
	    `_95_CI` `_95_CI$float`,
	    `_95_CI_size` `_95_CI_size$float`,
	    `_95_CI_lower` `_95_CI_lower$float`,
	    `_95_CI_upper` `_95_CI_upper$float`,
	    `is_current` `is_current$int`,
	    `year_compare` `year_compare$int`,
	    `estimate_difference`,
	    `percentage_point_difference`,
	    `percentage_change`,
	    `rank_raw` `rank_raw$int`,
	    `rank_dense` `rank_dense$int`,
	    `rank`,
	    `rank_sort` `rank_sort$int`,
	    `percent_fpl`
	FROM
	    `b17024_5_year_under_fpl`"
);
