<?php
class NSCH_model extends CI_Model {

/**
This model is based on analysis done by the Vermont Department of Health for Building Bright Futures with 
NSCH data 2016-2018, and contained in Data for BBF HAVYC MH Highlight & Overall Report, updated 10.21.2019.xls

Methodology was taken from SAS file NSCH1618_CB.sas.  Data was loaded from raw NSCH files into MySQL tables, and 
subsequent analyis done mostly in straight SQL queries, with programmatic manipulation as needed for presentation.

NOTE: All variable names were taken from the original file, 'as-is'.  Therefore, notations of '16' or '18', etc... may not 
be intuitive for calculations run on different years.

**/

  public function __construct()
   {
      $this->load->database();
   }
   
   function addData($tableName, $data){
   	$this->db->insert($tableName, $data);
   }
   
   function deleteData($tableName, $dataId){
   	$this->db->delete($tableName, array( 'id' => $dataId));
   }

   function getAdequateInsurance($reportYears = '', $reportAges = '', $reportStates = '50'){

        $years = explode('~',$reportYears);
        $ages = explode('~',$reportAges);
        $states = explode('~',$reportStates);
        $agesIn = implode(',',$ages);
        $statesIn = implode(',', $states);
        //get the number of years for the analyis, to use when combining weighted values
        $numYears = count($years);
        /* logic taken from VDH NSCH SAS script */
        $sql = "select 
        CASE
        WHEN InsGap_1618 in (1,-99) and InsAdeq_1618 in (1,-99) then  1
        WHEN CURRINS = 2 or InsGap_1618 = 2 or InsAdeq_1618 = 2 then 2
        WHEN InsGap_1618 = -99 and InsAdeq_1618 = -99 then  -99
        END as Insurance_1618,SUM(FWCANNUAL) as WeightedFrequency
        From (
        select *, 
        CASE
        WHEN benefits_1618 in (1,2,-99) and allows_1618 in (1,2,-99) and expense_1618 in (1,2,4,-99) then 1
        WHEN benefits_1618 = 3 or allows_1618 = 3 or expense_1618 = 3 then 2
        WHEN benefits_1618 = -99 and allows_1618 = -99 and expense_1618 = -99 then -99
        WHEN CURRINS = 2 then -99
        END as InsAdeq_1618                                                                          
        from (
        Select INSGAP,
        CASE 
        WHEN INSGAP = 1 then  1
        WHEN INSGAP in (2,3) then 2
        WHEN CURRINS = 2 then  2
        When INSGAP = -99 then -99
        END
        as InsGap_1618,

        CASE 
        WHEN K3Q20 = 1 	then 1
        WHEN K3Q20 = 2 	then 2
        WHEN K3Q20 in (3,4) then 3
        WHEN K3Q20 = -99 then -99
        WHEN CURRINS = -99 then -99
        WHEN CURRINS = 2 then -99
        END as benefits_1618,

        CASE 
        WHEN K3Q22 = 1 	then 1
        WHEN K3Q22 = 2 	then 2
        WHEN K3Q22 in (3,4) then 3
        WHEN K3Q22 = -99 then -99
        WHEN CURRINS = -99 then -99
        WHEN CURRINS = 2 then -99
        END as allows_1618,

        CASE 
        WHEN K3Q21B = 1 	then 1
        WHEN K3Q21B = 2 	then 2
        WHEN K3Q21B in (3,4) then 3
        WHEN K3Q21B = -99 then -99
        WHEN HOWMUCH = 1 then 4
        WHEN CURRINS = -99 then -99
        WHEN CURRINS = 2 then -99
        END as expense_1618,

        CURRINS, FWC, FWC/".$numYears." as FWCANNUAL,HOWMUCH

        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
            $tmpSql  = "select FWC,SC_AGE_YEARS,CURRINS, INSGAP, K3Q20, K3Q22, K3Q21B,HOWMUCH from nsch_".$y."_topical n".$y."
            where SC_AGE_YEARS  in (".$agesIn.") ";

            if($reportStates != 'US') { 
                $tmpSql .= " and n".$y.".FIPSST in (".$statesIn.")";
            }  
            $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".

        ") as a 
        ) as b
        ) as c group by Insurance_1618 ";

        //echo $sql; exit;

        $query = $this->db->query($sql);
   	    $results = $query->result();
   	    
        //send back raw data - will have to finalize processing for VDH analysis from the calling application
        return $results;

   }

   function getAdverseChildhoodExperiences($reportYears = '', $reportAges = '', $reportStates = '50'){

        $years = explode('~',$reportYears);
        $ages = explode('~',$reportAges);
        $states = explode('~',$reportStates);
        $agesIn = implode(',',$ages);
        $statesIn = implode(',', $states);
        //get the number of years for the analyis, to use when combining weighted values
        $numYears = count($years);

        $sql ="
        select ACE2more_1618, SUM(FWCANNUAL) as FWCANNUAL from (
        select *,
        CASE
        WHEN AFE_CNT_R = 0 THEN  0
        WHEN AFE_CNT_R = 1 THEN 1
        WHEN AFE_CNT_R >= 2 THEN 2
        END as ACE2more_1618
        from (
        select *,
        ((CASE WHEN ACE1R = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE3 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE4 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE5 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE6 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE7 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE8 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE9 = 1 THEN 1 ELSE 0 END)+
        (CASE WHEN ACE10 = 1 THEN 1 ELSE 0 END)
        ) as AFE_CNT_R
        from (
        select *,
        CASE	
        WHEN ACE1 IN (3,4) THEN  1
        WHEN ACE1 IN (1,2) THEN 2
        END as ACE1R,
        FWC/".$numYears." as FWCANNUAL

        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
            $tmpSql = "select FWC,SC_AGE_YEARS,ACE1,ACE3,ACE4,ACE5,ACE6,ACE7,ACE8,ACE9,ACE10,K11Q43R from nsch_".$y."_topical n".$y."
            where SC_AGE_YEARS in (".$agesIn.") ";

            if($reportStates != 'US') { 
                $tmpSql .= " and n".$y.".FIPSST in (".$statesIn.")";
            }  

            $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".
        
        ") as a
        ) as b
        ) as c
        ) as d group by ACE2more_1618";
        

        $query = $this->db->query($sql);
   	    $results = $query->result();


        //print_r($results); exit;

        return $results;

       // print_r($retVal);
       // exit;
    }
   
   function getAnyBEM($reportYears = '', $reportAges = ''){

    $years = explode('~',$reportYears);
    $ages = explode('~',$reportAges);
    $agesIn = implode(',',$ages);
    //get the number of years for the analyis, to use when combining weighted values
    $numYears = count($years);

    $sql = "select 
        CASE
        WHEN ANY_BEM = 1 THEN 'Yes'
        WHEN ANY_BEM = 2 THEN 'No'
        END as HasCondition
        , 
        count(*) as Count, SUM(FWCANNUAL) as WeightedCount from (

        select *,

        CASE
        WHEN ADHD = 1 AND (ANXIETY=1 OR DEPRESSION=1 OR BEHAVIOR=1 OR AUTISM=1 OR DEVDELAY=1 OR LEARNDIS=1 OR SPEECH=1) THEN  1
        ELSE 2 
        END as ADHDPLUS,

        CASE
        WHEN AUTISM = 1 OR ADHD = 1 OR ANXIETY=1 OR DEPRESSION=1 OR BEHAVIOR=1 OR DEVDELAY=1 OR LEARNDIS=1 OR SPEECH=1 THEN  1
        ELSE  2
        END as ANY_BEM


         from
        (
        select SC_AGE_YEARS, FWC,
        FWC/".$numYears." as FWCANNUAL,

        CASE
        WHEN K2Q33A = 1 AND K2Q33B = 1 	THEN  1
        WHEN K2Q33A = 2 AND K2Q33B = 1 	THEN  1
        WHEN K2Q33A IN (1,-99) AND K2Q33B IN (2,-99) THEN  2
        WHEN K2Q33A IN (2,-99) AND K2Q33B IN (2,-99) THEN  2
        END as ANXIETY,
        CASE
        WHEN K2Q32A = 1 AND K2Q32B = 1 	THEN 1
        WHEN K2Q32A = 2 AND K2Q32B = 1 	THEN 1
        WHEN K2Q32A IN (1,-99) AND K2Q32B IN (2,-99) THEN 2
        WHEN K2Q32A IN (2,-99) AND K2Q32B IN (2,-99) THEN 2
        END as DEPRESSION,
        
        CASE
        WHEN K2Q34A = 1 AND K2Q34B = 1 	THEN  1
        WHEN K2Q34A = 2 AND K2Q34B = 1 	THEN  1
        WHEN K2Q34A IN (1,-99) AND K2Q34B IN (2,-99) THEN  2
        WHEN K2Q34A IN (2,-99) AND K2Q34B IN (2,-99) THEN  2
        END as BEHAVIOR,

        CASE
        WHEN K2Q31A = 1 AND K2Q31B = 1 	THEN  1
        WHEN K2Q31A = 2 AND K2Q31B = 1 	THEN  1
        WHEN K2Q31A IN (1,-99) AND K2Q31B IN (2,-99) THEN  2
        WHEN K2Q31A IN (2,-99) AND K2Q31B IN (2,-99) THEN  2
        END as ADHD,

        CASE
        WHEN K2Q35A = 1 AND K2Q35B = 1 	THEN  1
        WHEN K2Q35A = 2 AND K2Q35B = 1 	THEN  1
        WHEN K2Q35A IN (1,-99) AND K2Q35B IN (2,-99) THEN  2
        WHEN K2Q35A IN (2,-99) AND K2Q35B IN (2,-99) THEN  2
        END as AUTISM,

        CASE
        WHEN K2Q36A = 1 AND K2Q36B = 1 THEN  1
        WHEN K2Q36A = 2 AND K2Q36B = 1 	THEN  1
        WHEN K2Q36A IN (1,-99) AND K2Q36B IN (2,-99) THEN  2
        WHEN K2Q36A IN (2,-99) AND K2Q36B IN (2,-99) THEN  2
        END as DEVDELAY,

        CASE
        WHEN K2Q30A = 1 AND K2Q30B = 1 	THEN  1
        WHEN K2Q30A = 2 AND K2Q30B = 1 	THEN  1
        WHEN K2Q30A IN (1,-99) AND K2Q30B IN (2,-99) THEN  2
        WHEN K2Q30A IN (2,-99) AND K2Q30B IN (2,-99) THEN  2
        END as LEARNDIS,

        CASE
        WHEN K2Q37A = 1 AND K2Q37B = 1 	THEN  1
        WHEN K2Q37A = 2 AND K2Q37B = 1 	THEN  1
        WHEN K2Q37A IN (1,-99) AND K2Q37B IN (2,-99) THEN  2
        WHEN K2Q37A IN (2,-99) AND K2Q37B IN (2,-99) THEN  2
        END as SPEECH

        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
             $tmpSql = "select FWC,SC_AGE_YEARS,K2Q30A,K2Q30B,K2Q31A,K2Q31B,K2Q32A,K2Q32B,K2Q33A,K2Q33B,K2Q34A,K2Q34B,K2Q35A,K2Q35B,
             K2Q36A,K2Q36B,K2Q37A,K2Q37B from nsch_".$y."_topical n".$y."
             where n".$y.".FIPSST = 50 and SC_AGE_YEARS in (".$agesIn.") "; 
             $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".
       
        "       
        ) as a
        ) as b
        ) as c group by ANY_BEM";

        $query = $this->db->query($sql);
   	    return $query->result_array();

    }   

   function getFlourishing5m6y($reportYears = ''){

        $years = explode('~',$reportYears);
        //get the number of years for the analyis, to use when combining weighted values
        $numYears = count($years);

        //print_r($years); 

        $sql = "select 
         CASE
         WHEN flrish0to5_1618 = 1 THEN 'Meets 0-2 Items'
         WHEN flrish0to5_1618 = 2 THEN 'Meets 3 Items'
         WHEN flrish0to5_1618 = 3 THEN 'Meets all 4 Items'
         END as 'Flourishing',
         WeightedCount
 
         from (
         select flrish0to5_1618, SUM(FWCANNUAL) as WeightedCount from 
         (
        select * ,
        (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) as flrsh0to5ct,

        CASE 
        WHEN (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) = 0 THEN 1
        WHEN (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) = 1 THEN 1
        WHEN (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) = 2 THEN 1
        WHEN (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) = 3 THEN 2
        WHEN (tender_1618 + resil0to5_1618 + curious0to5_1618 + smile_1618) = 4 THEN 3
        END as flrish0to5_1618

         from (

        select SC_AGE_YEARS, FWC,
                FWC/".$numYears." as FWCANNUAL,
        CASE
        WHEN K6Q70_R = 1 THEN  1
        WHEN K6Q70_R in (2,3) THEN  0
        ELSE 0
        END as tender_1618,

        CASE
        WHEN K6Q73_R = 1 THEN  1
        WHEN K6Q73_R in (2,3) THEN  0
        ELSE 0
        END as resil0to5_1618,

        CASE
        WHEN K6Q71_R = 1 THEN 1
        WHEN K6Q71_R in (2,3) THEN 0
        ELSE 0
        END as curious0to5_1618,

        CASE
        WHEN K6Q72_R = 1 	THEN 1
        WHEN K6Q72_R in (2,3) THEN 0
        ELSE 0
        END as smile_1618
 
        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
            //this assumes that table names use the same naming convention, e.g. 'nsch_$$year_topical'
            $tmpSql = "select FWC,SC_AGE_YEARS, K6Q70_R,K6Q73_R,K6Q71_R,K6Q72_R from nsch_".$y."_topical n".$y."
                where n".$y.".FIPSST = 50 and SC_AGE_YEARS < 6 AND SC_AGE_LT6 != 1"; 
                $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".
                
        ") as a where (K6Q70_R <> -99 OR K6Q73_R <> -99 OR  K6Q71_R <> -99 OR K6Q72_R <> -99) 

        ) as b 

          ) as c
         group by flrish0to5_1618
         ) as d";
       
    	
   	    $query = $this->db->query($sql);
   	    return $query->result_array();
   }

   function getFlourishing6y8y($reportYears = ''){

        $years = explode('~',$reportYears);
        //get the number of years for the analyis, to use when combining weighted values
        $numYears = count($years);

        $sql = "select 
         CASE
         WHEN flrish6to17_1618 = 1 THEN 'Meets 0-1 Items'
         WHEN flrish6to17_1618 = 2 THEN 'Meets 2 Items'
         WHEN flrish6to17_1618 = 3 THEN 'Meets All 3 Items'
         END as 'Flourishing',
         WeightedCount
 
         from (
         select flrish6to17_1618, SUM(FWCANNUAL) as WeightedCount from 
         (
        select * ,
        (curious6to17_1618 + finishes_1618 + resil6to17_1618) as flrsh6to17ct,

        CASE 
        WHEN (curious6to17_1618 + finishes_1618 + resil6to17_1618 ) = 0 THEN 1
        WHEN (curious6to17_1618 + finishes_1618 + resil6to17_1618) = 1 THEN 1
        WHEN (curious6to17_1618 + finishes_1618 + resil6to17_1618) = 2 THEN 2
        WHEN (curious6to17_1618 + finishes_1618 + resil6to17_1618) = 3 THEN 3
        END as flrish6to17_1618

         from (

        select SC_AGE_YEARS, FWC,
                FWC/3 as FWCANNUAL,
        CASE
        WHEN K6Q71_R = 1 THEN  1
        WHEN K6Q71_R in (2,3,4) THEN  0
        ELSE 0
        END as curious6to17_1618,

        CASE
        WHEN K7Q84_R = 1 THEN  1
        WHEN K7Q84_R in (2,3,4) THEN  0
        ELSE 0
        END as finishes_1618,

        CASE
        WHEN K7Q85_R = 1 THEN 1
        WHEN K7Q85_R in (2,3,4) THEN 0
        ELSE 0
        END as resil6to17_1618

 
        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
            //this assumes that table names use the same naming convention, e.g. 'nsch_$$year_topical'
            $tmpSql = "select FWC,SC_AGE_YEARS, K6Q71_R,K7Q84_R,K7Q85_R from nsch_".$y."_topical n".$y." 
                 where n".$y.".FIPSST = 50 and SC_AGE_YEARS > 5 and SC_AGE_YEARS < 9"; 
                $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".
        
        ") as a where (K6Q71_R <> -99 OR K7Q84_R <> -99 OR  K7Q85_R <> -99) 

        ) as b 

          ) as c
         group by flrish6to17_1618
         ) as d";

         $query = $this->db->query($sql);
   	     return $query->result_array();

    }

   function getMentalHealth($reportYears = '', $reportAges = ''){

       $years = explode('~',$reportYears);
       $ages = explode('~',$reportAges);
       $agesIn = implode(',',$ages);
       //get the number of years for the analyis, to use when combining weighted values
       $numYears = count($years);

        $sql = "select ANY_BEM, count(*), SUM(FWCANNUAL) from (

        select *,

        CASE
        WHEN ADHD = 1 AND (ANXIETY=1 OR DEPRESSION=1 OR BEHAVIOR=1 OR AUTISM=1 OR DEVDELAY=1 OR LEARNDIS=1 OR SPEECH=1) THEN  1
        ELSE 2 
        END as ADHDPLUS,

        CASE
        WHEN AUTISM = 1 OR ADHD = 1 OR ANXIETY=1 OR DEPRESSION=1 OR BEHAVIOR=1 OR DEVDELAY=1 OR LEARNDIS=1 OR SPEECH=1 THEN  1
        ELSE  2
        END as ANY_BEM


         from
        (
        select SC_AGE_YEARS, FWC,
        FWC/".$numYears." as FWCANNUAL,

        CASE
        WHEN K2Q33A = 1 AND K2Q33B = 1 	THEN  1
        WHEN K2Q33A = 2 AND K2Q33B = 1 	THEN  1
        WHEN K2Q33A IN (1,-99) AND K2Q33B IN (2,-99) THEN  2
        WHEN K2Q33A IN (2,-99) AND K2Q33B IN (2,-99) THEN  2
        END as ANXIETY,
        CASE
        WHEN K2Q32A = 1 AND K2Q32B = 1 	THEN 1
        WHEN K2Q32A = 2 AND K2Q32B = 1 	THEN 1
        WHEN K2Q32A IN (1,-99) AND K2Q32B IN (2,-99) THEN 2
        WHEN K2Q32A IN (2,-99) AND K2Q32B IN (2,-99) THEN 2
        END as DEPRESSION,
        
        CASE
        WHEN K2Q34A = 1 AND K2Q34B = 1 	THEN  1
        WHEN K2Q34A = 2 AND K2Q34B = 1 	THEN  1
        WHEN K2Q34A IN (1,-99) AND K2Q34B IN (2,-99) THEN  2
        WHEN K2Q34A IN (2,-99) AND K2Q34B IN (2,-99) THEN  2
        END as BEHAVIOR,

        CASE
        WHEN K2Q31A = 1 AND K2Q31B = 1 	THEN  1
        WHEN K2Q31A = 2 AND K2Q31B = 1 	THEN  1
        WHEN K2Q31A IN (1,-99) AND K2Q31B IN (2,-99) THEN  2
        WHEN K2Q31A IN (2,-99) AND K2Q31B IN (2,-99) THEN  2
        END as ADHD,

        CASE
        WHEN K2Q35A = 1 AND K2Q35B = 1 	THEN  1
        WHEN K2Q35A = 2 AND K2Q35B = 1 	THEN  1
        WHEN K2Q35A IN (1,-99) AND K2Q35B IN (2,-99) THEN  2
        WHEN K2Q35A IN (2,-99) AND K2Q35B IN (2,-99) THEN  2
        END as AUTISM,

        CASE
        WHEN K2Q36A = 1 AND K2Q36B = 1 THEN  1
        WHEN K2Q36A = 2 AND K2Q36B = 1 	THEN  1
        WHEN K2Q36A IN (1,-99) AND K2Q36B IN (2,-99) THEN  2
        WHEN K2Q36A IN (2,-99) AND K2Q36B IN (2,-99) THEN  2
        END as DEVDELAY,

        CASE
        WHEN K2Q30A = 1 AND K2Q30B = 1 	THEN  1
        WHEN K2Q30A = 2 AND K2Q30B = 1 	THEN  1
        WHEN K2Q30A IN (1,-99) AND K2Q30B IN (2,-99) THEN  2
        WHEN K2Q30A IN (2,-99) AND K2Q30B IN (2,-99) THEN  2
        END as LEARNDIS,

        CASE
        WHEN K2Q37A = 1 AND K2Q37B = 1 	THEN  1
        WHEN K2Q37A = 2 AND K2Q37B = 1 	THEN  1
        WHEN K2Q37A IN (1,-99) AND K2Q37B IN (2,-99) THEN  2
        WHEN K2Q37A IN (2,-99) AND K2Q37B IN (2,-99) THEN  2
        END as SPEECH

        from (";

       $yearsSelect = array();
       foreach($years as $y){
           $tmpSql = "select FWC,SC_AGE_YEARS,K2Q30A,K2Q30B,K2Q31A,K2Q31B,K2Q32A,K2Q32B,K2Q33A,K2Q33B,K2Q34A,K2Q34B,K2Q35A,K2Q35B,
        K2Q36A,K2Q36B,K2Q37A,K2Q37B from nsch_".$y."_topical n".$y."
        where n".$y.".FIPSST = 50 and SC_AGE_YEARS < 6";
           $yearsSelect[] = $tmpSql;
       }

        //create the union statement
       $dataSelect = implode(' UNION ALL ',$yearsSelect);
       $sql .= " ".$dataSelect." ".

           ") as a
        ) as b
        ) as c group by ANY_BEM";

       $query = $this->db->query($sql);
       return $query->result_array();
    }

   function getPreventiveDental($reportYears = '', $reportAges = ''){
        $years = explode('~',$reportYears);
        $ages = explode('~',$reportAges);
        $agesIn = implode(',',$ages);
        //get the number of years for the analyis, to use when combining weighted values
        $numYears = count($years);
        $sql = " select HAVYC_AGE, PrevDent_1618, count(*) as Frequency, SUM(FWCANNUAL) as WeightedFrequency from 
        (
        Select 
        K4Q30_R,
        CASE
        WHEN SC_AGE_YEARS IN (1,2) THEN 1
        WHEN SC_AGE_YEARS IN (3,4,5)	THEN 2
        WHEN SC_AGE_YEARS IN (6,7,8) THEN 3
        END as HAVYC_AGE,

        CASE
        WHEN DENTISTVISIT in (2,3) 	then 1
        WHEN DENTISTVISIT = 1 then 2
        WHEN K4Q30_R = 3 then 2
        WHEN SC_AGE_YEARS < 1 then -99
        END as PrevDent_1618,

        FWC/".$numYears." as FWCANNUAL,

        SC_AGE_YEARS

        from ( ";

        $yearsSelect = array();
        foreach($years as $y){
             $tmpSql = "select HHID,FWC,SC_AGE_YEARS,DENTISTVISIT,K4Q30_R, FWC/3 as FWCANNUAL from nsch_".$y."_topical n".$y."
             where n".$y.".FIPSST = 50 and SC_AGE_YEARS in (".$agesIn.") and K4Q30_R != -99";
             $yearsSelect[] = $tmpSql;
        }

        //create the union statement
        $dataSelect = implode(' UNION ALL ',$yearsSelect);

        $sql .= " ".$dataSelect." ".

         " ) as a 

        ) as b where PrevDent_1618 is not null 

         group by HAVYC_AGE, PrevDent_1618";

        $query = $this->db->query($sql);
   	    return $query->result_array();
    }  

   function getResidentialMobility(){
      $sql = "select RES_MOB, count(*) as Frequency, SUM(FWCANNUAL) as WeightedFrequency from 

        (

        Select 

        CASE
        WHEN K11Q43R IN (0,1,2,3) THEN 2
        WHEN K11Q43R IN (4,5,6,7,8,9,10,11,12,13,14,15) THEN  1
        WHEN K11Q43R IN (-99) THEN  -99
        END as RES_MOB,

        FWC/3 as FWCANNUAL

        from (

        select FWC,SC_AGE_YEARS,K11Q43R, FWC/3 as FWCANNUAL from nsch_2019_topical n2019
        where n2019.FIPSST = 50 and SC_AGE_YEARS < 9

        UNION ALL

        select FWC,SC_AGE_YEARS,K11Q43R, FWC/3 as FWCANNUAL from nsch_2017_topical n2017
        where n2017.FIPSST = 50 and SC_AGE_YEARS  < 9

        UNION ALL

        select FWC,SC_AGE_YEARS,K11Q43R, FWC/3 as FWCANNUAL from nsch_2018_topical n2018
        where n2018.FIPSST = 50 and SC_AGE_YEARS  < 9

        ) as a 

        ) as b where RES_MOB != -99 group by RES_MOB ";
    }
   
   
}